{{-- resources/views/learn/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h3 class="mb-3">Available modules</h3>

  <div class="row g-3">
    @forelse ($modules as $m)
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h5 class="card-title">{{ $m->title }}</h5>
            <p class="text-secondary small mb-3">
              {{ \Illuminate\Support\Str::limit($m->description, 140) }}
            </p>

            @php $pct = (int) ($progress[$m->id] ?? 0); @endphp
            <div class="progress mb-3" style="height:10px">
              <div class="progress-bar" role="progressbar" style="width: {{ $pct }}%"></div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
              <div class="text-muted small">Progress: {{ $pct }}%</div>
              <form action="{{ route('quiz.start', $m) }}" method="POST" class="m-0">
                @csrf
                <button class="btn btn-primary btn-sm">Start / Resume</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-muted">No modules yet.</div>
    @endforelse
  </div>
</div>
@endsection
