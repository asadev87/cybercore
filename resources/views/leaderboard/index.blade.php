{{-- resources/views/leaderboard/index.blade.php --}}

@extends('layouts.app')
@section('content')
<section class="space-y-8">
  <header class="flex flex-wrap items-center justify-between gap-4">
    <div>
      <p class="text-xs font-semibold uppercase tracking-[0.24em] text-muted-foreground">Community insight</p>
      <h1 class="text-3xl font-semibold tracking-tight">Leaderboard</h1>
      <p class="text-sm text-muted-foreground">Average scores across all quiz attempts. Updated in real-time.</p>
    </div>
    <a class="btn btn-outline" href="{{ route('learn.index') }}">Back to modules</a>
  </header>

  <div class="card-surface overflow-hidden">
    <div class="border-b border-border/60 px-6 py-4">
      <h2 class="text-lg font-semibold">All-time average score</h2>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-border/60 text-sm">
        <thead class="bg-secondary/60 text-secondary-foreground">
          <tr>
            <th scope="col" class="px-6 py-3 text-left font-semibold">#</th>
            <th scope="col" class="px-6 py-3 text-left font-semibold">Name</th>
            <th scope="col" class="px-6 py-3 text-left font-semibold">Avg score</th>
            <th scope="col" class="px-6 py-3 text-left font-semibold">Attempts</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border/60">
          @foreach($rows as $i => $r)
            <tr class="transition hover:bg-secondary/40">
              <td class="px-6 py-4 font-semibold">{{ $i + 1 }}</td>
              <td class="px-6 py-4 text-sm font-medium text-foreground">{{ $r->user->name ?? 'User '.$r->user_id }}</td>
              <td class="px-6 py-4 text-sm text-muted-foreground">{{ number_format($r->avg_score, 1) }}%</td>
              <td class="px-6 py-4 text-sm text-muted-foreground">{{ $r->attempts }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection
