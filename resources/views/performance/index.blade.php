{{-- resources/views/performance/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Your Performance</h3>
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('learn.index') }}">Back to modules</a>
  </div>

  {{-- WEEKLY CHART --}}
  <div class="card mb-4">
    <div class="card-header">Weekly (last 12 weeks)</div>
    <div class="card-body">
      <canvas id="ccWeekly" height="110"></canvas>
    </div>
  </div>

  {{-- MONTHLY CHART --}}
  <div class="card mb-4">
    <div class="card-header">Monthly (last 12 months)</div>
    <div class="card-body">
      <canvas id="ccMonthly" height="110"></canvas>
    </div>
  </div>

  {{-- RECENT ATTEMPTS --}}
  <div class="card mb-4">
    <div class="card-header">Recent attempts</div>
    <div class="card-body p-0">
      <table class="table mb-0 align-middle">
        <thead><tr><th>Date</th><th>Module</th><th>Score</th><th>Duration</th><th></th></tr></thead>
        <tbody>
          @forelse($recent as $a)
            <tr>
              <td>{{ optional($a->completed_at)->format('Y-m-d H:i') }}</td>
              <td>{{ $a->module->title ?? '—' }}</td>
              <td>{{ $a->score }}%</td>
              <td>{{ $a->duration_sec }}s</td>
              <td><a class="btn btn-sm btn-primary" href="{{ route('quiz.result', $a) }}">View</a></td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-muted py-4">No attempts yet.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- MODULE STATUS --}}
  <div class="card">
    <div class="card-header">Module status</div>
    <div class="card-body p-0">
      <table class="table mb-0 align-middle">
        <thead><tr><th>Module</th><th style="width:40%">Progress</th><th>Percent</th></tr></thead>
        <tbody>
          @foreach($modules as $m)
            @php $pct = (int) ($progress[$m->id] ?? 0); @endphp
            <tr>
              <td>{{ $m->title }}</td>
              <td>
                <div class="progress" style="height:10px">
                  <div class="progress-bar" role="progressbar" style="width: {{ $pct }}%"></div>
                </div>
              </td>
              <td>{{ $pct }}%</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@push('scripts')
  {{-- Chart.js + date-fns adapter for time scale --}}
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
  <script>
    // Weekly
    const wCtx = document.getElementById('ccWeekly').getContext('2d');
    new Chart(wCtx, {
      type: 'line',
      data: {
        labels: @json($weeklyLabels),
        datasets: [
          { label: 'Avg score', data: @json($weeklyAvg), yAxisID: 'y1' },
          { label: 'Attempts',  data: @json($weeklyCount), yAxisID: 'y2' }
        ]
      },
      options: {
        parsing: false,
        scales: {
          x: { type: 'time', time: { unit: 'week' } },
          y1: { type: 'linear', position: 'left', suggestedMin: 0, suggestedMax: 100, title: { display: true, text: 'Score %' } },
          y2: { type: 'linear', position: 'right', suggestedMin: 0, ticks: { stepSize: 1 }, grid: { drawOnChartArea: false }, title: { display: true, text: 'Attempts' } }
        }
      }
    });

    // Monthly
    const mCtx = document.getElementById('ccMonthly').getContext('2d');
    new Chart(mCtx, {
      type: 'line',
      data: {
        labels: @json($monthlyLabels),
        datasets: [
          { label: 'Avg score', data: @json($monthlyAvg), yAxisID: 'y1' },
          { label: 'Attempts',  data: @json($monthlyCount), yAxisID: 'y2' }
        ]
      },
      options: {
        parsing: false,
        scales: {
          x: { type: 'time', time: { unit: 'month' } },
          y1: { type: 'linear', position: 'left', suggestedMin: 0, suggestedMax: 100, title: { display: true, text: 'Score %' } },
          y2: { type: 'linear', position: 'right', suggestedMin: 0, ticks: { stepSize: 1 }, grid: { drawOnChartArea: false }, title: { display: true, text: 'Attempts' } }
        }
      }
    });
  </script>
@endpush
