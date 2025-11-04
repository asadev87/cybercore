{{-- resources/views/learn/index.blade.php --}}
@extends('layouts.app')

@section('content')
@php
  $moduleCount     = $filteredStats['count'] ?? $modules->count();
  $moduleLabel     = $moduleCount === 1 ? 'module' : 'modules';
  $completed       = $filteredStats['completed'] ?? $modules->filter(fn ($mod) => (int) ($progress[$mod->id] ?? 0) >= 100)->count();
  $inProgress      = max($moduleCount - $completed, 0);
  $totalCount      = $totalStats['count'] ?? $moduleCount;
  $totalLabel      = $totalCount === 1 ? 'module' : 'modules';
  $activeSearch    = $activeFilters['search'] ?? '';
  $activeTopic     = $activeFilters['topic'] ?? '';
  $activeRole      = $activeFilters['role'] ?? '';
  $activeDifficulty= $activeFilters['difficulty'] ?? '';
  $anyFilterActive = $activeSearch !== '' || $activeTopic || $activeRole || $activeDifficulty;
  $difficultyOptions = ['Beginner', 'Intermediate', 'Advanced', 'Unrated'];
  $latestAttempts = collect($latestAttempts ?? []);
  $bestScores = collect($bestScores ?? []);
  $attemptCost = (int) config('tokens.module_attempt_cost', 0);
@endphp

