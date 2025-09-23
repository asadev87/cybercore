{{-- resources/views/dashboard.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
  <h1 class="h3 mb-4">Welcome, {{ auth()->user()->name ?? 'Learner' }}</h1>

  {{-- Quick stats (optional) --}}
  {{-- <div class="alert alert-info">You have completed 2 modules this month.</div> --}}

  <div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-lg-3">
    {{-- Learn --}}
    <div class="col">
      <div class="card h-100 shadow-sm">
        <div class="card-body d-flex flex-column">
          <div class="d-flex align-items-center mb-2">
            <i class="bi bi-journal-text fs-3 me-2 text-primary"></i>
            <h5 class="card-title mb-0">Start Learning</h5>
          </div>
          <p class="text-secondary flex-grow-1">Browse modules and continue where you left off.</p>
          <a href="{{ route('learn.index') }}" class="btn btn-primary mt-auto">Go to Learn</a>
        </div>
      </div>
    </div>

    {{-- Performance --}}
    <div class="col">
      <div class="card h-100 shadow-sm">
        <div class="card-body d-flex flex-column">
          <div class="d-flex align-items-center mb-2">
            <i class="bi bi-graph-up fs-3 me-2 text-primary"></i>
            <h5 class="card-title mb-0">Performance</h5>
          </div>
          <p class="text-secondary flex-grow-1">View progress, history and analytics.</p>
          <a href="{{ route('performance.index') }}" class="btn btn-primary mt-auto">Open Performance</a>
        </div>
      </div>
    </div>

    {{-- Leaderboard --}}
    <div class="col">
      <div class="card h-100 shadow-sm">
        <div class="card-body d-flex flex-column">
          <div class="d-flex align-items-center mb-2">
            <i class="bi bi-trophy fs-3 me-2 text-primary"></i>
            <h5 class="card-title mb-0">Leaderboard</h5>
          </div>
          <p class="text-secondary flex-grow-1">See top scores and your rank.</p>
          <a href="{{ route('leaderboard.index') }}" class="btn btn-primary mt-auto">View Leaderboard</a>
        </div>
      </div>
    </div>

    {{-- Badges --}}
    <div class="col">
      <div class="card h-100 shadow-sm">
        <div class="card-body d-flex flex-column">
          <div class="d-flex align-items-center mb-2">
            <i class="bi bi-patch-check fs-3 me-2 text-primary"></i>
            <h5 class="card-title mb-0">Badges</h5>
          </div>
          <p class="text-secondary flex-grow-1">Collect badges for finished modules.</p>
          <a href="{{ url('/badges') }}" class="btn btn-primary mt-auto">My Badges</a>
        </div>
      </div>
    </div>

    {{-- Account --}}
    <div class="col">
      <div class="card h-100 shadow-sm">
        <div class="card-body d-flex flex-column">
          <div class="d-flex align-items-center mb-2">
            <i class="bi bi-person-gear fs-3 me-2 text-primary"></i>
            <h5 class="card-title mb-0">Account</h5>
          </div>
          <p class="text-secondary flex-grow-1">Update your name, email, or password.</p>
          <a href="{{ route('account.index') }}" class="btn btn-primary mt-auto">Account Settings</a>
        </div>
      </div>
    </div>

    {{-- Admin (visible only to admins) --}}
    @role('admin')
    <div class="col">
      <div class="card h-100 shadow-sm border-danger">
        <div class="card-body d-flex flex-column">
          <div class="d-flex align-items-center mb-2">
            <i class="bi bi-shield-lock fs-3 me-2 text-danger"></i>
            <h5 class="card-title mb-0">Admin</h5>
          </div>
          <p class="text-secondary flex-grow-1">Manage modules, questions and reports.</p>
          <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-danger mt-auto">Open Admin</a>
        </div>
      </div>
    </div>
    @endrole
  </div>
</div>
@endsection

