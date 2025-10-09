{{-- resources/views/learn/index.blade.php --}}
@extends('layouts.app')

@section('content')
@php
  $moduleCount   = $modules->count();
  $moduleLabel   = $moduleCount === 1 ? 'module' : 'modules';
  $completed     = $modules->filter(fn ($mod) => (int) ($progress[$mod->id] ?? 0) >= 100)->count();
  $inProgress    = $moduleCount - $completed;
  $defaultNotes  = (array) (config('module_notes.defaults') ?? []);
  $descriptionCopy = (array) (config('module_notes.descriptions') ?? []);
@endphp

<section class="space-y-10">
  <header class="card-surface p-6 sm:p-8">
    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
      <div class="space-y-3">
        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-muted-foreground">Module catalog</p>
        <h1 class="text-3xl font-semibold tracking-tight">Continue your cybersecurity journey</h1>
        <p class="max-w-2xl text-sm text-muted-foreground">
          Dip back into an in‑progress path or start something fresh. Everything you complete here feeds into your overall readiness score.
        </p>
      </div>
      <div class="grid gap-3 sm:grid-cols-3">
        <div class="rounded-2xl border border-border/60 bg-secondary/40 px-4 py-3 text-center">
          <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Total</p>
          <p class="text-xl font-semibold">{{ $moduleCount }}</p>
          <p class="text-xs text-muted-foreground">{{ $moduleLabel }}</p>
        </div>
        <div class="rounded-2xl border border-border/60 bg-emerald-500/10 px-4 py-3 text-center text-emerald-700 dark:text-emerald-200">
          <p class="text-xs font-semibold uppercase tracking-[0.28em]">Completed</p>
          <p class="text-xl font-semibold">{{ $completed }}</p>
          <p class="text-xs text-emerald-700/80 dark:text-emerald-200/80">Great work</p>
        </div>
        <div class="rounded-2xl border border-border/60 bg-primary/10 px-4 py-3 text-center text-primary">
          <p class="text-xs font-semibold uppercase tracking-[0.28em]">In progress</p>
          <p class="text-xl font-semibold">{{ $inProgress }}</p>
          <p class="text-xs text-primary/80">Pick up where you left off</p>
        </div>
      </div>
    </div>
  </header>

  <div class="space-y-6">
    <div class="grid gap-6 lg:grid-cols-2">
    @forelse ($modules as $m)
      @php
        $pct          = (int) ($progress[$m->id] ?? 0);
        $statusLabel  = $pct >= 100 ? 'Complete' : ($pct > 0 ? 'In progress' : 'Not started');
        $statusTone   = $pct >= 100 ? 'text-emerald-600 dark:text-emerald-300 bg-emerald-500/10 border-emerald-500/30' : 'text-primary bg-primary/10 border-primary/20';
        $summary      = (string) ($descriptionCopy[$m->slug] ?? $m->description);
        $summary      = mb_strlen($summary) > 180 ? rtrim(mb_substr($summary, 0, 177)) . '…' : $summary;
        $difficulty   = $difficultyLabels[$m->id] ?? 'Unrated';
        $note         = $m->note ?: ($defaultNotes[$m->slug] ?? null);
      @endphp

      <article class="card-surface p-6 sm:p-8">
        <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
          <div class="space-y-5 md:flex-1">
            <div class="flex flex-wrap items-start justify-between gap-4">
              <div>
                <h2 class="text-2xl font-semibold tracking-tight">{{ $m->title }}</h2>
                <p class="mt-2 text-sm text-muted-foreground">{{ $summary }}</p>
              </div>
              <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-wide {{ $statusTone }}">
                {{ $statusLabel }}
              </span>
            </div>

            <dl class="grid gap-4 sm:grid-cols-3">
              <div class="rounded-lg border border-border/60 bg-secondary/50 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-[0.24em] text-muted-foreground">Difficulty</dt>
                <dd class="mt-1 text-sm font-medium text-foreground">{{ $difficulty }}</dd>
              </div>
              <div class="rounded-lg border border-border/60 bg-secondary/50 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-[0.24em] text-muted-foreground">Pass score</dt>
                <dd class="mt-1 text-sm font-medium text-foreground">{{ $m->pass_score }}%</dd>
              </div>
              <div class="rounded-lg border border-border/60 bg-secondary/50 px-4 py-3">
                <dt class="text-xs font-semibold uppercase tracking-[0.24em] text-muted-foreground">Your progress</dt>
                <dd class="mt-1 text-sm font-medium text-foreground">{{ $pct }}%</dd>
              </div>
            </dl>

            @if($note)
              <div class="rounded-2xl border border-amber-300/40 bg-amber-100/40 px-4 py-3 text-xs text-amber-800 dark:border-amber-400/30 dark:bg-amber-500/10 dark:text-amber-100">
                <span class="font-semibold uppercase tracking-wide text-amber-700 dark:text-amber-200">Prep note</span>
                <span class="block pt-1 text-amber-700/80 dark:text-amber-200/80">{{ $note }}</span>
              </div>
            @endif

            <div class="space-y-2">
              <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
                <span>Overall completion</span>
                <span>{{ $pct }}%</span>
              </div>
              <div class="h-2 w-full rounded-full bg-secondary">
                <div
                  class="h-2 rounded-full {{ $pct >= 100 ? 'bg-emerald-500' : 'bg-primary' }} transition-all duration-500"
                  style="width: {{ $pct }}%"
                  role="progressbar"
                  aria-valuenow="{{ $pct }}"
                  aria-valuemin="0"
                  aria-valuemax="100"
                  aria-label="Module progress"
                ></div>
              </div>
            </div>
          </div>

          <div class="flex w-full flex-col gap-3 md:w-56">
            <form action="{{ route('quiz.start', $m) }}" method="POST">
              @csrf
              <button class="btn btn-primary w-full">{{ $pct > 0 ? 'Resume module' : 'Begin module' }}</button>
            </form>
            @can('update', $m)
              <a href="{{ route('admin.modules.builder', $m) }}" class="btn btn-muted w-full text-sm">Manage content</a>
            @endcan
          </div>
        </div>
      </article>
    @empty
      <div class="card-muted flex items-center justify-center px-6 py-12 text-sm text-muted-foreground lg:col-span-2">
        No modules yet. Once a lecturer publishes a module, it will appear here automatically.
      </div>
    @endforelse
    </div>
  </div>
</section>
@endsection
