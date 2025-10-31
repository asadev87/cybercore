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
                <div class="flex items-start gap-3">
                  @if(!empty($b->icon))
                    <span class="text-3xl leading-none" aria-hidden="true">{{ $b->icon }}</span>
                  @endif
                  <div>
                    <h3 class="text-base font-semibold text-foreground">{{ $b->name }}</h3>
                    <p class="text-sm text-muted-foreground">{{ $b->description }}</p>
                  </div>
                </div>
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

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
      @forelse($completed as $p)
        <div class="card-muted flex h-full flex-col gap-3 p-4">
          <div class="flex items-center gap-3">
            <span class="text-xl" aria-hidden="true">🏅</span>
            <h3 class="text-base font-semibold text-foreground">{{ $p->module->title }}</h3>
          </div>
          <p class="text-sm text-muted-foreground">{{ \Illuminate\Support\Str::limit($p->module->description, 120) }}</p>
          <div class="mt-auto flex items-center justify-between">
            <span class="rounded-full bg-success/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-success">Completed</span>
            @php
              $certificate = \App\Models\Certificate::where('user_id', auth()->id())
                ->where('module_id', $p->module_id)
                ->latest('issued_at')
                ->first();
            @endphp
            @if($certificate)
              <a class="btn btn-outline" href="{{ route('certificates.embed', $certificate) }}">Certificate</a>
            @else
              <a class="btn btn-outline" href="{{ route('learn.start', $p->module) }}">Review</a>
            @endif
          </div>
        </div>
      @empty
        @foreach(range(1, 3) as $i)
          <div class="card-muted animate-pulse flex h-full flex-col gap-4 p-4">
            <div class="flex items-center gap-3">
              <span class="h-10 w-10 rounded-full bg-muted" aria-hidden="true"></span>
              <div class="flex-1 space-y-2">
                <div class="h-4 w-3/4 rounded bg-muted"></div>
                <div class="h-3 w-1/2 rounded bg-muted"></div>
              </div>
            </div>
            <div class="space-y-2">
              <div class="h-3 w-full rounded bg-muted"></div>
              <div class="h-3 w-4/5 rounded bg-muted"></div>
              <div class="h-3 w-2/3 rounded bg-muted"></div>
            </div>
            <div class="mt-auto flex items-center justify-between">
              <span class="h-6 w-24 rounded-full bg-muted"></span>
              <span class="h-9 w-24 rounded-lg bg-muted"></span>
            </div>
          </div>
        @endforeach
      @endforelse
    </div>

    @if($completed->isEmpty())
      <p class="text-sm text-muted-foreground">Complete a module to replace these placeholders with your real achievements.</p>
    @endif
  </article>
</section>
@endsection
