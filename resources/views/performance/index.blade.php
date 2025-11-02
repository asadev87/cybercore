{{-- resources/views/performance/index.blade.php --}}

@extends('layouts.app')

@section('content')
<section class="space-y-8">
  <header class="flex flex-wrap items-center justify-between gap-4">
    <div>
      <p class="text-xs font-semibold uppercase tracking-[0.24em] text-muted-foreground">Analytics</p>
      <h1 class="text-3xl font-semibold tracking-tight">Your performance</h1>
      <p class="text-sm text-muted-foreground">Track how your scores evolve each week and review recent attempts.</p>
    </div>
    <a class="btn btn-outline" href="{{ route('learn.index') }}">Back to modules</a>
  </header>

  <div class="grid gap-6 lg:grid-cols-2">
    <article class="card-surface p-6">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold">Weekly trend (last 7 days)</h2>
        <span class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Scores & attempts</span>
      </div>
      <canvas id="ccWeekly" class="mt-6 h-48 w-full"></canvas>
      <p id="ccWeeklyHint" class="mt-3 text-xs text-muted-foreground hidden">No attempts yet. Your activity will appear here after your first quiz.</p>
    </article>

    <article class="card-surface p-6">
      <div class="flex items-center justify-between">
        <h2 class="text-lg font-semibold">Monthly trend (last 30 days)</h2>
        <span class="text-xs font-semibold uppercase tracking-wide text-muted-foreground">Scores & attempts</span>
      </div>
      <canvas id="ccMonthly" class="mt-6 h-48 w-full"></canvas>
      <p id="ccMonthlyHint" class="mt-3 text-xs text-muted-foreground hidden">No attempts yet. Your activity will appear here after your first quiz.</p>
    </article>
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
            <th scope="col" class="px-6 py-3 text-left font-semibold">Duration</th>
            <th scope="col" class="px-6 py-3 text-left font-semibold"></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border/60">
          @forelse($recent as $a)
            <tr class="transition hover:bg-secondary/40">
              <td class="px-6 py-4 text-sm text-muted-foreground">{{ optional($a->completed_at)->format('Y-m-d H:i') }}</td>
              <td class="px-6 py-4 text-sm font-medium text-foreground">{{ $a->module->title ?? '—' }}</td>
              <td class="px-6 py-4 text-sm text-muted-foreground">{{ $a->score }}%</td>
              <td class="px-6 py-4 text-sm text-muted-foreground">{{ $a->score >= ($a->module->pass_score ?? 70) ? "Passed" : "In progress" }}</td>
              <td class="px-6 py-4 text-right"><a class="btn btn-outline" href="{{ route('quiz.result', $a) }}">View</a></td>
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
          @foreach($modules as $m)
            @php $pct = (int) ($progress[$m->id] ?? 0); @endphp
            <tr class="transition hover:bg-secondary/40">
              <td class="px-6 py-4 text-sm font-medium text-foreground">{{ $m->title }}</td>
              <td class="px-6 py-4">
                <div class="h-2 max-w-md rounded-full bg-secondary">
                  <div class="h-2 rounded-full bg-primary" style="width: {{ $pct }}%" role="progressbar" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100" aria-label="Module progress"></div>
                </div>
              </td>
              <td class="px-6 py-4 text-sm text-muted-foreground">{{ $pct }}%</td>
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
    // Weekly chart with placeholder series when no data
    const wCtx = document.getElementById('ccWeekly');
    if (wCtx) {
      const weeklyLabels = @json($weeklyLabels);
      const weeklyAvg = @json($weeklyAvg);
      const weeklyCount = @json($weeklyCount);

      const hasWeeklyData = (Array.isArray(weeklyAvg) && weeklyAvg.some(v => v !== null && v !== undefined))
        || (Array.isArray(weeklyCount) && weeklyCount.some(v => Number(v) > 0));

      const weeklyDatasets = [
        { label: 'Avg score', data: weeklyAvg, yAxisID: 'y1', tension: 0.4, borderColor: '#2563eb', backgroundColor: 'rgba(37, 99, 235, 0.15)', fill: true, spanGaps: true },
        { label: 'Attempts', data: weeklyCount, yAxisID: 'y2', tension: 0.4, borderColor: '#0ea5e9', backgroundColor: 'rgba(14, 165, 233, 0.1)', spanGaps: true }
      ];

      if (!hasWeeklyData) {
        weeklyDatasets.push({
          label: 'No data yet',
          data: weeklyLabels.map(() => 8), // low baseline on score axis
          yAxisID: 'y1',
          tension: 0.4,
          borderColor: 'rgba(148, 163, 184, 0.8)', // slate-400
          backgroundColor: 'transparent',
          borderDash: [6, 4],
          pointRadius: 0,
          fill: false
        });
      }

      const weeklyHint = document.getElementById('ccWeeklyHint');
      if (weeklyHint) {
        weeklyHint.classList.toggle('hidden', hasWeeklyData);
      }

      new Chart(wCtx, {
        type: 'line',
        data: { labels: weeklyLabels, datasets: weeklyDatasets },
        options: {
          parsing: false,
          maintainAspectRatio: false,
          scales: {
            x: { type: 'time', time: { unit: 'day' }, grid: { color: 'rgba(148, 163, 184, 0.15)' } },
            y1: { type: 'linear', position: 'left', suggestedMin: 0, suggestedMax: 100, title: { display: true, text: 'Score %' }, grid: { color: 'rgba(148, 163, 184, 0.1)' } },
            y2: { type: 'linear', position: 'right', suggestedMin: 0, ticks: { stepSize: 1 }, grid: { drawOnChartArea: false }, title: { display: true, text: 'Attempts' } }
          },
          plugins: {
            legend: { display: true, labels: { usePointStyle: true } },
            tooltip: { enabled: hasWeeklyData }
          }
        }
      });
    }

    // Monthly chart with placeholder series when no data
    const mCtx = document.getElementById('ccMonthly');
    if (mCtx) {
      const monthlyLabels = @json($monthlyLabels);
      const monthlyAvg = @json($monthlyAvg);
      const monthlyCount = @json($monthlyCount);

      const hasMonthlyData = (Array.isArray(monthlyAvg) && monthlyAvg.some(v => v !== null && v !== undefined))
        || (Array.isArray(monthlyCount) && monthlyCount.some(v => Number(v) > 0));

      const monthlyDatasets = [
        { label: 'Avg score', data: monthlyAvg, yAxisID: 'y1', tension: 0.4, borderColor: '#2563eb', backgroundColor: 'rgba(37, 99, 235, 0.15)', fill: true, spanGaps: true },
        { label: 'Attempts', data: monthlyCount, yAxisID: 'y2', tension: 0.4, borderColor: '#0ea5e9', backgroundColor: 'rgba(14, 165, 233, 0.1)', spanGaps: true }
      ];

      if (!hasMonthlyData) {
        monthlyDatasets.push({
          label: 'No data yet',
          data: monthlyLabels.map(() => 8),
          yAxisID: 'y1',
          tension: 0.4,
          borderColor: 'rgba(148, 163, 184, 0.8)',
          backgroundColor: 'transparent',
          borderDash: [6, 4],
          pointRadius: 0,
          fill: false
        });
      }

      const monthlyHint = document.getElementById('ccMonthlyHint');
      if (monthlyHint) {
        monthlyHint.classList.toggle('hidden', hasMonthlyData);
      }

      new Chart(mCtx, {
        type: 'line',
        data: { labels: monthlyLabels, datasets: monthlyDatasets },
        options: {
          parsing: false,
          maintainAspectRatio: false,
          scales: {
            x: { type: 'time', time: { unit: 'day' }, grid: { color: 'rgba(148, 163, 184, 0.15)' } },
            y1: { type: 'linear', position: 'left', suggestedMin: 0, suggestedMax: 100, title: { display: true, text: 'Score %' }, grid: { color: 'rgba(148, 163, 184, 0.1)' } },
            y2: { type: 'linear', position: 'right', suggestedMin: 0, ticks: { stepSize: 1 }, grid: { drawOnChartArea: false }, title: { display: true, text: 'Attempts' } }
          },
          plugins: {
            legend: { display: true, labels: { usePointStyle: true } },
            tooltip: { enabled: hasMonthlyData }
          }
        }
      });
    }
  </script>
@endpush
