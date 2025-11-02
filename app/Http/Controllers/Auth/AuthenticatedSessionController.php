<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\EmailOtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $user = Auth::user();

        // Capture intended destination before we alter auth state
        $intended = $request->session()->pull('url.intended', route('dashboard', absolute: false));

        // Ensure the user is not left authenticated if sending the OTP fails.
        // We already have the $user reference, so log out first to enforce OTP.
        Auth::logout();

        // Start OTP flow (may throw ValidationException if email fails to send)
        $this->otpService->begin(
            $request,
            $user,
            EmailOtpService::CONTEXT_LOGIN,
            $intended,
            'email',
            $request->boolean('remember')
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
}
