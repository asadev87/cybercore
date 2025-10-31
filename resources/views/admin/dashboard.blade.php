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

  <div class="grid gap-6 lg:grid-cols-4 auto-rows-[minmax(200px,auto)]">
    <article class="bento-card bento-card--hero lg:col-span-2 text-[15px] sm:text-base">
      <div class="flex flex-col gap-6">
        <div class="flex items-center justify-between gap-4">
          <div class="space-y-2">
            <span class="bento-pill">Course authoring</span>
            <h2 class="text-2xl font-semibold tracking-tight">Build once, evolve often.</h2>
            <p class="text-base text-muted-foreground">You have <span class="font-semibold text-foreground">{{ $modules }}</span> modules and <span class="font-semibold text-foreground">{{ $questions }}</span> questions live in the catalogue.</p>
          </div>
          <div class="hidden text-right text-xs font-medium text-muted-foreground sm:block">Updated {{ now()->format('M j, Y') }}</div>
        </div>

        <div class="flex flex-wrap items-center gap-3">
          <a href="{{ route('admin.modules.index') }}" class="btn btn-primary">Open builder</a>
          @can('create', App\Models\Module::class)
            <a href="{{ route('admin.modules.create') }}" class="btn btn-outline">New module</a>
          @endcan
        </div>

        <dl class="grid gap-4 sm:grid-cols-2">
          <div class="bento-badge">
            <dt class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Active learners</dt>
            <dd class="bento-stat">{{ $users }}</dd>
          </div>
          <div class="bento-badge">
            <dt class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Total attempts</dt>
            <dd class="bento-stat">{{ $attempts }}</dd>
          </div>
        </dl>
      </div>
    </article>

    <article class="bento-card bento-card--metric text-[15px] sm:text-base">
      <p class="bento-label">Users</p>
      <p class="bento-stat">{{ $users }}</p>
      <p class="bento-sub text-[15px] sm:text-base">Invite learners or staff by assigning roles in the people directory.</p>
      <a href="{{ route('account.index') }}" class="btn btn-outline text-sm">Manage profiles →</a>
    </article>

    <article class="bento-card bento-card--metric text-[15px] sm:text-base">
      <p class="bento-label">Modules</p>
      <p class="bento-stat">{{ $modules }}</p>
      <p class="bento-sub text-[15px] sm:text-base">Draft, publish, and retire modules as your curriculum evolves.</p>
      <a href="{{ route('admin.modules.index') }}" class="btn btn-outline text-sm">View catalogue →</a>
    </article>

    <article class="bento-card bento-card--metric text-[15px] sm:text-base">
      <p class="bento-label">Questions</p>
      <p class="bento-stat">{{ $questions }}</p>
      <p class="bento-sub text-[15px] sm:text-base">Refresh assessments with new scenarios to keep pace with emerging threats.</p>
      <a href="{{ route('admin.modules.index') }}" class="btn btn-outline text-sm">Open question bank →</a>
    </article>

    <article class="bento-card bento-card--metric text-[15px] sm:text-base">
      <p class="bento-label">Quiz attempts</p>
      <p class="bento-stat">{{ $attempts }}</p>
      <p class="bento-sub text-[15px] sm:text-base">Track completions to spot momentum and identify modules that need a boost.</p>
      <a href="{{ route('performance.index') }}" class="btn btn-outline text-sm">View performance →</a>
    </article>

    @can('viewAny', App\Models\Module::class)
    <article class="bento-card lg:col-span-2 text-[15px] sm:text-base">
      <div class="flex items-center justify-between gap-3">
        <div>
          <p class="bento-label">Quick actions</p>
          <h3 class="text-lg font-semibold">Speed up your next update</h3>
        </div>
        <span class="bento-pill">Shortcuts</span>
      </div>
      <ul class="bento-list">
        <li>
          <span class="bento-list__title">Access the module builder</span>
          <span class="bento-list__description text-[15px] sm:text-base">Jump into the catalogue to tweak sections, questions, and metadata.</span>
          <a href="{{ route('admin.modules.index') }}" class="btn btn-outline text-sm">Open builder →</a>
        </li>
        <li>
          <span class="bento-list__title">Assign lecturers</span>
          <span class="bento-list__description text-[15px] sm:text-base">Map each module to a subject lead to keep ownership clear.</span>
          <a href="{{ route('admin.modules.index') }}?view=owners" class="btn btn-outline text-sm">Manage ownership →</a>
        </li>
        <li>
          <span class="bento-list__title">Celebrate wins</span>
          <span class="bento-list__description text-[15px] sm:text-base">Recognise high scores and healthy competition on the leaderboard.</span>
          <a href="{{ route('leaderboard.index') }}" class="btn btn-outline text-sm">Open leaderboard →</a>
        </li>
      </ul>
    </article>
    @endcan

    <article class="bento-card lg:col-span-4 overflow-hidden text-[15px] sm:text-base">
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
              <td class="px-6 py-4 text-sm font-medium text-foreground">{{ $a->user->name ?? $a->user->email }}</td>
              <td class="px-6 py-4 text-sm text-muted-foreground">{{ $a->module->title }}</td>
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
