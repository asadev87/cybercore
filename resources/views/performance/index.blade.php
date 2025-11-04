{{-- resources/views/performance/index.blade.php --}}

@extends('layouts.app')

@section('content')
<section class="space-y-8">
  <header class="flex flex-wrap items-center justify-between gap-4">
    <div>
      <p class="text-xs font-semibold uppercase tracking-[0.24em] text-muted-foreground">Analytics</p>
      <h1 class="text-3xl font-semibold tracking-tight">Your performance</h1>
      <p class="text-sm text-muted-foreground">Review how often you log in and keep tabs on your recent quiz progress.</p>
    </div>
    <a class="btn btn-outline" href="{{ route('learn.index') }}">Back to modules</a>
  </header>

  <div class="grid gap-6 lg:grid-cols-2">
    <article class="card-surface p-6 lg:col-span-2">
      <div class="flex flex-col gap-2 pb-4 border-b border-border/60">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-semibold">Average score by module</h2>
          <span class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Overall avg: {{ !is_null($overallAverageScore) ? number_format($overallAverageScore, 1) : '—' }}%</span>
        </div>
        <p class="text-sm text-muted-foreground">Latest average per active module.</p>
      </div>
      <div class="mt-6 flex min-h-[18rem] flex-col">
        <div class="flex-1">
          <canvas id="chartModuleScores" class="h-full w-full"></canvas>
        </div>
        <p id="chartModuleScoresHint" class="mt-3 text-xs text-muted-foreground hidden">Scores will appear here once you complete a quiz.</p>
        <div class="mt-4 flex flex-wrap items-center gap-2 border-t border-border/60 pt-4 text-sm text-muted-foreground">
          @php
            $trendNote = __('No trend yet. Complete more attempts to calculate progress.');
            if (!empty($moduleScoreAverages) && count($moduleScoreAverages) > 1) {
                $delta = end($moduleScoreAverages) - $moduleScoreAverages[array_key_first($moduleScoreAverages)];
                $trendNote = $delta >= 0
                    ? __('Trending up by :value points since your earliest module.', ['value' => number_format($delta, 1)])
                    : __('Down by :value points compared to your earliest module.', ['value' => number_format(abs($delta), 1)]);
            }
          @endphp
          <span class="font-medium text-foreground">{{ $trendNote }}</span>
        </div>
      </div>
    </article>

    <article class="card-surface p-6">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold">Login activity (last 7 days)</h2>
        <span class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Daily sign-ins</span>
      </div>
      <div class="pt-4">
        <canvas id="chartWeekly" class="h-48 w-full"></canvas>
      </div>
      <p id="chartWeeklyHint" class="mt-3 text-xs text-muted-foreground hidden">No logins recorded yet. Once you sign in, your activity will show here.</p>
    </article>

    <article class="card-surface p-6">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold">Login activity (last 30 days)</h2>
        <span class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Daily sign-ins</span>
      </div>
      <div class="pt-4">
        <canvas id="chartMonthly" class="h-48 w-full"></canvas>
      </div>
      <p id="chartMonthlyHint" class="mt-3 text-xs text-muted-foreground hidden">No logins recorded yet. Your sign-ins will appear once you access your account.</p>
    </article>
  </div>

</div>

  <article class="card-surface overflow-hidden">
    <header class="border-b border-border/60 px-6 py-4">
      <h2 class="text-lg font-semibold">Recent attempts</h2>
    </header>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-border/60 text-sm">
        <thead class="bg-secondary/60 text-secondary-foreground">
          <tr>
            <th scope="col" class="px-6 py-3 text-left font-semibold">Date</th>
            <th scope="col" class="px-6 py-3 text-left font-semibold">Module</th>
            <th scope="col" class="px-6 py-3 text-left font-semibold">Score</th>
            <th scope="col" class="px-6 py-3 text-left font-semibold">Status</th>
            <th scope="col" class="px-6 py-3 text-left font-semibold"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border/60">
          @forelse($recent as $attempt)
            <tr class="transition hover:bg-secondary/40">
              <td class="px-6 py-4 text-sm text-muted-foreground">{{ optional($attempt->completed_at)->format('Y-m-d H:i') }}</td>
              <td class="px-6 py-4 text-sm font-medium text-foreground">{{ $attempt->module->title ?? '—' }}</td>
              <td class="px-6 py-4 text-sm text-muted-foreground">{{ $attempt->score }}%</td>
              <td class="px-6 py-4 text-sm text-muted-foreground">{{ $attempt->score >= ($attempt->module->pass_score ?? 70) ? 'Passed' : 'In progress' }}</td>
              <td class="px-6 py-4 text-right"><a class="btn btn-outline" href="{{ route('quiz.result', $attempt) }}">View</a></td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-6 py-8 text-center text-sm text-muted-foreground">No attempts yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </article>

  <article class="card-surface overflow-hidden">
    <header class="border-b border-border/60 px-6 py-4">
      <h2 class="text-lg font-semibold">Module status</h2>
    </header>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-border/60 text-sm">
        <thead class="bg-secondary/60 text-secondary-foreground">
          <tr>
            <th scope="col" class="px-6 py-3 text-left font-semibold">Module</th>
            <th scope="col" class="px-6 py-3 text-left font-semibold">Progress</th>
            <th scope="col" class="px-6 py-3 text-left font-semibold">Percent</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border/60">
          @foreach($modules as $module)
            @php $percent = (int) ($progress[$module->id] ?? 0); @endphp
            <tr class="transition hover:bg-secondary/40">
              <td class="px-6 py-4 text-sm font-medium text-foreground">{{ $module->title }}</td>
              <td class="px-6 py-4">
                <div class="h-2 max-w-md rounded-full bg-secondary">
                  <div class="h-2 rounded-full bg-primary" style="width: {{ $percent }}%" role="progressbar" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100" aria-label="Module progress"></div>
                </div>
              </td>
              <td class="px-6 py-4 text-sm text-muted-foreground">{{ $percent }}%</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </article>
