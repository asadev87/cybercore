{{-- resources/views/account/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h3 class="mb-4">Account</h3>

  {{-- Flash messages --}}
  @if (session('status') === 'profile-updated')
    <div class="alert alert-success">Profile updated.</div>
  @endif
  @if (session('status') === 'password-updated')
    <div class="alert alert-success">Password updated.</div>
  @endif

  <div class="row g-4">
    {{-- Profile --}}
    <div class="col-lg-6">
      <div class="card shadow-sm h-100">
        <div class="card-header">Profile</div>
        <div class="card-body">
          <form method="POST" action="{{ route('account.profile.update') }}" class="vstack gap-3">
            @csrf
            @method('PATCH')

            <div>
              <label class="form-label">Name</label>
              <input name="name" type="text" class="form-control @error('name') is-invalid @enderror"
                     value="{{ old('name', $user->name) }}" required>
              @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div>
              <label class="form-label">Email</label>
              <input name="email" type="email" class="form-control @error('email') is-invalid @enderror"
                     value="{{ old('email', $user->email) }}" required>
              @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
              @if($user->hasVerifiedEmail() === false)
                <div class="form-text text-danger">Unverified — check your inbox for a verification link.</div>
              @endif
            </div>

            <button class="btn btn-primary">Save changes</button>
          </form>
        </div>
      </div>
    </div>

    {{-- Change Password --}}
    <div class="col-lg-6">
      <div class="card shadow-sm h-100">
        <div class="card-header">Change password</div>
        <div class="card-body">
          <form method="POST" action="{{ route('account.password.update') }}" class="vstack gap-3">
            @csrf
            @method('PUT')

            <div>
              <label class="form-label">Current password</label>
              <input name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" required>
              @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div>
              <label class="form-label">New password</label>
              <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" required>
              @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div>
              <label class="form-label">Confirm new password</label>
              <input name="password_confirmation" type="password" class="form-control" required>
            </div>

            <button class="btn btn-outline-danger">Update password</button>
          </form>
          <div class="form-text mt-2">
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
