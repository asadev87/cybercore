{{-- resources/views/leaderboard/index.blade.php --}}

@extends('layouts.app')
@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Leaderboard (All-time Avg Score)</h3>
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('learn.index') }}">Back to modules</a>
  </div>

  <div class="card">
    <div class="card-body p-0">
      <table class="table align-middle mb-0">
        <thead><tr><th>#</th><th>Name</th><th>Avg Score</th><th>Attempts</th></tr></thead>
        <tbody>
          @foreach($rows as $i => $r)
            <tr>
              <td>{{ $i+1 }}</td>
              <td>{{ $r->user->name ?? 'User '.$r->user_id }}</td>
              <td>{{ number_format($r->avg_score,1) }}%</td>
              <td>{{ $r->attempts }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
