{{-- resources/views/admin/reports/index.blade.php --}}

@extends('admin.layout')

@section('content')
<section class="space-y-8">
  <header class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
    <div>
      <h1 class="text-2xl font-semibold tracking-tight">Analytics &amp; reports</h1>
      <p class="text-sm text-muted-foreground">Filter and export learner performance across modules.</p>
    </div>
    <div class="flex flex-wrap items-center gap-3">
      <a class="btn btn-outline text-xs" href="{{ route('admin.reports.export.excel', request()->only('from','to','module_id')) }}">Export Excel</a>
      <a class="btn btn-outline text-xs" href="{{ route('admin.reports.export.pdf', request()->only('from','to','module_id')) }}">Export PDF</a>
    </div>
  </header>

  <form method="GET" class="card-surface grid gap-6 md:grid-cols-4">
    <div class="grid gap-2">
      <label for="from" class="input-label">From</label>
      <input type="date" id="from" name="from" value="{{ optional($from)->toDateString() }}" class="input-field">
    </div>
    <div class="grid gap-2">
      <label for="to" class="input-label">To</label>
      <input type="date" id="to" name="to" value="{{ optional($to)->toDateString() }}" class="input-field">
    </div>
    <div class="grid gap-2 md:col-span-2">
      <label for="module_id" class="input-label">Module</label>
      <select id="module_id" name="module_id" class="input-field">
        <option value="">All modules</option>
        @foreach($modules as $m)
          <option value="{{ $m->id }}" @selected($moduleId == $m->id)>{{ $m->title }}</option>
        @endforeach
      </select>
    </div>
    <div class="md:col-span-4 flex justify-end">
      <button class="btn btn-primary">Apply filters</button>
    </div>
  </form>

  <div class="grid gap-4 md:grid-cols-3">
    <div class="card-surface">
      <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Attempts</p>
      <p class="mt-3 text-3xl font-semibold">{{ number_format($summary->attempts ?? 0) }}</p>
    </div>
    <div class="card-surface">
      <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Average score</p>
      <p class="mt-3 text-3xl font-semibold">{{ isset($summary->avg_score) ? number_format($summary->avg_score,1).'%' : '—' }}</p>
    </div>
    <div class="card-surface">
      <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Passes</p>
      <p class="mt-3 text-3xl font-semibold">{{ number_format($summary->passes ?? 0) }}</p>
    </div>
  </div>

  <div class="card-surface overflow-hidden">
    <div class="border-b border-border/60 px-6 py-4">
      <h2 class="text-lg font-semibold">Ranking by module</h2>
      <p class="text-sm text-muted-foreground">Sorted by average score</p>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-border/60 text-sm">
        <thead class="bg-secondary/60 text-secondary-foreground">
          <tr>
            <th class="px-4 py-3 font-semibold">#</th>
            <th class="px-4 py-3 font-semibold">Module</th>
            <th class="px-4 py-3 font-semibold">Attempts</th>
            <th class="px-4 py-3 font-semibold">Passes</th>
            <th class="px-4 py-3 font-semibold">Avg score</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border/60">
          @forelse($ranking as $i => $r)
            <tr class="transition hover:bg-secondary/40">
              <td class="px-4 py-3 text-sm font-semibold text-muted-foreground">{{ $i + 1 }}</td>
              <td class="px-4 py-3 text-sm text-foreground">{{ $r->title }}</td>
              <td class="px-4 py-3 text-sm text-muted-foreground">{{ $r->attempts }}</td>
              <td class="px-4 py-3 text-sm text-muted-foreground">{{ $r->passes }}</td>
              <td class="px-4 py-3 text-sm text-muted-foreground">{{ number_format($r->avg_score, 1) }}%</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-6 py-8 text-center text-sm text-muted-foreground">No data.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="card-surface overflow-hidden">
    <div class="border-b border-border/60 px-6 py-4">
      <h2 class="text-lg font-semibold">Recent attempts</h2>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-border/60 text-sm">
        <thead class="bg-secondary/60 text-secondary-foreground">
          <tr>
            <th class="px-4 py-3 font-semibold">When</th>
            <th class="px-4 py-3 font-semibold">User</th>
            <th class="px-4 py-3 font-semibold">Module</th>
            <th class="px-4 py-3 font-semibold">Score</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border/60">
          @forelse($rows as $a)
            <tr class="transition hover:bg-secondary/40">
              <td class="px-4 py-3 text-sm text-muted-foreground">{{ $a->completed_at?->format('Y-m-d H:i') }}</td>
              <td class="px-4 py-3 text-sm text-foreground">{{ $a->user->name ?? $a->user->email }}</td>
              <td class="px-4 py-3 text-sm text-muted-foreground">{{ $a->module->title }}</td>
              <td class="px-4 py-3 text-sm text-muted-foreground">{{ $a->score }}%</td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-6 py-8 text-center text-sm text-muted-foreground">No attempts in range.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection
