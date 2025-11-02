<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EmailOtpService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    public function __construct(private readonly EmailOtpService $otpService)
    {
    }

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'account_type' => ['required', Rule::in(['user', 'lecturer'])],
        ]);

        $accountType = $request->input('account_type', 'user');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($accountType === 'lecturer') {
            $lecturerRole = Role::firstOrCreate(['name' => 'lecturer']);
            $user->assignRole($lecturerRole);
        }

        event(new Registered($user));

        $this->otpService->begin(
            $request,
            $user,
            EmailOtpService::CONTEXT_SIGNUP,
            route('dashboard', absolute: false),
            'email'
        );

        return redirect()->route('verification.otp.notice')
            ->with('status', __('We sent a verification code to your email.'));
    }
}
