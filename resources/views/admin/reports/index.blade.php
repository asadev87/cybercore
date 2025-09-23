{{-- resources/views/admin/reports/index.blade.php --}}

@extends('admin.layout')
@section('content')
<div class="d-flex justify-content-between align-items-end mb-3">
  <h4 class="mb-0">Analytics & Reports</h4>
  <div class="d-flex gap-2">
    <a class="btn btn-outline-primary btn-sm"
       href="{{ route('admin.reports.export.excel', request()->only('from','to','module_id')) }}">Export Excel</a>
    <a class="btn btn-outline-danger btn-sm"
       href="{{ route('admin.reports.export.pdf',   request()->only('from','to','module_id')) }}">Export PDF</a>
  </div>
</div>

<form method="GET" class="row g-2 align-items-end mb-3">
  <div class="col-auto">
    <label class="form-label">From</label>
    <input type="date" name="from" class="form-control" value="{{ optional($from)->toDateString() }}">
  </div>
  <div class="col-auto">
    <label class="form-label">To</label>
    <input type="date" name="to" class="form-control" value="{{ optional($to)->toDateString() }}">
  </div>
  <div class="col-auto">
    <label class="form-label">Module</label>
    <select name="module_id" class="form-select">
      <option value="">All modules</option>
      @foreach($modules as $m)
        <option value="{{ $m->id }}" @selected($moduleId==$m->id)>{{ $m->title }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-auto"><button class="btn btn-primary">Apply</button></div>
</form>

<div class="row g-3 mb-4">
  <div class="col-md-4"><div class="card"><div class="card-body">
    <div class="small text-muted">Attempts</div>
    <div class="h4 mb-0">{{ number_format($summary->attempts ?? 0) }}</div>
  </div></div></div>
  <div class="col-md-4"><div class="card"><div class="card-body">
    <div class="small text-muted">Average score</div>
    <div class="h4 mb-0">{{ isset($summary->avg_score) ? number_format($summary->avg_score,1).'%' : '—' }}</div>
  </div></div></div>
  <div class="col-md-4"><div class="card"><div class="card-body">
    <div class="small text-muted">Passes</div>
    <div class="h4 mb-0">{{ number_format($summary->passes ?? 0) }}</div>
  </div></div></div>
</div>

<div class="card mb-4">
  <div class="card-header">Ranking by module (avg score)</div>
  <div class="card-body p-0">
    <table class="table mb-0 align-middle">
      <thead><tr><th>#</th><th>Module</th><th>Attempts</th><th>Passes</th><th>Avg score</th></tr></thead>
      <tbody>
        @forelse($ranking as $i=>$r)
          <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $r->title }}</td>
            <td>{{ $r->attempts }}</td>
            <td>{{ $r->passes }}</td>
            <td>{{ number_format($r->avg_score,1) }}%</td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center text-muted py-3">No data.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="card">
  <div class="card-header">Recent attempts</div>
  <div class="card-body p-0">
    <table class="table mb-0 align-middle">
      <thead><tr><th>When</th><th>User</th><th>Module</th><th>Score</th></tr></thead>
      <tbody>
        @forelse($rows as $a)
          <tr>
            <td>{{ $a->completed_at?->format('Y-m-d H:i') }}</td>
            <td>{{ $a->user->name ?? $a->user->email }}</td>
            <td>{{ $a->module->title }}</td>
            <td>{{ $a->score }}%</td>
          </tr>
        @empty
          <tr><td colspan="4" class="text-center text-muted py-3">No attempts in range.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
