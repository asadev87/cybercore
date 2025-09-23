{{-- resources/views/badges/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-end mb-3">
    <div>
      <h3 class="mb-0">Your badges & completed modules</h3>
      <div class="text-muted">Great work—keep going!</div>
    </div>
    <a class="btn btn-outline-primary btn-sm" href="{{ route('learn.index') }}">Back to Learn</a>
  </div>

  {{-- Earned badges (if available) --}}
  @if($hasBadgeTables)
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center">
        <strong>Earned Badges</strong>
        <span class="badge bg-primary ms-2">{{ $earnedBadges->count() }}</span>
      </div>
      <div class="card-body">
        @if($earnedBadges->isEmpty())
          <p class="text-muted mb-0">No badges yet. Complete a module to earn your first badge.</p>
        @else
          <div class="row g-3">
            @foreach($earnedBadges as $b)
              <div class="col-6 col-md-4 col-lg-3">
                <div class="border rounded-3 p-3 h-100 d-flex flex-column justify-content-between">
                  <div>
                    <div class="fw-bold">{{ $b->name }}</div>
                    <div class="small text-muted">{{ $b->description }}</div>
                  </div>
                  <div class="small text-secondary mt-2">Awarded {{ optional($b->pivot->awarded_at)->diffForHumans() }}</div>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  @endif

  {{-- Completed modules --}}
  <div class="card">
    <div class="card-header d-flex align-items-center">
      <strong>Completed Modules</strong>
      <span class="badge bg-success ms-2">{{ $completed->count() }}</span>
    </div>
    <div class="card-body">
      @if($completed->isEmpty())
        <p class="text-muted mb-0">No completed modules yet. Start a topic from the Learn page.</p>
      @else
        <div class="row g-3">
          @foreach($completed as $p)
            <div class="col-12 col-md-6 col-lg-4">
              <div class="border rounded-3 p-3 h-100 d-flex flex-column">
                <div class="d-flex align-items-center mb-2">
                  <span class="me-2" aria-hidden="true">🏅</span>
                  <div class="fw-bold mb-0">{{ $p->module->title }}</div>
                </div>
                <div class="small text-muted mb-2">
                  {{ \Illuminate\Support\Str::limit($p->module->description, 120) }}
                </div>
                <div class="mt-auto d-flex justify-content-between align-items-center">
                  <span class="badge text-bg-success">Completed</span>
                  <a class="btn btn-sm btn-outline-primary"
                     href="{{ route('learn.start', $p->module) }}">
                     Review
                  </a>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>
</div>
@endsection