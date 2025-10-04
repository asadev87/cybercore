{{-- resources/views/account/index.blade.php --}}

@extends('layouts.app')

@section('content')
<section class="space-y-8">
  <header class="space-y-2">
    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-muted-foreground">Profile</p>
    <h1 class="text-3xl font-semibold tracking-tight">Account</h1>
    <p class="text-sm text-muted-foreground">Manage your personal information and password. Changes apply instantly across CyberCore.</p>
  </header>

  <div class="grid gap-6 lg:grid-cols-2">
    <article class="card-surface space-y-5 p-6">
      <header>
        <h2 class="text-lg font-semibold">Profile</h2>
      </header>

      @if (session('status') === 'profile-updated')
        <div class="rounded-lg border border-success/30 bg-success/10 px-4 py-2 text-sm font-medium text-success">Profile updated.</div>
      @endif

      <form method="POST" action="{{ route('account.profile.update') }}" class="space-y-4">
        @csrf
        @method('PATCH')

        <div class="space-y-2">
          <label class="input-label" for="name">Name</label>
          <input id="name" name="name" type="text" class="input-field" value="{{ old('name', $user->name) }}" required>
          <x-input-error :messages="$errors->get('name')" />
        </div>

        <div class="space-y-2">
          <label class="input-label" for="email">Email</label>
          <input id="email" name="email" type="email" class="input-field" value="{{ old('email', $user->email) }}" required>
          <x-input-error :messages="$errors->get('email')" />
          @if($user->hasVerifiedEmail() === false)
            <p class="input-hint text-destructive">Unverified — check your inbox for a verification link.</p>
          @endif
        </div>

        <button class="btn btn-primary">Save changes</button>
      </form>
    </article>

    <article class="card-surface space-y-5 p-6">
      <header class="flex items-center justify-between">
        <h2 class="text-lg font-semibold">Change password</h2>
        @if (session('status') === 'password-updated')
          <span class="rounded-full bg-success/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-success">Updated</span>
        @endif
      </header>

      <form method="POST" action="{{ route('account.password.update') }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="space-y-2">
          <label class="input-label" for="current_password">Current password</label>
          <input id="current_password" name="current_password" type="password" class="input-field" required>
          <x-input-error :messages="$errors->get('current_password')" />
        </div>

        <div class="space-y-2">
          <label class="input-label" for="password">New password</label>
          <input id="password" name="password" type="password" class="input-field" required>
          <x-input-error :messages="$errors->get('password')" />
          <p class="input-hint">Use at least 12 characters with a memorable phrase.</p>
        </div>

        <div class="space-y-2">
          <label class="input-label" for="password_confirmation">Confirm new password</label>
          <input id="password_confirmation" name="password_confirmation" type="password" class="input-field" required>
        </div>

        <button class="btn btn-outline">Update password</button>
      </form>
    </article>
  </div>
</section>
@endsection
