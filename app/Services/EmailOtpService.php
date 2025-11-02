<?php

namespace App\Services;

use App\Models\EmailOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PHPMailer\PHPMailer\Exception as MailException;

class EmailOtpService
{
    public const SESSION_KEY = 'auth.email_otp';
    public const CONTEXT_SIGNUP = 'signup';
    public const CONTEXT_LOGIN = 'login';
    public const EXPIRY_MINUTES = 10;

    public function __construct(private readonly OtpMailer $mailer)
    {
    }

    public function begin(
        Request $request,
        User $user,
        string $context,
        ?string $intended = null,
        string $errorField = 'code',
        bool $remember = false
    ): void
    {
        $code = $this->generateCode();

        EmailOtp::updateOrCreate(
            ['user_id' => $user->id],
            [
                'code_hash' => Hash::make($code),
                'expires_at' => now()->addMinutes(self::EXPIRY_MINUTES),
                'attempts' => 0,
                'last_sent_at' => now(),
            ],
        );

        $request->session()->put(self::SESSION_KEY, [
            'user_id' => $user->id,
            'context' => $context,
            'intended' => $intended,
            'remember' => $remember,
        ]);

        $this->sendMail($user, $code, $errorField);
    }

    public function resend(Request $request): void
    {
        $pending = $this->pending($request);

        $user = User::find($pending['user_id']);
        if (! $user) {
            $this->clear($request);
            throw ValidationException::withMessages([
                'code' => __('We could not find an account for verification.'),
            ]);
        }

        $this->begin(
            $request,
            $user,
            $pending['context'],
            $pending['intended'],
            'code',
            $pending['remember']
        );
    }

    /**
     * @return array{user: User, context: string, intended: ?string, remember: bool}
     */
    public function verify(Request $request, string $code): array
    {
        $pending = $this->pending($request);

        $user = User::find($pending['user_id']);
        if (! $user) {
            $this->clear($request);

            throw ValidationException::withMessages([
                'code' => __('We could not find an account for verification.'),
            ]);
        }

        $otp = EmailOtp::where('user_id', $user->id)->first();
        if (! $otp) {
            throw ValidationException::withMessages([
                'code' => __('Request a new verification code to continue.'),
            ]);
        }

        if ($otp->expires_at && $otp->expires_at->isPast()) {
            throw ValidationException::withMessages([
                'code' => __('This verification code has expired. Request a new one.'),
            ]);
        }

        if (! Hash::check($code, $otp->code_hash)) {
            $otp->increment('attempts');

            throw ValidationException::withMessages([
                'code' => __('The verification code you entered is incorrect.'),
            ]);
        }

        $otp->delete();

        $this->clear($request);

        return [
            'user' => $user,
            'context' => $pending['context'],
            'intended' => $pending['intended'] ?? null,
            'remember' => $pending['remember'],
        ];
    }

    public function pending(Request $request): array
    {
        $pending = $request->session()->get(self::SESSION_KEY);

        if (! is_array($pending) || empty($pending['user_id']) || empty($pending['context'])) {
            throw ValidationException::withMessages([
                'code' => __('Start the verification process again.'),
            ]);
        }

        return [
            'user_id' => $pending['user_id'],
            'context' => $pending['context'],
            'intended' => $pending['intended'] ?? null,
            'remember' => (bool) ($pending['remember'] ?? false),
        ];
    }

    public function clear(Request $request): void
    {
        $request->session()->forget(self::SESSION_KEY);
    }

    private function generateCode(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    private function sendMail(User $user, string $code, string $errorField): void
    {
        try {
            $this->mailer->send($user->email, $user->name ?? $user->email, $code);
        } catch (MailException $exception) {
            throw ValidationException::withMessages([
                $errorField => __('We were unable to send a verification email. Please try again later.'),
            ]);
        }
    }
}
