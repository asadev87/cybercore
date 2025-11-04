<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\UserLogin;
use App\Services\EmailOtpService;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(private readonly EmailOtpService $otpService)
    {
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $defaultRedirect = route('dashboard', absolute: false);
        $remember = $request->boolean('remember');

        $requiresVerification = $user instanceof MustVerifyEmail;
        $isVerified = ! $requiresVerification || $user->hasVerifiedEmail();

        if ($isVerified) {
            $this->logLogin($request, $user, $remember, EmailOtpService::CONTEXT_LOGIN);
            $request->session()->regenerate();

            return redirect()->intended($defaultRedirect);
        }

        $intended = $request->session()->pull('url.intended', $defaultRedirect);

        // Ensure the user is not left authenticated if sending the OTP fails.
        Auth::logout();

        // Start OTP flow (may throw ValidationException if email fails to send)
        $this->otpService->begin(
            $request,
            $user,
            EmailOtpService::CONTEXT_LOGIN,
            $intended,
            'email',
            $remember
        );

        return redirect()->route('verification.otp.notice')
            ->with('status', __('We sent a verification code to your email.'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function logLogin(Request $request, $user, bool $remember, string $context): void
    {
        if (! Schema::hasTable('user_logins')) {
            return;
        }

        UserLogin::create([
            'user_id' => $user->id,
            'context' => $context,
            'remember' => $remember,
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 512),
            'logged_in_at' => now(),
        ]);
    }
}