</section>
@endsection

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
  <script>
    (() => {
      window.cybercoreCharts = window.cybercoreCharts || {};

      const weeklyLabels = @json($login7Labels);
      const weeklyRaw = @json($login7Counts).map((value) => Number(value) || 0);
      const monthlyLabels = @json($login30Labels);
      const monthlyRaw = @json($login30Counts).map((value) => Number(value) || 0);
      const moduleLabels = @json($moduleScoreLabels);
      const moduleRaw = @json($moduleScoreAverages).map((value) => Number(value) || 0);

      const loginMax = Math.max(
        weeklyRaw.length ? Math.max(...weeklyRaw) : 0,
        monthlyRaw.length ? Math.max(...monthlyRaw) : 0
      );
      const loginSuggestedMax = Math.max(10, loginMax + 1);

      const buildLineChart = (canvasId, hintId, labels, source) => {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;

        window.cybercoreCharts[canvasId]?.destroy();

        const hasData = source.some((value) => value > 0);
        const chartData = hasData ? source : labels.map(() => 0);
        const hint = document.getElementById(hintId);

        hint?.classList.toggle('hidden', hasData);

        const strokeColor = hasData ? '#2563eb' : 'rgba(148, 163, 184, 0.6)';
        const fillColor = hasData ? 'rgba(37, 99, 235, 0.1)' : 'transparent';

        window.cybercoreCharts[canvasId] = new Chart(canvas, {
          type: 'line',
          data: {
            labels,
            datasets: [{
              label: 'Daily sign-ins',
              data: chartData,
              fill: false,
              borderColor: strokeColor,
              backgroundColor: fillColor,
              tension: 0.3,
              pointRadius: hasData ? 4 : 0,
              pointHoverRadius: hasData ? 6 : 0,
              pointBackgroundColor: hasData ? '#2563eb' : 'rgba(148, 163, 184, 0.6)',
            }],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              x: {
                grid: { color: 'rgba(148, 163, 184, 0.15)' },
                ticks: { autoSkip: true, autoSkipPadding: 16, maxRotation: 0 },
                title: { display: true, text: 'Date' },
              },
              y: {
                beginAtZero: true,
                suggestedMax: loginSuggestedMax,
                ticks: { stepSize: 1, precision: 0 },
                title: { display: true, text: 'Login count' },
                grid: { color: 'rgba(148, 163, 184, 0.1)' },
              },
            },
            plugins: {
              legend: { display: false },
              tooltip: { enabled: hasData },
            },
          },
        });
      };

      buildLineChart('chartWeekly', 'chartWeeklyHint', weeklyLabels, weeklyRaw);
      buildLineChart('chartMonthly', 'chartMonthlyHint', monthlyLabels, monthlyRaw);

      const moduleCanvas = document.getElementById('chartModuleScores');
      if (moduleCanvas) {
        window.cybercoreCharts.chartModuleScores?.destroy();

        const hasModuleData = moduleLabels.length > 0;
        const labels = hasModuleData ? moduleLabels : ['No attempts yet'];
        const scores = hasModuleData ? moduleRaw : [0];
        const hint = document.getElementById('chartModuleScoresHint');

        hint?.classList.toggle('hidden', hasModuleData);

        const palette = ['#ef4444', '#3b82f6', '#facc15', '#22c55e', '#a855f7', '#fb923c'];
        const backgroundColor = labels.map((_, index) => palette[index % palette.length]);

        window.cybercoreCharts.chartModuleScores = new Chart(moduleCanvas, {
          type: 'bar',
          data: {
            labels,
            datasets: [{
              label: 'Average score (%)',
              data: scores,
              backgroundColor,
              borderWidth: 1,
              borderRadius: 12,
              borderSkipped: false,
            }],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
              y: {
                beginAtZero: true,
                suggestedMax: 100,
              },
            },
          },
        });
      }
    })();
  </script>
@endpush

