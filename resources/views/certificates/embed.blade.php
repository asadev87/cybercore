{{-- resources/views/certificates/embed.blade.php --}}
@extends('layouts.app')

@section('content')
<section class="bg-white py-16 text-slate-900">
  @php
    $logoExists = file_exists(public_path('images/logo.png'));
    $signaturePath = public_path('images/signature.png');
    $signatureExists = file_exists($signaturePath);
    $signatureUrl = $signatureExists ? asset('images/signature.png').'?v='.filemtime($signaturePath) : null;
    $secondarySignaturePath = public_path('images/signature2.png');
    $secondarySignatureExists = file_exists($secondarySignaturePath);
    $secondarySignatureUrl = $secondarySignatureExists ? asset('images/signature2.png').'?v='.filemtime($secondarySignaturePath) : null;
  @endphp

  <div class="container">
    <div class="mx-auto max-w-4xl">
      <div class="rounded-3xl border border-blue-100 bg-white p-10 shadow-[0_30px_60px_-35px_rgba(37,99,235,0.35)] lg:p-14" style="color:#0f172a;">
        <div class="flex flex-col items-center gap-6 text-center">
          @if($logoExists)
            <img src="{{ asset('images/logo.png') }}" alt="Organization Logo" class="h-16 w-auto drop-shadow-sm">
          @endif

          <div class="rounded-full border border-blue-300 bg-blue-50 px-6 py-2 text-xs font-semibold uppercase tracking-[0.35em] text-blue-700">Certificate of Achievement</div>

          <div class="flex items-center gap-4 text-sm text-slate-600">
            <div class="h-px flex-1 bg-gradient-to-r from-transparent via-blue-300/70 to-transparent"></div>
            <span>Issued {{ $certificate->created_at->format('F j, Y') }}</span>
            <div class="h-px flex-1 bg-gradient-to-l from-transparent via-blue-300/70 to-transparent"></div>
          </div>

          <div class="space-y-3">
            <p class="text-sm uppercase tracking-[0.35em] text-slate-600">This certifies that</p>
            <h1 class="text-4xl font-semibold tracking-tight text-slate-900">{{ $certificate->user->name }}</h1>
          </div>

          <div class="space-y-2 text-sm text-slate-600">
            <p>has successfully completed the module</p>
            <p class="text-2xl font-semibold text-slate-900">{{ $certificate->module->title }}</p>
          </div>

          <div class="rounded-2xl border border-blue-200 bg-blue-50 px-4 py-2 text-sm text-slate-900">
            @php($attempt = $certificate->attempt)
            @if($attempt && isset($attempt->score))
              Final score: <span class="font-semibold">{{ $attempt->score }}%</span>
            @else
              Score not recorded for this attempt
            @endif
          </div>

          <div class="grid gap-4 text-left text-sm text-slate-700 sm:grid-cols-2">
            <div class="rounded-2xl border border-blue-200 bg-white p-4 shadow-sm">
              <p class="text-xs font-semibold uppercase tracking-[0.28em] text-blue-700">Learner ID</p>
              <p class="mt-2 font-medium text-slate-900">{{ $certificate->user->email }}</p>
            </div>
            <div class="rounded-2xl border border-blue-200 bg-white p-4 shadow-sm">
              <p class="text-xs font-semibold uppercase tracking-[0.28em] text-blue-700">Certificate No.</p>
              <p class="mt-2 font-medium text-slate-900">{{ $certificate->serial }}</p>
            </div>
          </div>

          <div class="mt-10 flex flex-col gap-8 text-slate-700 sm:flex-row sm:justify-center sm:gap-16">
            <div class="flex flex-col items-center gap-3 text-center uppercase tracking-[0.28em] text-slate-900">
              <div class="flex h-24 w-64 items-center justify-center">
                @if($signatureExists)
                  <img src="{{ $signatureUrl }}" alt="Executive Signature" class="h-20 w-auto drop-shadow-md">
                @else
                  <span class="text-xs uppercase tracking-[0.3em] text-blue-700">Executive Signature</span>
                @endif
              </div>
              <div class="w-48 border-b-2 border-slate-900"></div>
              <p class="text-xs uppercase tracking-[0.22em] text-slate-700">Executive Manager from CyberCore</p>
            </div>
            <div class="flex flex-col items-center gap-3 text-center uppercase tracking-[0.28em] text-slate-900">
              <div class="flex h-24 w-64 items-center justify-center">
                @if($secondarySignatureExists)
                  <img src="{{ $secondarySignatureUrl }}" alt="Department Head Signature" class="h-20 w-auto drop-shadow-md">
                @else
                  <span class="text-xs uppercase tracking-[0.3em] text-blue-700">Signature</span>
                @endif
              </div>
              <div class="w-48 border-b-2 border-slate-900"></div>
              <p class="font-semibold tracking-[0.22em]">IDRIS BIN MOHAMED MOBIN</p>
              <p class="text-xs tracking-[0.18em] text-slate-700">Ketua Jabatan Teknologi Maklumat dan Komunikasi</p>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-6 flex flex-col gap-4 text-sm text-slate-700 sm:flex-row sm:items-center sm:justify-between">
        <div>Congratulations! {{ $certificate->user->name }} has successfully completed the module {{ $certificate->module->title }}.</div>
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
          <a href="{{ route('certificates.download', $certificate) }}" class="sera-btn-primary bg-blue-500 text-xs text-black hover:bg-blue-400 hover:text-black sm:w-auto">Download PDF</a>
          <a href="{{ route('certificates.view', $certificate) }}" class="sera-btn border border-slate-900 bg-white text-xs text-slate-900 hover:bg-slate-100 hover:border-slate-900 dark:border-white dark:bg-white dark:text-slate-900 dark:hover:bg-slate-100 dark:hover:border-white sm:w-auto">Open share view</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
