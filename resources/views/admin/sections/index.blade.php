{{-- resources/views/admin/sections/index.blade.php --}}

@extends('admin.layout')

@section('content')
<section class="space-y-8">
  <header class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h1 class="text-2xl font-semibold tracking-tight">Sections</h1>
      <p class="text-sm text-muted-foreground">{{ $module->title }} <span class="text-xs uppercase tracking-[0.28em] text-muted-foreground">/ {{ $module->slug }}</span></p>
    </div>
    <a href="{{ route('admin.modules.sections.create', $module) }}" class="btn btn-primary">New section</a>
  </header>

  @if(session('ok'))
    <div class="rounded-xl border border-success/40 bg-success/10 px-4 py-3 text-sm text-success">{{ session('ok') }}</div>
  @endif

  <div class="card-surface overflow-hidden">
    <div class="flex items-center justify-between border-b border-border/60 px-6 py-4">
      <h2 class="text-lg font-semibold">Section order</h2>
      <span class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">{{ $sections->total() }} total</span>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-border/60 text-sm">
        <thead class="bg-secondary/60 text-secondary-foreground">
          <tr>
            <th scope="col" class="px-4 py-3 font-semibold">#</th>
            <th scope="col" class="px-4 py-3 font-semibold">Title</th>
            <th scope="col" class="px-4 py-3 font-semibold">Status</th>
            <th scope="col" class="px-4 py-3 text-right font-semibold">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border/60">
          @forelse($sections as $s)
            <tr class="transition hover:bg-secondary/40">
              <td class="px-4 py-3 text-sm font-semibold text-muted-foreground">{{ $s->order }}</td>
              <td class="px-4 py-3 text-sm text-foreground">{{ $s->title }}</td>
              <td class="px-4 py-3 text-sm">
                @if($s->is_active)
                  <span class="inline-flex items-center rounded-full border border-success/30 bg-success/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-success">Active</span>
                @else
                  <span class="inline-flex items-center rounded-full border border-muted/50 bg-muted px-3 py-1 text-xs font-semibold uppercase tracking-wide text-muted-foreground">Hidden</span>
                @endif
              </td>
              <td class="px-4 py-3">
                <div class="flex items-center justify-end gap-2">
                  <a class="btn btn-muted text-xs" href="{{ route('admin.modules.sections.edit', [$module, $s]) }}">Edit</a>
                  <form method="POST" action="{{ route('admin.modules.sections.destroy', [$module, $s]) }}" onsubmit="return confirm('Delete section?')" class="inline-flex">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-destructive text-xs">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-6 py-8 text-center text-sm text-muted-foreground">No sections yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="flex justify-end">
    {{ $sections->links() }}
  </div>
</section>
@endsection
