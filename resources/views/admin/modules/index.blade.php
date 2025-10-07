@extends('admin.layout')

@section('content')
<section class="space-y-8">
  <header class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h1 class="text-2xl font-semibold tracking-tight">Modules</h1>
      <p class="text-sm text-muted-foreground">Manage learning content, ownership, and visibility across the platform.</p>
    </div>
    @can('create', App\Models\Module::class)
      <a href="{{ route('admin.modules.create') }}" class="btn btn-primary">New Module</a>
    @endcan
  </header>

  @if(session('ok'))
    <div class="rounded-xl border border-success/40 bg-success/10 px-4 py-3 text-sm text-success">
      {{ session('ok') }}
    </div>
  @endif

  <div class="card-surface overflow-hidden">
    <div class="flex items-center justify-between border-b border-border/60 px-6 py-4">
      <h2 class="text-lg font-semibold">Module catalogue</h2>
      <span class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">{{ $modules->total() }} total</span>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-border/60 text-left text-sm">
        <thead class="bg-secondary/60 text-secondary-foreground">
          <tr>
            <th scope="col" class="px-6 py-3 font-semibold">Title</th>
            <th scope="col" class="px-6 py-3 font-semibold">Owner</th>
            <th scope="col" class="px-6 py-3 font-semibold">Pass Score</th>
            <th scope="col" class="px-6 py-3 font-semibold">Status</th>
            <th scope="col" class="px-6 py-3 text-right font-semibold">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border/60">
        @forelse($modules as $m)
          <tr class="transition hover:bg-secondary/40">
            <td class="px-6 py-4 font-medium text-foreground">{{ $m->title }}</td>
            <td class="px-6 py-4 text-sm text-muted-foreground">{{ $m->user->name ?? 'N/A' }}</td>
            <td class="px-6 py-4 text-sm text-muted-foreground">{{ $m->pass_score ?? 70 }}%</td>
            <td class="px-6 py-4 text-sm">
              @if($m->is_active)
                <span class="inline-flex items-center rounded-full border border-success/30 bg-success/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-success">Active</span>
              @else
                <span class="inline-flex items-center rounded-full border border-muted/50 bg-muted px-3 py-1 text-xs font-semibold uppercase tracking-wide text-muted-foreground">Hidden</span>
              @endif
            </td>
            <td class="px-6 py-4">
              <div class="flex flex-wrap items-center justify-end gap-2">
                @can('update', $m)
                  <a class="btn btn-primary text-xs" href="{{ route('admin.modules.builder', $m) }}">Builder</a>
                  <a class="btn btn-muted text-xs" href="{{ route('admin.modules.edit', $m) }}">Edit</a>
                  <a class="btn btn-outline text-xs" href="{{ route('admin.modules.sections.index', $m) }}">Sections</a>
                  <a class="btn btn-outline text-xs" href="{{ route('admin.modules.questions.index', $m) }}">Questions</a>
                @endcan

                @can('delete', $m)
                  <form method="POST" action="{{ route('admin.modules.destroy', $m) }}" onsubmit="return confirm('Delete module?')" class="inline-flex">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-destructive text-xs">Delete</button>
                  </form>
                @endcan
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-6 py-8 text-center text-sm text-muted-foreground">No modules yet.</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="flex justify-end">
    {{ $modules->links() }}
  </div>
</section>
@endsection
