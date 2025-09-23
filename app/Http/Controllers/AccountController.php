<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        return view('account.index', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'  => ['required','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
        ]); // validation per docs. :contentReference[oaicite:1]{index=1}

        // If email is changing, require re-verification (if you use MustVerifyEmail)
        $emailChanged = $data['email'] !== $user->email;

        $user->name  = $data['name'];
        $user->email = $data['email'];

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($emailChanged && method_exists($user, 'sendEmailVerificationNotification')) {
            $user->sendEmailVerificationNotification(); // built-in verification flow
        } // email verification docs. :contentReference[oaicite:2]{index=2}

        return back()->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => ['required','current_password'],                  // checks against auth user
            'password'              => ['required', Password::defaults(), 'confirmed'], // strong default rules
        ]); // password rule docs. :contentReference[oaicite:3]{index=3}

        $request->user()->forceFill([
            'password' => Hash::make($request->input('password')),
        ])->save(); // hashing per docs. :contentReference[oaicite:4]{index=4}

        return back()->with('status', 'password-updated');
    }
}
