{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.layout')

@section('content')
<div class="row g-3">
  <div class="col-md-3"><div class="card"><div class="card-body"><div class="small text-muted">Users</div><div class="h3">{{ $users }}</div></div></div></div>
  <div class="col-md-3"><div class="card"><div class="card-body"><div class="small text-muted">Modules</div><div class="h3">{{ $modules }}</div></div></div></div>
  <div class="col-md-3"><div class="card"><div class="card-body"><div class="small text-muted">Questions</div><div class="h3">{{ $questions }}</div></div></div></div>
  <div class="col-md-3"><div class="card"><div class="card-body"><div class="small text-muted">Quiz Attempts</div><div class="h3">{{ $attempts }}</div></div></div></div>
</div>

<div class="card mt-4">
  <div class="card-header">Recent activity</div>
  <div class="card-body p-0">
    <table class="table align-middle mb-0">
      <thead><tr><th>User</th><th>Module</th><th>Score</th><th>Completed</th></tr></thead>
      <tbody>
      @forelse($recent as $a)
        <tr>
          <td>{{ $a->user->name ?? $a->user->email }}</td>
          <td>{{ $a->module->title }}</td>
          <td>{{ $a->score }}%</td>
          <td>{{ optional($a->completed_at)->diffForHumans() }}</td>
        </tr>
      @empty
        <tr><td colspan="4" class="text-center text-muted py-4">No attempts yet.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
