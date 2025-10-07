{{-- resources/views/learn/index.blade.php --}}
@extends('layouts.app')

@section('content')
@php
  $moduleCount = $modules->count();
  $moduleLabel = $moduleCount === 1 ? 'module' : 'modules';
@endphp

<section class="space-y-8">
  <header class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
    <div class="space-y-1">
      <p class="text-xs font-semibold uppercase tracking-[0.24em] text-muted-foreground">Module catalog</p>
      <h1 class="text-3xl font-semibold tracking-tight">Available modules</h1>
      <p class="max-w-2xl text-sm text-muted-foreground">Continue exploring cybersecurity skills. Your progress persists across devices so you can resume instantly.</p>
    </div>
    <div class="rounded-2xl border border-border/60 bg-secondary/40 px-4 py-3 text-sm text-muted-foreground shadow-sm">
      {{ $moduleCount }} {{ $moduleLabel }} available
    </div>
  </header>

  <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
    @forelse ($modules as $m)
      @php
        $pct = (int) ($progress[$m->id] ?? 0);
        $description = (string) $m->description;
        $summary = mb_strlen($description) > 140 ? rtrim(mb_substr($description, 0, 137)) . '…' : $description;
      @endphp
      <article class="card-surface flex h-full flex-col gap-6 p-6">
        <div class="space-y-3">
          <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">{{ $m->title }}</h2>
            <div class="flex items-center gap-2">
              <span class="rounded-full bg-secondary px-3 py-1 text-xs font-semibold uppercase tracking-wide text-secondary-foreground">{{ $pct >= 100 ? 'Complete' : 'In progress' }}</span>
              @can('update', $m)
                <a href="{{ route('admin.modules.builder', $m) }}" class="inline-flex items-center gap-1 rounded-full border border-primary/40 bg-primary/10 px-3 py-1 text-xs font-semibold text-primary transition hover:border-primary/60 hover:bg-primary/15">Manage</a>
              @endcan
            </div>
          </div>
          <p class="text-sm text-muted-foreground">{{ $summary }}</p>
        </div>

        <div class="space-y-2">
          <div class="flex items-center justify-between text-xs font-medium text-muted-foreground">
            <span>Progress</span>
            <span>{{ $pct }}%</span>
          </div>
          <div class="h-2 w-full rounded-full bg-secondary">
            <div
              class="h-2 rounded-full bg-primary transition-all duration-300"
              style="width: {{ $pct }}%"
              role="progressbar"
              aria-valuenow="{{ $pct }}"
              aria-valuemin="0"
              aria-valuemax="100"
              aria-label="Module progress"
            ></div>
          </div>
        </div>

        <form action="{{ route('quiz.start', $m) }}" method="POST" class="mt-auto">
          @csrf
          <button class="btn btn-primary w-full">{{ $pct > 0 ? 'Start / Resume' : 'Begin module' }}</button>
        </form>
      </article>
    @empty
      <div class="card-muted col-span-full flex items-center justify-center p-10 text-sm text-muted-foreground">
        No modules yet.
      </div>
    @endforelse
  </div>
</section>
@endsection
