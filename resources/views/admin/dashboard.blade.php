{{-- resources/views/admin/dashboard.blade.php --}}
@extends('admin.layout')

@section('content')
<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
  <div class="card-surface p-6">
    <p class="text-xs font-medium uppercase tracking-[0.2em] text-muted-foreground">Users</p>
    <p class="mt-3 text-3xl font-semibold">{{ $users }}</p>
  </div>
  <div class="card-surface p-6">
    <p class="text-xs font-medium uppercase tracking-[0.2em] text-muted-foreground">Modules</p>
    <p class="mt-3 text-3xl font-semibold">{{ $modules }}</p>
  </div>
  <div class="card-surface p-6">
    <p class="text-xs font-medium uppercase tracking-[0.2em] text-muted-foreground">Questions</p>
    <p class="mt-3 text-3xl font-semibold">{{ $questions }}</p>
  </div>
  <div class="card-surface p-6">
    <p class="text-xs font-medium uppercase tracking-[0.2em] text-muted-foreground">Quiz Attempts</p>
    <p class="mt-3 text-3xl font-semibold">{{ $attempts }}</p>
  </div>
</div>

<div class="card-surface mt-8 overflow-hidden">
  <div class="flex items-center justify-between border-b border-border/60 px-6 py-4">
    <h2 class="text-lg font-semibold">Recent activity</h2>
    <span class="text-xs font-medium uppercase tracking-[0.2em] text-muted-foreground">Last 10 attempts</span>
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
</div>
@endsection
