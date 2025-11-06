{{-- resources/views/admin/performance/lecturer.blade.php --}}
@extends('admin.layout')

@section('title', 'My module performance')

@section('content')
@php
    $totalCompletions = (int) ($totals['completions'] ?? 0);
    $avgScoreDisplay = $totals['average_score'] !== null ? number_format($totals['average_score'], 1).'%' : '—';
    $passRateDisplay = $totals['pass_rate'] !== null ? number_format($totals['pass_rate'], 1).'%' : '—';
@endphp

<section class="space-y-10">
  <header class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
    <div class="space-y-2">
      <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Lecturer dashboard</p>
      <h1 class="text-3xl font-semibold tracking-tight">Learner outcomes across my modules</h1>
      <p class="max-w-3xl text-sm text-muted-foreground">
        Monitor how students are performing once they complete your modules. Track completion volume, average scores, and who has met the pass threshold so you can plan follow-up sessions.
      </p>
    </div>
    <a href="{{ route('admin.modules.index') }}" class="btn btn-outline text-sm">Manage modules</a>
  </header>

  @if ($modules->isEmpty())
    <article class="bento-card flex flex-col items-center justify-center gap-4 px-8 py-16 text-center">
      <div class="rounded-full bg-primary/10 px-6 py-5 text-primary">
        <span class="text-3xl font-semibold">📊</span>
      </div>
      <div class="space-y-2">
        <h2 class="text-xl font-semibold tracking-tight">No modules to report on yet</h2>
        <p class="text-sm text-muted-foreground">
          Create a module and publish it to start tracking learner completions and scores here.
        </p>
      </div>
      <a href="{{ route('admin.modules.create') }}" class="btn btn-primary">Create your first module</a>
    </article>
  @else
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
      <article class="bento-badge">
        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Total completions</p>
        <p class="mt-2 text-3xl font-semibold text-foreground">{{ number_format($totalCompletions) }}</p>
        <p class="text-xs text-muted-foreground">Unique learners who have completed your modules</p>
      </article>
      <article class="bento-badge">
        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Average score</p>
        <p class="mt-2 text-3xl font-semibold text-foreground">{{ $avgScoreDisplay }}</p>
        <p class="text-xs text-muted-foreground">Across best completed attempts</p>
      </article>
      <article class="bento-badge">
        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Pass rate</p>
        <p class="mt-2 text-3xl font-semibold text-foreground">{{ $passRateDisplay }}</p>
        <p class="text-xs text-muted-foreground">Measured against each module's threshold</p>
      </article>
      <article class="bento-badge">
        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Active modules</p>
        <p class="mt-2 text-3xl font-semibold text-foreground">{{ number_format($modules->count()) }}</p>
        <p class="text-xs text-muted-foreground">Modules assigned to you</p>
      </article>
    </div>

    <div class="space-y-8">
      @foreach ($modules as $module)
        @php
          /** @var \Illuminate\Support\Collection|null $rows */
          $rows = $attemptsByModule->get($module->id, collect());
          $summary = $summaryByModule->get($module->id, null);
          $completions = $summary['completions'] ?? 0;
          $averageScore = $summary['average_score'] ?? null;
          $passRate = $summary['pass_rate'] ?? null;
          $threshold = (int) ($module->pass_score ?? 70);
        @endphp

        <article class="bento-card space-y-6">
          <header class="flex flex-col gap-3 border-b border-border/60 pb-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
              <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Module</p>
              <h2 class="text-2xl font-semibold tracking-tight">{{ $module->title }}</h2>
              <p class="text-xs text-muted-foreground">Pass threshold: {{ $threshold }}%</p>
            </div>
            <div class="flex flex-wrap gap-3 text-sm">
              <div class="rounded-xl border border-border/70 px-4 py-2">
                <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-muted-foreground">Completions</p>
                <p class="text-lg font-semibold text-foreground">{{ number_format($completions) }}</p>
              </div>
              <div class="rounded-xl border border-border/70 px-4 py-2">
                <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-muted-foreground">Average score</p>
                <p class="text-lg font-semibold text-foreground">{{ $averageScore !== null ? number_format($averageScore, 1).'%' : '—' }}</p>
              </div>
              <div class="rounded-xl border border-border/70 px-4 py-2">
                <p class="text-[11px] font-semibold uppercase tracking-[0.24em] text-muted-foreground">Pass rate</p>
                <p class="text-lg font-semibold text-foreground">{{ $passRate !== null ? number_format($passRate, 1).'%' : '—' }}</p>
              </div>
              <a href="{{ route('admin.modules.edit', $module) }}" class="btn btn-outline btn-sm whitespace-nowrap">Edit module</a>
            </div>
          </header>

          @if ($rows->isEmpty())
            <p class="rounded-xl border border-dashed border-border/60 px-6 py-8 text-center text-sm text-muted-foreground">
              No learners have completed this module yet. Once attempts are completed, their best scores will appear here.
            </p>
          @else
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-border/70 text-sm">
                <thead>
                  <tr class="text-left text-xs font-semibold uppercase tracking-[0.24em] text-muted-foreground">
                    <th class="px-4 py-3">Learner</th>
                    <th class="px-4 py-3">Best score</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Completed</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-border/60">
                  @foreach ($rows as $attempt)
                    @php
                      $score = (int) ($attempt->score ?? 0);
                      $passed = $score >= $threshold;
                      $user = $attempt->user;
                    @endphp
                    <tr class="hover:bg-muted/30">
                      <td class="px-4 py-3">
                        <div class="flex flex-col">
                          <span class="font-medium text-foreground">{{ $user?->name ?? 'Unknown learner' }}</span>
                          @if ($user?->email)
                            <span class="text-xs text-muted-foreground">{{ $user->email }}</span>
                          @endif
                        </div>
                      </td>
                      <td class="px-4 py-3 font-semibold text-foreground">{{ $score }}%</td>
                      <td class="px-4 py-3">
                        <span @class([
                            'inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide',
                            $passed ? 'bg-emerald-500/10 text-emerald-700 dark:text-emerald-200' : 'bg-rose-500/10 text-rose-600 dark:text-rose-200',
                        ])>
                          <span class="h-2 w-2 rounded-full {{ $passed ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                          {{ $passed ? 'Passed' : 'Below pass' }}
                        </span>
                      </td>
                      <td class="px-4 py-3 text-xs text-muted-foreground">
                        {{ optional($attempt->completed_at)->timezone(config('app.timezone', 'UTC'))->format('d M Y · H:i') }}
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </article>
      @endforeach
    </div>
  @endif
</section>
@endsection
