<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class EmailOtpController extends Controller
{
    public function showVerifyForm()
    {
        // You can swap to a Blade view later: return view('auth.verify-otp');
        return response('<form method="POST" action="/email/otp/verify">'.csrf_field().'
            <input name="otp" placeholder="Enter OTP">
            <button>Verify</button>
        </form>');
    }

    public function send(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        // simple throttle example – you already defined rate limiters in routes/web.php
        if (RateLimiter::tooManyAttempts('otp:'.$request->ip(), 5)) {
            throw ValidationException::withMessages(['email' => 'Too many attempts, try again later.']);
        }

        $otp = random_int(100000, 999999);
        $request->session()->put('email_otp', $otp);

        Mail::raw("Your CyberCore OTP is: {$otp}", function ($m) use ($request) {
            $m->to($request->input('email'))->subject('CyberCore OTP');
        });

        return back()->with('status', 'OTP sent to your email.');
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|digits:6']);
        $ok = $request->session()->pull('email_otp');

        if (!$ok || (string)$ok !== (string)$request->input('otp')) {
            throw ValidationException::withMessages(['otp' => 'Invalid OTP']);
        }

        return redirect()->intended('/dashboard')->with('status', 'Email verified.');
    }
}
