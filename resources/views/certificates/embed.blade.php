{{-- resources/views/certificates/embed.blade.php --}}
@extends('layouts.app')

@section('content')
<section class="relative isolate overflow-hidden py-16">
  <div class="absolute inset-0 -z-10 bg-gradient-to-br from-slate-950 via-slate-900/90 to-slate-950 dark:from-slate-950 dark:via-slate-950/95 dark:to-slate-950"></div>
  <div class="absolute -left-32 top-10 h-72 w-72 rounded-full bg-primary/15 blur-3xl"></div>
  <div class="absolute -right-20 bottom-16 h-64 w-64 rounded-full bg-accent/15 blur-3xl"></div>

  @php
    $logoExists = file_exists(public_path('images/logo.png'));
    $signatureExists = file_exists(public_path('images/signature.png'));
  @endphp

  <div class="container">
    <div class="mx-auto max-w-4xl">
      <div class="sera-card p-10 lg:p-14">
        <div class="flex flex-col items-center gap-6 text-center">
          @if($logoExists)
            <img src="{{ asset('images/logo.png') }}" alt="Organization Logo" class="h-16 w-auto">
          @endif

          <div class="rounded-3xl border border-border/60 bg-secondary px-5 py-2 text-xs font-semibold uppercase tracking-[0.35em] text-muted-foreground dark:border-white/15 dark:bg-white/10 dark:text-white/80">Certificate of achievement</div>

          <div class="flex items-center gap-4 text-sm text-muted-foreground">
            <div class="h-px flex-1 bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
            <span>Issued {{ $certificate->created_at->format('F j, Y') }}</span>
            <div class="h-px flex-1 bg-gradient-to-l from-transparent via-white/30 to-transparent"></div>
          </div>

          <div class="space-y-3">
            <p class="text-sm uppercase tracking-[0.35em] text-muted-foreground">This certifies that</p>
            <h1 class="text-4xl font-semibold tracking-tight text-foreground dark:text-white">{{ $certificate->user->name }}</h1>
          </div>

          <div class="space-y-2 text-sm text-muted-foreground">
            <p>has successfully completed the module</p>
            <p class="text-2xl font-semibold text-foreground dark:text-white">{{ $certificate->module->title }}</p>
          </div>

          <div class="rounded-2xl border border-border/60 bg-secondary px-4 py-2 text-sm text-muted-foreground dark:border-white/10 dark:bg-white/10 dark:text-white/80">
            @php($attempt = $certificate->attempt)
            @if($attempt && isset($attempt->score))
              Final score: <span class="font-semibold text-white">{{ $attempt->score }}%</span>
            @else
              Score not recorded for this attempt
            @endif
          </div>

          <div class="grid gap-4 text-left text-sm text-muted-foreground sm:grid-cols-2">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 dark:bg-white/10">
              <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Learner ID</p>
              <p class="mt-2 font-medium text-foreground dark:text-white">{{ $certificate->user->email }}</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4 dark:bg-white/10">
              <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Certificate No.</p>
              <p class="mt-2 font-medium text-foreground dark:text-white">{{ $certificate->serial }}</p>
            </div>
          </div>

          <div class="mt-10 flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-col items-center gap-2 text-sm text-muted-foreground">
              <div class="h-14 w-36 border-b border-border dark:border-white/40"></div>
              @if($signatureExists)
                <img src="{{ asset('images/signature.png') }}" alt="Authorized Signature" class="h-12 w-auto">
              @else
                <span class="text-xs uppercase tracking-[0.3em]">Authorized Signature</span>
              @endif
              <p class="tracking-[0.28em] uppercase">Authorized Signatory</p>
            </div>
            <div class="flex flex-col items-center gap-2 text-sm text-muted-foreground">
              <div class="h-14 w-36 border-b border-border dark:border-white/40"></div>
              <p class="tracking-[0.28em] uppercase">CyberCore</p>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-6 flex flex-wrap items-center justify-between gap-4 text-sm text-muted-foreground">
        <div>Verified by CyberCore. Keep your certificate number for record verification.</div>
        <div class="flex flex-wrap items-center gap-3">
          <a href="{{ route('certificates.download', $certificate) }}" class="sera-btn-primary text-xs">Download PDF</a>
          <a href="{{ route('certificates.view', $certificate) }}" class="sera-btn text-xs">Open share view</a>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection


