<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EmailOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class EmailOtpController extends Controller
{
    public function __construct(private readonly EmailOtpService $service)
    {
    }

    public function showVerifyForm(Request $request): RedirectResponse|View
    {
        try {
            $pending = $this->service->pending($request);
        } catch (ValidationException) {
            return redirect()->route('login');
        }

        $user = User::find($pending['user_id'] ?? null);
        if (! $user) {
            $this->service->clear($request);

            return redirect()->route('login');
        }

        $obfuscatedEmail = preg_replace_callback('/^([^@]+)@(.*)$/', function ($matches) {
            $local = $matches[1];
            $domain = $matches[2];

            if (strlen($local) <= 2) {
                return str_repeat('*', strlen($local)) . '@' . $domain;
            }

            return substr($local, 0, 2) . str_repeat('*', max(strlen($local) - 3, 1)) . substr($local, -1) . '@' . $domain;
        }, $user->email);

        return view('auth.verify-otp', [
            'pendingContext' => $pending['context'],
            'userEmail' => $obfuscatedEmail,
        ]);
    }

    public function send(Request $request): RedirectResponse
    {
        try {
            $this->service->pending($request);
        } catch (ValidationException) {
            return redirect()->route('login');
        }

        $this->service->resend($request);

        return back()->with('status', __('We sent a new verification code to your email.'));
    }

    public function verify(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $result = $this->service->verify($request, $validated['code']);

        $user = $result['user'];

        if ($result['context'] === EmailOtpService::CONTEXT_SIGNUP && ! $user->hasVerifiedEmail()) {
            $user->forceFill([
                'email_verified_at' => now(),
            ])->save();
        }

        Auth::login($user, $result['remember'] ?? false);
        $request->session()->regenerate();

        if (! empty($result['intended'])) {
            $request->session()->put('url.intended', $result['intended']);
        }

        return redirect()->intended(route('dashboard', absolute: false))
            ->with('status', __('Email verification successful.'));
    }
}
