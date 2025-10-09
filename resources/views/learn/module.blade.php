{{-- resources/views/learn/module.blade.php --}}
@extends('layouts.app')
@section('content')
<section class="space-y-8">
  @php
    $total = max(1, $sections->count());
    $done  = $sections->filter(fn($s) => ($prog[$s->id] ?? 0) >= 100)->count();
    $pct   = intval(($done / $total) * 100);
  @endphp

  <div class="card-surface space-y-4 p-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
      <div class="space-y-2">
        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-muted-foreground">Learning path</p>
        <h1 class="text-3xl font-semibold tracking-tight">{{ $module->title }}</h1>
        @php
          $defaultNotes = (array) (config('module_notes.defaults') ?? []);
          $descriptionCopy = (array) (config('module_notes.descriptions') ?? []);
          $description = $descriptionCopy[$module->slug] ?? $module->description;
          $note = $module->note ?: ($defaultNotes[$module->slug] ?? null);
        @endphp
        <p class="max-w-2xl text-sm text-muted-foreground">{{ $description }}</p>
        @if($note)
          <div class="rounded-xl border border-amber-300/40 bg-amber-100/30 px-4 py-3 text-xs text-amber-800 dark:border-amber-400/30 dark:bg-amber-400/10 dark:text-amber-100">
            <span class="font-semibold uppercase tracking-wide text-amber-700 dark:text-amber-200">Prep note:</span>
            <span class="ml-1">{{ $note }}</span>
          </div>
        @endif
      </div>
      <div class="flex flex-col items-end gap-3">
        <div class="rounded-2xl border border-border/60 bg-secondary/50 px-4 py-3 text-sm font-medium text-secondary-foreground">
          {{ $pct }}% overall progress
        </div>
        @can('update', $module)
          <a href="{{ route('admin.modules.builder', $module) }}" class="btn btn-outline text-xs">Manage module</a>
        @endcan
      </div>
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
          aria-label="Overall module progress"
        ></div>
      </div>
    </div>
  </div>

  <div class="space-y-4">
    @foreach($sections as $i => $s)
      @php
        $sectionProgress = intval($prog[$s->id] ?? 0);
        $locked = $i > 0 && intval($prog[$sections[$i-1]->id] ?? 0) < 100;
      @endphp

      <article class="card-surface flex flex-col gap-4 p-6 @if($locked) opacity-60 @endif">
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div class="space-y-2">
            <div class="flex items-center gap-2">
              <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-secondary text-sm font-semibold text-secondary-foreground">{{ $i + 1 }}</span>
              <h2 class="text-lg font-semibold">{{ $s->title }}</h2>
            </div>
            <p class="max-w-2xl text-sm text-muted-foreground">{{ $s->description }}</p>
          </div>

          <div class="space-y-2 text-right">
            <span class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">{{ $sectionProgress >= 100 ? 'Completed' : ($sectionProgress > 0 ? 'In Progress' : 'Locked') }}</span>
            <div class="h-1.5 w-48 rounded-full bg-secondary">
              <div class="h-1.5 rounded-full {{ $sectionProgress >= 100 ? 'bg-accent' : 'bg-primary' }}" style="width: {{ $sectionProgress }}%"></div>
            </div>
            <span class="block text-xs font-medium text-muted-foreground">{{ $sectionProgress }}%</span>
          </div>
        </div>

        <div>
          @if($locked)
            <button class="btn btn-muted" disabled>Complete Section {{ $i }} first</button>
          @else
            <a class="btn btn-primary" href="{{ route('learn.start', $module) }}?section={{ $s->id }}">
              {{ $sectionProgress > 0 ? 'Continue section' : 'Start section' }}
            </a>
          @endif
        </div>
      </article>
    @endforeach
  </div>
</section>
@endsection
