{{-- resources/views/learn/module.blade.php --}}

@extends('layouts.app')
@section('content')
<div class="container">
  <div class="card bg-surface shadow-soft mb-4">
    <div class="card-body">
      <h3 class="mb-1">{{ $module->title }}</h3>
      <p class="text-muted">Taught by: {{ $module->user->name ?? 'N/A' }}</p>
      <p class="text-muted mb-3">{{ $module->description }}</p>
      @php
        $total = max(1,$sections->count());
        $done  = $sections->filter(fn($s)=>($prog[$s->id]??0) >= 100)->count();
        $pct   = intval(($done/$total)*100);
      @endphp
      <div class="small text-muted mb-1">{{ $pct }}% Overall Progress</div>
      <div class="progress" style="height:10px">
        <div class="progress-bar" role="progressbar" style="width: {{ $pct }}%" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
      </div>
    </div>
  </div>

  @foreach($sections as $i => $s)
    @php $p = intval($prog[$s->id] ?? 0); $locked = $i>0 && intval($prog[$sections[$i-1]->id] ?? 0) < 100; @endphp
    <div class="card mb-3 {{ $locked?'opacity-75':'' }}">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <div class="d-flex align-items-center gap-2">
              <span class="badge rounded-pill text-bg-secondary">{{ $i+1 }}</span>
              <h5 class="mb-0">{{ $s->title }}</h5>
            </div>
            <div class="text-muted small mt-1">{{ $s->description }}</div>
          </div>
          <div class="text-end">
            <div class="small text-muted mb-1">{{ $p >= 100 ? 'Completed' : ($p>0?'In Progress':'Not Started') }}</div>
            <div class="progress" style="height:6px;width:220px">
              <div class="progress-bar {{ $p>=100?'accent':'' }}" style="width: {{ $p }}%"></div>
            </div>
          </div>
        </div>

        <div class="mt-3">
          @if($locked)
            <button class="btn btn-secondary" disabled>Complete Section {{ $i }} first</button>
          @else
            <a class="btn btn-primary" href="{{ route('learn.start', $module) }}?section={{ $s->id }}">
              {{ $p>0?'Continue Section':'Start Section' }}
            </a>
          @endif
        </div>
      </div>
    </div>
  @endforeach
</div>
@endsection
