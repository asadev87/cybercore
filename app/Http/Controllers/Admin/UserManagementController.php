<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->with('roles')->orderBy('name');

        if ($search = trim($request->input('search', ''))) {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(12)->withQueryString();
        $roles = Role::orderBy('name')->get();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $action = $request->input('action');

        if ($action === 'role') {
            $validated = $request->validate([
                'role' => ['required', 'exists:roles,name'],
            ]);

            if ($request->user()->is($user) && $validated['role'] !== 'admin') {
                return back()->withErrors(['role' => __('You must keep the admin role on your own account.')]);
            }

            $role = Role::where('name', $validated['role'])->first();
            $user->syncRoles([$validated['role']]);
            if ($role) {
                $user->role_id = $role->id;
                $user->save();
            }

            return back()->with('status', __('Role updated.'));
        }

        if ($action === 'verify') {
            if (! $user->hasVerifiedEmail()) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }

            return back()->with('status', __('User marked as verified.'));
        }

        if ($action === 'unverify') {
            $user->forceFill(['email_verified_at' => null])->save();

            return back()->with('status', __('Verification reset.'));
        }

        return back()->withErrors(['action' => __('Unsupported action.')]);
    }
}
