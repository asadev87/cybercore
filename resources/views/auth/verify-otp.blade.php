{{-- resources/views/auth/verify-otp.blade.php --}}

@extends('layouts.app')

@php
  $codePrefill = str_split(old('code', ''));
@endphp

@section('content')
<section class="relative isolate overflow-hidden py-16 sm:py-24">
  <div class="absolute inset-0 -z-10 bg-gradient-to-br from-slate-100 via-white to-blue-100 dark:from-slate-950 dark:via-slate-900 dark:to-slate-950"></div>

  <div class="mx-auto flex min-h-[60vh] max-w-3xl flex-col items-center justify-center px-4">
    <div class="w-full max-w-xl rounded-[32px] border border-white/80 bg-white/90 p-10 shadow-2xl backdrop-blur dark:border-white/10 dark:bg-slate-900/80">
      <div class="mb-6 flex items-center gap-2">
        <span class="h-3.5 w-3.5 rounded-full bg-[#ff5f57]"></span>
        <span class="h-3.5 w-3.5 rounded-full bg-[#febc2e]"></span>
        <span class="h-3.5 w-3.5 rounded-full bg-[#28c840]"></span>
      </div>

      <div class="mb-8 flex items-center gap-4">
        <div class="relative flex h-16 w-16 items-center justify-center rounded-2xl bg-primary/10 shadow-inner">
          <img src="{{ asset('images/logo.png') }}" alt="CyberCore" class="h-12 w-12 object-contain" onerror="this.style.display='none'">
        </div>
        <div>
          <p class="text-xs font-semibold uppercase tracking-[0.3em] text-muted-foreground">Two-step verification</p>
          <h1 class="mt-2 text-2xl font-semibold text-foreground">Check your inbox</h1>
        </div>
      </div>

      @if (session('status'))
        <div class="mb-6 rounded-2xl border border-primary/20 bg-primary/10 px-4 py-3 text-sm text-primary">
          {{ session('status') }}
        </div>
      @endif

      <p class="text-sm text-muted-foreground">
        @if (($pendingContext ?? null) === \App\Services\EmailOtpService::CONTEXT_LOGIN)
          We emailed a 6-digit code to <span class="font-medium text-foreground">{{ $userEmail ?? 'your account' }}</span>. Enter it below to finish signing in.
        @else
          We emailed a 6-digit code to <span class="font-medium text-foreground">{{ $userEmail ?? 'your account' }}</span>. Enter it below to complete your registration.
        @endif
      </p>

      <form id="otp-form" method="POST" action="{{ route('verification.otp.verify') }}" class="mt-8 space-y-6">
        @csrf
        <input type="hidden" name="code" id="otp-hidden" value="{{ old('code', '') }}">

        <label class="block text-xs font-semibold uppercase tracking-[0.18em] text-muted-foreground">Verification code</label>
        <div class="flex justify-center gap-3">
          @for ($i = 0; $i < 6; $i++)
            <input
              type="text"
              inputmode="numeric"
              maxlength="1"
              data-otp-input
              class="h-16 w-12 rounded-2xl border-2 border-black/60 dark:border-black bg-slate-200 dark:bg-slate-800 text-center text-2xl font-semibold text-foreground shadow-sm transition focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/30"
              value="{{ $codePrefill[$i] ?? '' }}"
              autocomplete="one-time-code"
            >
          @endfor
        </div>

        @error('code')
          <p class="text-sm font-medium text-destructive">{{ $message }}</p>
        @enderror

        <button type="submit" class="flex w-full items-center justify-center rounded-2xl bg-primary px-5 py-3 text-sm font-semibold uppercase tracking-wide text-white shadow-lg shadow-primary/25 transition hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/40">Verify &amp; continue</button>
      </form>

      <div class="mt-8 flex items-center justify-between text-sm text-muted-foreground">
        <span>Didn’t get the code?</span>
        <form method="POST" action="{{ route('verification.otp.send') }}">
          @csrf
          <button type="submit" class="font-semibold text-primary hover:text-primary/80">Resend email</button>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const form = document.getElementById('otp-form');
      const hidden = document.getElementById('otp-hidden');
      const inputs = Array.from(document.querySelectorAll('[data-otp-input]'));

      if (!inputs.length || !hidden || !form) {
        return;
      }

      const syncHidden = () => {
        hidden.value = inputs.map((input) => input.value ?? '').join('');
      };

      const focusNextEmpty = (startIndex) => {
        for (let i = startIndex; i < inputs.length; i++) {
          if (inputs[i].value === '') {
            inputs[i].focus();
            return;
          }
        }
      };

      inputs.forEach((input, index) => {
        input.addEventListener('focus', () => {
          input.select();
        });

        input.addEventListener('input', (event) => {
          const raw = (event.target.value || '').replace(/\D/g, '');

          if (!raw) {
            input.value = '';
            syncHidden();
            return;
          }

          const chars = raw.split('');
          input.value = chars.shift();

          let cursor = index + 1;
          while (chars.length && cursor < inputs.length) {
            inputs[cursor].value = chars.shift();
            cursor++;
          }

          if (index < inputs.length - 1) {
            focusNextEmpty(index + 1);
          }

          syncHidden();
        });

        input.addEventListener('keydown', (event) => {
          if (event.key === 'Backspace' && input.value === '' && index > 0) {
            inputs[index - 1].value = '';
            inputs[index - 1].focus();
            syncHidden();
            event.preventDefault();
          }
        });

        input.addEventListener('paste', (event) => {
          event.preventDefault();
          const pasted = (event.clipboardData?.getData('text') || '').replace(/\D/g, '');
          if (!pasted) {
            return;
          }

          const chars = pasted.split('');
          let cursor = index;
          while (chars.length && cursor < inputs.length) {
            inputs[cursor].value = chars.shift();
            cursor++;
          }

          if (cursor < inputs.length) {
            inputs[cursor].focus();
          } else {
            inputs[inputs.length - 1].focus();
          }

          syncHidden();
        });
      });

      form.addEventListener('submit', () => {
        syncHidden();
      });

      syncHidden();
      focusNextEmpty(0);
    });
  </script>
@endpush
