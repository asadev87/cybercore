{{-- resources/views/auth/verify-otp.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-sm">
        <div class="card-body p-4">
          <h4 class="mb-2">Verify your email</h4>
          @if (session('status'))
            <div class="alert alert-info">{{ session('status') }}</div>
          @endif
          <p class="text-secondary">
            @if (($pendingContext ?? null) === \App\Services\EmailOtpService::CONTEXT_LOGIN)
              Enter the 6-digit code we emailed you to finish signing in.
            @else
              Enter the 6-digit code we emailed you to finish creating your account.
            @endif
          </p>
          <form method="POST" action="{{ route('verification.otp.verify') }}" class="mb-3">
            @csrf
            <div class="mb-3">
              <label class="form-label">Verification Code</label>
              <input name="code" type="text" inputmode="numeric" maxlength="6" class="form-control @error('code') is-invalid @enderror" placeholder="000000" required>
              @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <button class="btn btn-primary w-100">Verify</button>
          </form>
          <form method="POST" action="{{ route('verification.otp.send') }}">
            @csrf
            <button class="btn btn-outline-danger w-100">Resend code</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
