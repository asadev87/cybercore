{{-- resources/views/dashboard.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="space-y-8">
  <header class="flex flex-col gap-2">
    <p class="text-sm font-medium text-muted-foreground">Welcome back</p>
    <h1 class="text-3xl font-semibold tracking-tight">{{ auth()->user()->name ?? 'Learner' }}</h1>
  </header>

  <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
    <article class="card-surface flex h-full flex-col gap-4 p-6">
      <div class="flex items-start justify-between">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-primary/10 text-primary">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 19h16M7 10h10M5 5h14" />
          </svg>
        </span>
        <span class="rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-primary">Modules</span>
      </div>
      <div class="space-y-3">
        <h2 class="text-xl font-semibold">Start learning</h2>
        <p class="text-sm text-muted-foreground">Browse curated modules and continue where you left off.</p>
      </div>
      <a href="{{ route('learn.index') }}" class="btn btn-primary mt-auto">Go to Learn</a>
    </article>

    <article class="card-surface flex h-full flex-col gap-4 p-6">
      <div class="flex items-start justify-between">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-primary/10 text-primary">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 12h16M4 16h16M4 8h16" />
          </svg>
        </span>
        <span class="rounded-full bg-secondary px-3 py-1 text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Insights</span>
      </div>
      <div class="space-y-3">
        <h2 class="text-xl font-semibold">Performance</h2>
        <p class="text-sm text-muted-foreground">Review your progress, scores, and personal recommendations.</p>
      </div>
      <a href="{{ route('performance.index') }}" class="btn btn-outline mt-auto">Open Performance</a>
    </article>

    <article class="card-surface flex h-full flex-col gap-4 p-6">
      <div class="flex items-start justify-between">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-primary/10 text-primary">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
          </svg>
        </span>
        <span class="rounded-full bg-accent/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-accent-foreground">Motivation</span>
      </div>
      <div class="space-y-3">
        <h2 class="text-xl font-semibold">Leaderboard</h2>
        <p class="text-sm text-muted-foreground">See how you compare with peers and stay motivated.</p>
      </div>
      <a href="{{ route('leaderboard.index') }}" class="btn btn-outline mt-auto">View Leaderboard</a>
    </article>

    <article class="card-surface flex h-full flex-col gap-4 p-6">
      <div class="flex items-start justify-between">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-primary/10 text-primary">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h10v10H7z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="m9.5 13 1.5 1.5L14.5 11" />
          </svg>
        </span>
        <span class="rounded-full bg-success/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-success">Achievements</span>
      </div>
      <div class="space-y-3">
        <h2 class="text-xl font-semibold">Badges</h2>
        <p class="text-sm text-muted-foreground">Collect and showcase progress for completed modules.</p>
      </div>
      <a href="{{ url('/badges') }}" class="btn btn-outline mt-auto">My Badges</a>
    </article>

    <article class="card-surface flex h-full flex-col gap-4 p-6">
      <div class="flex items-start justify-between">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-primary/10 text-primary">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12c2.485 0 4.5-2.015 4.5-4.5S14.485 3 12 3 7.5 5.015 7.5 7.5 9.515 12 12 12ZM5.25 21a6.75 6.75 0 0 1 13.5 0" />
          </svg>
        </span>
        <span class="rounded-full bg-secondary px-3 py-1 text-xs font-semibold uppercase tracking-wide text-secondary-foreground">Profile</span>
      </div>
      <div class="space-y-3">
        <h2 class="text-xl font-semibold">Account</h2>
        <p class="text-sm text-muted-foreground">Manage your profile, security, and notification preferences.</p>
      </div>
      <a href="{{ route('account.index') }}" class="btn btn-outline mt-auto">Account Settings</a>
    </article>

    @role('admin')
    <article class="card-surface flex h-full flex-col gap-4 border border-destructive/30 p-6">
      <div class="flex items-start justify-between">
        <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-destructive/10 text-destructive">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="m4 6 8-3 8 3v4c0 5.25-3.5 10-8 11-4.5-1-8-5.75-8-11V6Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v4" />
            <circle cx="12" cy="17" r="0.75" fill="currentColor" />
          </svg>
        </span>
        <span class="rounded-full bg-destructive/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-destructive">Admin</span>
      </div>
      <div class="space-y-3">
        <h2 class="text-xl font-semibold">Admin tools</h2>
        <p class="text-sm text-muted-foreground">Publish modules, manage content, and review reports.</p>
      </div>
      <a href="{{ route('admin.dashboard') }}" class="btn btn-destructive mt-auto">Open Admin</a>
    </article>
    @endrole
  </div>
</div>
@endsection
