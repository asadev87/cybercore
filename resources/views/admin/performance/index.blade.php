{{-- resources/views/admin/performance/index.blade.php --}}
@extends('admin.layout')

@section('title', 'Performance monitor')

@section('content')
@php
    $loginMax = max(1, (int) collect($loginTrend)->max('total'));
    $totalScoreSamples = max(1, array_sum(array_column($scoreDistribution, 'count')));
@endphp

<section class="space-y-10">
  <header class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
    <div class="space-y-2">
      <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Performance monitor</p>
      <h1 class="text-3xl font-semibold tracking-tight">Learner scores &amp; engagement</h1>
      <p class="max-w-3xl text-sm text-muted-foreground">
        Track how learners are progressing across quizzes and when they are signing in. Use these insights to celebrate wins, spot disengagement early, and focus support where it matters.
      </p>
    </div>
    <div class="flex flex-wrap gap-2">
      <a href="{{ route('admin.users.index') }}" class="btn btn-outline text-sm">Manage users</a>
      <a href="{{ route('admin.reports.index') }}" class="btn btn-outline text-sm">Export reports</a>
    </div>
  </header>

  <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <article class="bento-badge">
      <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Total attempts</p>
      <p class="mt-2 text-3xl font-semibold text-foreground">{{ number_format($overview['total_attempts']) }}</p>
      <p class="text-xs text-muted-foreground">({{ number_format($overview['attempts_last_30']) }} in last 30 days)</p>
    </article>
    <article class="bento-badge">
      <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Average score</p>
      <p class="mt-2 text-3xl font-semibold text-foreground">{{ number_format($overview['avg_score'], 1) }}%</p>
      <p class="text-xs text-muted-foreground">Across all completed attempts</p>
    </article>
    <article class="bento-badge">
      <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Pass rate</p>
      <p class="mt-2 text-3xl font-semibold text-foreground">{{ number_format($overview['pass_rate'], 1) }}%</p>
      <p class="text-xs text-muted-foreground">Score ≥ module pass threshold</p>
    </article>
    <article class="bento-badge">
      <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Active learners</p>
      <p class="mt-2 text-3xl font-semibold text-foreground">{{ number_format($overview['active_learners']) }}</p>
      <p class="text-xs text-muted-foreground">{{ number_format($overview['total_learners']) }} total learners</p>
    </article>
  </div>

  <div class="grid gap-6 lg:grid-cols-[minmax(0,0.7fr),minmax(0,0.3fr)]">
    <article class="bento-card h-full space-y-6">
      <div class="flex items-center justify-between border-b border-border/60 pb-4">
        <div>
          <p class="bento-label">Login cadence</p>
          <h2 class="text-lg font-semibold">Past two weeks</h2>
        </div>
        <span class="text-xs font-medium uppercase tracking-[0.2em] text-muted-foreground">Daily sign-ins</span>
      </div>
      <div class="space-y-4">
        <div class="grid h-32 grid-cols-14 items-end gap-2">
          @foreach ($loginTrend as $day)
            @php
              $height = max(6, ($day['total'] / $loginMax) * 100);
              $dateLabel = \Illuminate\Support\Carbon::parse($day['date'])->format('d M');
            @endphp
            <div class="group relative flex flex-col items-center justify-end">
              <div class="w-full rounded-t-xl bg-gradient-to-t from-primary/30 via-primary/60 to-primary shadow-[0_10px_20px_-15px_rgba(37,99,235,0.6)] transition duration-300 group-hover:from-primary/40 group-hover:via-primary/70 group-hover:to-primary/90" style="height: {{ $height }}%;"></div>
              <span class="mt-2 text-[11px] font-semibold uppercase tracking-[0.2em] text-muted-foreground group-hover:text-foreground">{{ \Illuminate\Support\Carbon::parse($day['date'])->format('D') }}</span>
              <div class="pointer-events-none absolute bottom-full mb-3 hidden rounded-lg border border-border/70 bg-background px-3 py-1 text-xs text-muted-foreground shadow group-hover:flex dark:border-white/10 dark:bg-white/10 dark:text-white/80">
                <span>{{ $dateLabel }} · {{ $day['total'] }} logins</span>
              </div>
            </div>
          @endforeach
        </div>
        <p class="text-xs text-muted-foreground">
          Peaks indicate workshop or cohort activity. Dip below expected? Consider nudging learners or refreshing content.
        </p>
      </div>
    </article>

    <article class="bento-card h-full space-y-6">
      <div class="border-b border-border/60 pb-4">
        <p class="bento-label">Score distribution</p>
        <h2 class="text-lg font-semibold">All attempts</h2>
      </div>
      <div class="space-y-4">
        @foreach ($scoreDistribution as $band)
          @php
            $percent = $totalScoreSamples > 0 ? round(($band['count'] / $totalScoreSamples) * 100) : 0;
          @endphp
          <div>
            <div class="flex items-center justify-between text-sm font-semibold text-foreground">
              <span>{{ $band['label'] }}</span>
              <span>{{ number_format($band['count']) }} <span class="text-xs text-muted-foreground">({{ $percent }}%)</span></span>
            </div>
            <div class="mt-2 h-2.5 rounded-full bg-secondary">
              <div class="h-2.5 rounded-full bg-gradient-to-r from-emerald-400 via-sky-400 to-blue-500" style="width: {{ min(100, $percent) }}%;"></div>
            </div>
          </div>
        @endforeach
      </div>
    </article>
  </div>

  <div class="grid gap-6 xl:grid-cols-2">
    <article class="bento-card space-y-4">
      <div class="flex items-center justify-between border-b border-border/60 pb-3">
        <div>
          <p class="bento-label">Module spotlight</p>
          <h2 class="text-lg font-semibold">Top 6 by activity</h2>
        </div>
        <span class="text-xs font-medium uppercase tracking-[0.2em] text-muted-foreground">Attempts · Avg score</span>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-border/60 text-left text-sm">
          <thead class="bg-secondary/60 text-secondary-foreground">
            <tr>
              <th class="px-5 py-3 font-semibold">Module</th>
              <th class="px-5 py-3 font-semibold text-center">Attempts</th>
              <th class="px-5 py-3 font-semibold text-center">Passes</th>
              <th class="px-5 py-3 font-semibold text-center">Avg score</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-border/60">
            @forelse ($topModules as $module)
              <tr class="transition hover:bg-secondary/40">
                <td class="px-5 py-3 text-sm font-medium text-foreground">{{ $module->title }}</td>
                <td class="px-5 py-3 text-center text-sm text-muted-foreground">{{ number_format($module->attempts) }}</td>
                <td class="px-5 py-3 text-center text-sm text-muted-foreground">{{ number_format($module->passes) }}</td>
                <td class="px-5 py-3 text-center text-sm text-muted-foreground">{{ number_format($module->average_score, 1) }}%</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-5 py-6 text-center text-sm text-muted-foreground">No module attempts recorded yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </article>

    <article class="bento-card space-y-4">
      <div class="flex items-center justify-between border-b border-border/60 pb-3">
        <div>
          <p class="bento-label">Recent logins</p>
          <h2 class="text-lg font-semibold">Latest access</h2>
        </div>
        <span class="text-xs font-medium uppercase tracking-[0.2em] text-muted-foreground">Last 12 entries</span>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-border/60 text-left text-sm">
          <thead class="bg-secondary/60 text-secondary-foreground">
            <tr>
              <th class="px-5 py-3 font-semibold">User</th>
              <th class="px-5 py-3 font-semibold">Channel</th>
              <th class="px-5 py-3 font-semibold">When</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-border/60">
            @forelse ($recentLogins as $login)
              <tr class="transition hover:bg-secondary/40">
                <td class="px-5 py-3 text-sm font-medium text-foreground">
                  {{ optional($login->user)->name ?? optional($login->user)->email ?? 'Unknown user' }}
                </td>
                <td class="px-5 py-3 text-sm text-muted-foreground">{{ ucfirst($login->context ?? 'web') }} @if($login->remember) · <span class="text-xs uppercase tracking-[0.2em] text-emerald-500">Remembered</span>@endif</td>
                <td class="px-5 py-3 text-sm text-muted-foreground">{{ optional($login->logged_in_at)->diffForHumans() }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="3" class="px-5 py-6 text-center text-sm text-muted-foreground">No login records yet.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </article>
  </div>

  <article class="bento-card space-y-4">
    <div class="flex items-center justify-between border-b border-border/60 pb-3">
      <div>
        <p class="bento-label">Latest quiz attempts</p>
        <h2 class="text-lg font-semibold">Score feed</h2>
      </div>
      <span class="text-xs font-medium uppercase tracking-[0.2em] text-muted-foreground">Last 12 completions</span>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-border/60 text-left text-sm">
        <thead class="bg-secondary/60 text-secondary-foreground">
          <tr>
            <th class="px-5 py-3 font-semibold">User</th>
            <th class="px-5 py-3 font-semibold">Module</th>
            <th class="px-5 py-3 font-semibold text-center">Score</th>
            <th class="px-5 py-3 font-semibold">Completed</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border/60">
          @forelse ($recentAttempts as $attempt)
            <tr class="transition hover:bg-secondary/40">
              <td class="px-5 py-3 text-sm font-medium text-foreground">
                {{ optional($attempt->user)->name ?? optional($attempt->user)->email ?? 'Unknown user' }}
              </td>
              <td class="px-5 py-3 text-sm text-muted-foreground">{{ optional($attempt->module)->title ?? 'Unknown module' }}</td>
              @php
                $passScore = optional($attempt->module)->pass_score ?? 70;
              @endphp
              <td class="px-5 py-3 text-center text-sm font-semibold {{ $attempt->score >= $passScore ? 'text-emerald-500' : 'text-destructive' }}">
                {{ $attempt->score }}%
              </td>
              <td class="px-5 py-3 text-sm text-muted-foreground">{{ optional($attempt->completed_at)->diffForHumans() }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-5 py-6 text-center text-sm text-muted-foreground">No attempts recorded yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </article>
</section>
@endsection