<section class="space-y-10">
  <form
    method="GET"
    action="{{ route('learn.index') }}"
    class="card-surface space-y-6 p-8 sm:p-12"
    x-data="learnFilters({ initialSearch: @json($activeSearch) })"
    x-ref="filterForm"
  >
    <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:gap-6">
      <label class="flex-1 text-sm font-medium text-muted-foreground">
        <span class="mb-2 block text-sm font-semibold uppercase tracking-[0.24em]">Search catalog</span>
        <div class="relative">
          <input
            type="search"
            name="search"
            value="{{ $activeSearch }}"
            x-model="search"
            x-ref="searchField"
            x-on:input="queueSearch($event.target.value)"
            class="w-full rounded-xl border border-border/60 bg-background px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
            placeholder="Search by title, summary, or tags…"
          />
          <button
            type="button"
            class="absolute inset-y-0 right-3 hidden items-center text-muted-foreground/70 hover:text-foreground"
            x-show="search.length"
            x-cloak
            x-on:click="clearSearch()"
            aria-label="Clear search"
          >
            &times;
          </button>
        </div>
      </label>

      <label class="w-full text-sm font-medium text-muted-foreground lg:w-48">
        <span class="mb-2 block text-sm font-semibold uppercase tracking-[0.24em]">Topic</span>
        <select
          name="topic"
          x-on:change="$refs.filterForm?.requestSubmit()"
          class="w-full rounded-xl border border-border/60 bg-background px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
        >
          <option value="">All topics</option>
          @foreach ($topicsMap as $topicKey => $topicLabel)
            <option value="{{ $topicKey }}" @selected($activeTopic === $topicKey)>{{ $topicLabel }}</option>
          @endforeach
        </select>
      </label>

      <label class="w-full text-sm font-medium text-muted-foreground lg:w-48">
        <span class="mb-2 block text-sm font-semibold uppercase tracking-[0.24em]">Role focus</span>
        <select
          name="role"
          x-on:change="$refs.filterForm?.requestSubmit()"
          class="w-full rounded-xl border border-border/60 bg-background px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
        >
          <option value="">All roles</option>
          @foreach ($rolesMap as $roleKey => $roleLabel)
            <option value="{{ $roleKey }}" @selected($activeRole === $roleKey)>{{ $roleLabel }}</option>
          @endforeach
        </select>
      </label>

      <label class="w-full text-sm font-medium text-muted-foreground lg:w-48">
        <span class="mb-2 block text-sm font-semibold uppercase tracking-[0.24em]">Difficulty</span>
        <select
          name="difficulty"
          x-on:change="$refs.filterForm?.requestSubmit()"
          class="w-full rounded-xl border border-border/60 bg-background px-4 py-3 text-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20"
        >
          <option value="">All levels</option>
          @foreach ($difficultyOptions as $option)
            <option value="{{ $option }}" @selected($activeDifficulty === $option)>{{ $option }}</option>
          @endforeach
        </select>
      </label>
    </div>

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <p class="text-sm text-muted-foreground">
        Showing <span class="font-semibold text-foreground">{{ $moduleCount }}</span> of
        <span class="font-semibold text-foreground">{{ $totalCount }}</span> {{ $totalLabel }}.
      </p>
        <div class="flex flex-wrap items-center gap-3 text-sm sm:text-base">
        <button type="submit" class="btn btn-primary text-sm">Apply filters</button>
        @if ($anyFilterActive)
          <a href="{{ route('learn.index') }}" class="btn btn-muted text-sm">Clear filters</a>
        @endif
      </div>
    </div>
  </form>

  <header class="card-surface p-8 sm:p-12">
    <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
      <div class="space-y-3">
        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-muted-foreground">Module catalog</p>
        <h1 class="text-3xl font-semibold tracking-tight">Continue your cybersecurity journey</h1>
        <p class="max-w-2xl text-base text-muted-foreground">
          Dip back into an in‑progress path or start something fresh. Everything you complete here feeds into your overall readiness score.
        </p>
      </div>
      <div class="grid gap-3 sm:grid-cols-3">
        <div class="rounded-2xl border border-border/60 bg-secondary/40 px-4 py-3 text-center">
          <p class="text-sm font-semibold uppercase tracking-[0.28em] text-muted-foreground">Total</p>
          <p class="text-xl font-semibold">{{ $moduleCount }}</p>
          <p class="text-sm text-muted-foreground">{{ $moduleLabel }}</p>
        </div>
        <div class="rounded-2xl border border-border/60 bg-emerald-500/10 px-4 py-3 text-center text-emerald-700 dark:text-emerald-200">
          <p class="text-sm font-semibold uppercase tracking-[0.28em]">Completed</p>
          <p class="text-xl font-semibold">{{ $completed }}</p>
          <p class="text-sm text-emerald-700/80 dark:text-emerald-200/80">Great work</p>
        </div>
        <div class="rounded-2xl border border-border/60 bg-primary/10 px-4 py-3 text-center text-primary">
          <p class="text-sm font-semibold uppercase tracking-[0.28em]">In progress</p>
          <p class="text-xl font-semibold">{{ $inProgress }}</p>
          <p class="text-sm text-primary/80">Pick up where you left off</p>
        </div>
      </div>
    </div>
  </header>

  <div class="space-y-10">
    <div class="grid gap-8 lg:grid-cols-2">
    @forelse ($modules as $m)
      @php
        $pct          = (int) ($progress[$m->id] ?? 0);
        $statusLabel  = $pct >= 100 ? 'Complete' : ($pct > 0 ? 'In progress' : 'Not started');
        $statusTone   = $pct >= 100 ? 'text-emerald-600 dark:text-emerald-300 bg-emerald-500/10 border-emerald-500/30' : 'text-primary bg-primary/10 border-primary/20';
        $summary      = (string) ($m->catalog_copy ?? $m->description);
        $summary      = mb_strlen($summary) > 180 ? rtrim(mb_substr($summary, 0, 177)) . '…' : $summary;
        $difficulty   = $difficultyLabels[$m->id] ?? 'Unrated';
        $note         = $m->note ?: $m->default_note;
        $topicBadges  = collect($m->topics ?? [])->map(fn ($key) => $topicsMap[$key] ?? \Illuminate\Support\Str::headline($key));
        $roleBadges   = collect($m->roles ?? [])->map(fn ($key) => $rolesMap[$key] ?? \Illuminate\Support\Str::headline($key));
        $recentAttempt = $latestAttempts->get($m->id);
        $recentPassed = $recentAttempt && (int) ($recentAttempt->score ?? 0) >= (int) ($m->pass_score ?? 70);
        $hasCompletedAttempt = (bool) $recentAttempt;
        $isComplete = $recentPassed;
      @endphp

      <article class="card-surface p-7 sm:p-10">
        <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
          <div class="space-y-5 md:flex-1">
            <div class="flex flex-wrap items-start justify-between gap-4">
              <div class="space-y-2">
                <div class="flex items-start justify-between gap-2">
                  <h2 class="text-2xl font-semibold tracking-tight">{{ $m->title }}</h2>
                  @php
                    $scoreColor = 'bg-muted text-muted-foreground';
                    $scoreValue = null;
                    if (($bestScores->get($m->id)) !== null) {
                        $scoreValue = (int) $bestScores->get($m->id);
                    } elseif (($recentAttempt?->score ?? null) !== null) {
                        $scoreValue = (int) $recentAttempt->score;
                    }
                    if ($scoreValue !== null) {
                    $passScore = $m->pass_score ?? 70;
                    if ($scoreValue >= $passScore) {
                        $scoreColor = 'bg-emerald-500/15 text-emerald-700 dark:text-emerald-200';
                    } else {
                        $scoreColor = 'bg-rose-500/15 text-rose-700 dark:text-rose-200';
                    }
                    }
                  @endphp
                  <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs font-semibold uppercase tracking-wide ml-auto {{ $scoreColor }}">
                    <span>Score</span>
                    <span>{{ $scoreValue !== null ? $scoreValue.'%' : '—' }}</span>
                  </span>
                </div>
                <p class="mt-3 text-base text-muted-foreground">{{ $summary }}</p>
                @if($topicBadges->isNotEmpty() || $roleBadges->isNotEmpty())
                  <div class="mt-3 flex flex-wrap gap-2 text-sm font-medium">
                    @foreach($topicBadges as $label)
                      <span class="inline-flex items-center gap-1 rounded-full bg-primary/10 px-3 py-1 text-primary">
                    <span class="text-[10px] uppercase tracking-[0.24em] text-primary/70">Topic</span> {{ $label }}
                  </span>
                @endforeach
                @foreach($roleBadges as $label)
                  <span class="inline-flex items-center gap-1 rounded-full bg-secondary/70 px-3 py-1 text-foreground/70">
                    <span class="text-[10px] uppercase tracking-[0.24em] text-foreground/40">Role</span> {{ $label }}
                  </span>
                @endforeach
                  </div>
                @endif
              </div>
              <span class="inline-flex items-start justify-between gap-2 rounded-full border px-3 py-1 text-sm font-semibold uppercase tracking-wide {{ $statusTone }}">
                {{ $statusLabel }}
              </span>
            </div>

            <dl class="flex flex-col gap-3 lg:flex-row lg:items-stretch lg:gap-4">
              <div class="flex-1 rounded-2xl border border-border/60 bg-secondary/50 px-4 py-3">
                <dt class="text-sm font-semibold uppercase tracking-[0.24em] text-muted-foreground">Difficulty</dt>
                <dd class="mt-1 text-sm font-medium text-foreground">{{ $difficulty }}</dd>
              </div>
              <div class="flex-1 rounded-2xl border border-border/60 bg-secondary/50 px-4 py-3">
                <dt class="text-sm font-semibold uppercase tracking-[0.24em] text-muted-foreground">Pass score</dt>
                <dd class="mt-1 text-sm font-medium text-foreground">{{ $m->pass_score }}%</dd>
              </div>
              <div class="flex-1 rounded-2xl border border-border/60 bg-secondary/50 px-4 py-3">
                <dt class="text-sm font-semibold uppercase tracking-[0.24em] text-muted-foreground">Your progress</dt>
                <dd class="mt-1 text-sm font-medium text-foreground">{{ $pct }}%</dd>
              </div>
            </dl>

            @if($note)
              <div class="rounded-2xl border border-amber-300/40 bg-amber-100/40 px-4 py-3 text-sm text-amber-800 dark:border-amber-400/30 dark:bg-amber-500/10 dark:text-amber-100">
                <span class="font-semibold uppercase tracking-wide text-amber-700 dark:text-amber-200">Prep note</span>
                <span class="block pt-1 text-amber-700/80 dark:text-amber-200/80">{{ $note }}</span>
              </div>
            @endif

            <div class="space-y-2">
              <div class="flex items-center justify-between text-sm font-medium text-muted-foreground">
                <span>Overall completion</span>
                <span>{{ $pct }}%</span>
              </div>
              <div class="relative h-2 w-full overflow-hidden rounded-full bg-secondary">
                <div
                  class="h-2 rounded-full transition-all duration-500 {{ $pct >= 100 ? 'bg-emerald-500 animate-glow-complete' : 'bg-primary' }}"
                  style="width: {{ $pct }}%"
                  role="progressbar"
                  aria-valuenow="{{ $pct }}"
                  aria-valuemin="0"
                  aria-valuemax="100"
                  aria-label="Module progress"
                ></div>
                @if($pct >= 100)
                  <span class="pointer-events-none absolute inset-0 rounded-full bg-emerald-400/40 blur-md animate-glow-complete"></span>
                @endif
              </div>
            </div>

            <div class="flex flex-wrap gap-3 pt-3">
              @if($recentAttempt)
                @if($recentPassed)
                  <a href="{{ route('quiz.result', $recentAttempt) }}" class="btn btn-primary">Review results</a>
                  <form action="{{ route('quiz.start', $m) }}" method="POST" class="inline-flex">
                    @csrf
                    <button class="btn btn-outline">
                      <span>Retake module</span>
                      @if($attemptCost > 0)
                        <span class="ml-2 text-xs font-semibold uppercase tracking-wide text-muted-foreground/80">{{ $attemptCost }} tokens</span>
                      @endif
                    </button>
                  </form>
                @else
                  <a href="{{ route('quiz.result', $recentAttempt) }}" class="btn btn-muted">Review last attempt</a>
                  <form action="{{ route('quiz.start', $m) }}" method="POST" class="inline-flex">
                    @csrf
                    <button class="btn btn-primary">
                      <span>Retake module</span>
                      @if($attemptCost > 0)
                        <span class="ml-2 text-xs font-semibold uppercase tracking-wide text-white/80">{{ $attemptCost }} tokens</span>
                      @endif
                    </button>
                  </form>
                @endif
              @else
                <form action="{{ route('quiz.start', $m) }}" method="POST" class="inline-flex">
                  @csrf
                  @php
                    $startLabel = $pct > 0 ? 'Resume module' : 'Begin module';
                    $showStartCost = $startLabel === 'Begin module' && $attemptCost > 0;
                  @endphp
                  <button class="btn btn-primary">
                    <span>{{ $startLabel }}</span>
                    @if($showStartCost)
                      <span class="ml-2 text-xs font-semibold uppercase tracking-wide text-white/80">{{ $attemptCost }} tokens</span>
                    @endif
                  </button>
                </form>
              @endif
              @can('update', $m)
                <a href="{{ route('admin.modules.builder', $m) }}" class="btn btn-muted text-sm">Manage content</a>
              @endcan
            </div>
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
