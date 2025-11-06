{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.layout')

@section('content')
<section class="space-y-8">
  <header class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
    <div class="space-y-2">
      <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Control Centre</p>
      <h1 class="text-3xl font-semibold tracking-tight">Welcome back, {{ auth()->user()->name ?? 'Admin' }}</h1>
      <p class="max-w-2xl text-sm text-muted-foreground">Monitor learner momentum, curate content, and keep your course catalogue fresh with the tools below.</p>
    </div>
    <div class="flex flex-wrap items-center gap-2 sm:justify-end">
      <a href="{{ route('dashboard') }}" class="btn btn-muted text-sm">User dashboard</a>
      <a href="{{ url('/') }}" class="btn btn-outline text-sm">View landing page</a>
    </div>
  </header>

  <div class="grid gap-6">
    <article class="bento-card bento-card--hero text-[15px] sm:text-base">
      <div class="flex flex-col items-center gap-8 text-center">
        <div class="space-y-2">
          <span class="bento-pill">Admin workspace</span>
          <h2 class="text-2xl font-semibold tracking-tight">Keep CyberCore running smoothly</h2>
          <p class="text-sm text-muted-foreground">Snapshot of the latest activity and quick actions.</p>
        </div>

        <dl class="grid w-full max-w-3xl grid-cols-2 gap-4 sm:grid-cols-4">
          <div class="bento-badge">
            <dt class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Learners</dt>
            <dd class="bento-stat">{{ $users }}</dd>
          </div>
          <div class="bento-badge">
            <dt class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Modules</dt>
            <dd class="bento-stat">{{ $modules }}</dd>
          </div>
          <div class="bento-badge">
            <dt class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Questions</dt>
            <dd class="bento-stat">{{ $questions }}</dd>
          </div>
          <div class="bento-badge">
            <dt class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Attempts</dt>
            <dd class="bento-stat">{{ $attempts }}</dd>
          </div>
        </dl>

        <div class="grid w-full max-w-3xl gap-4 sm:grid-cols-3">
          <a href="{{ route('admin.modules.index') }}" class="btn btn-primary w-full">Open builder</a>
          @can('create', App\Models\Module::class)
            <a href="{{ route('admin.modules.create') }}" class="btn btn-outline w-full">New module</a>
          @endcan
          <a href="{{ route('admin.users.index') }}" class="btn btn-outline w-full">Manage users</a>
          <a href="{{ route('admin.modules.index', ['view' => 'owners']) }}" class="btn btn-outline w-full">Manage ownership</a>
          @if (auth()->user()?->hasRole('admin'))
            <a href="{{ route('admin.performance.monitor') }}" class="btn btn-outline w-full">Performance monitor</a>
          @elseif (auth()->user()?->hasRole('lecturer'))
            <a href="{{ route('admin.performance.index') }}" class="btn btn-outline w-full">Lecturer performance</a>
          @else
            <a href="{{ route('performance.index') }}" class="btn btn-outline w-full">Performance insight</a>
          @endif
          <a href="{{ route('leaderboard.index') }}" class="btn btn-outline w-full">Leaderboard</a>
        </div>
      </div>
    </article>

    <article class="bento-card overflow-hidden text-[15px] sm:text-base">
      <div class="flex items-center justify-between border-b border-border/60 pb-4">
        <div>
          <p class="bento-label">Recent activity</p>
          <h3 class="text-lg font-semibold">Latest quiz attempts</h3>
        </div>
        <span class="text-xs font-medium uppercase tracking-[0.2em] text-muted-foreground">Last 10 entries</span>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-border/60 text-left text-sm">
          <thead class="bg-secondary/60 text-secondary-foreground">
            <tr>
              <th scope="col" class="px-6 py-3 font-semibold">User</th>
              <th scope="col" class="px-6 py-3 font-semibold">Module</th>
              <th scope="col" class="px-6 py-3 font-semibold">Score</th>
              <th scope="col" class="px-6 py-3 font-semibold">Completed</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-border/60">
          @forelse($recent as $a)
            <tr class="transition hover:bg-secondary/40">
              <td class="px-6 py-4 text-sm font-medium text-foreground">{{ optional($a->user)->name ?? optional($a->user)->email ?? __('Unknown user') }}</td>
              <td class="px-6 py-4 text-sm text-muted-foreground">{{ optional($a->module)->title ?? __('Unknown module') }}</td>
              <td class="px-6 py-4 text-sm text-muted-foreground">{{ $a->score }}%</td>
              <td class="px-6 py-4 text-sm text-muted-foreground">{{ optional($a->completed_at)->diffForHumans() }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-6 py-8 text-center text-sm text-muted-foreground">No attempts yet.</td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>
    </article>
  </div>
</section>
@endsection

