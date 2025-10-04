{{-- resources/views/badges/index.blade.php --}}

@extends('layouts.app')

@section('content')
<section class="space-y-8">
  <header class="flex flex-wrap items-end justify-between gap-4">
    <div>
      <p class="text-xs font-semibold uppercase tracking-[0.24em] text-muted-foreground">Recognition</p>
      <h1 class="text-3xl font-semibold tracking-tight">Your badges & completed modules</h1>
      <p class="text-sm text-muted-foreground">Celebrate the milestones you have earned so far.</p>
    </div>
    <a class="btn btn-outline" href="{{ route('learn.index') }}">Back to Learn</a>
  </header>

  @if($hasBadgeTables)
    <article class="card-surface space-y-6 p-6">
      <header class="flex items-center gap-3">
        <h2 class="text-lg font-semibold">Earned badges</h2>
        <span class="rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-primary">{{ $earnedBadges->count() }}</span>
      </header>

      @if($earnedBadges->isEmpty())
        <p class="text-sm text-muted-foreground">No badges yet. Complete a module to earn your first badge.</p>
      @else
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
          @foreach($earnedBadges as $b)
            <div class="card-muted flex h-full flex-col justify-between gap-3 p-4">
              <div class="space-y-2">
                <h3 class="text-base font-semibold text-foreground">{{ $b->name }}</h3>
                <p class="text-sm text-muted-foreground">{{ $b->description }}</p>
              </div>
              <p class="text-xs text-muted-foreground">Awarded {{ optional($b->pivot->awarded_at)->diffForHumans() }}</p>
            </div>
          @endforeach
        </div>
      @endif
    </article>
  @endif

  <article class="card-surface space-y-6 p-6">
    <header class="flex items-center gap-3">
      <h2 class="text-lg font-semibold">Completed modules</h2>
      <span class="rounded-full bg-success/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-success">{{ $completed->count() }}</span>
    </header>

    @if($completed->isEmpty())
      <p class="text-sm text-muted-foreground">No completed modules yet. Start from the Learn page to unlock your first badge.</p>
    @else
      <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach($completed as $p)
          <div class="card-muted flex h-full flex-col gap-3 p-4">
            <div class="flex items-center gap-3">
              <span class="text-xl" aria-hidden="true">🏅</span>
              <h3 class="text-base font-semibold text-foreground">{{ $p->module->title }}</h3>
            </div>
            <p class="text-sm text-muted-foreground">{{ \Illuminate\Support\Str::limit($p->module->description, 120) }}</p>
            <div class="mt-auto flex items-center justify-between">
              <span class="rounded-full bg-success/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-success">Completed</span>
              <a class="btn btn-outline" href="{{ route('learn.start', $p->module) }}">Review</a>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </article>
</section>
@endsection
