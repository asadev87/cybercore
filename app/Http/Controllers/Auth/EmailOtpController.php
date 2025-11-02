<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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

        return view('auth.verify-otp', [
            'pendingContext' => $pending['context'],
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
