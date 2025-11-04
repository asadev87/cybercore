{{-- resources/views/admin/questions/index.blade.php --}}
@extends('admin.layout')

@section('content')
<section class="space-y-8">
  <header class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
    <div>
      <h1 class="text-2xl font-semibold tracking-tight">Questions</h1>
      <p class="text-sm text-muted-foreground">{{ $module->title }} <span class="text-xs uppercase tracking-[0.28em] text-muted-foreground">/ {{ $module->slug }}</span></p>
    </div>
    <div class="flex flex-wrap items-center gap-3">
      <a href="{{ route('admin.modules.questions.create', $module) }}" class="btn btn-primary text-xs">New question</a>
    </div>
  </header>

  @if ($errors->any())
    <div class="rounded-xl border border-destructive/40 bg-destructive/10 px-4 py-3 text-sm text-destructive">
      {{ $errors->first() }}
    </div>
  @endif

  @if (session('ok'))
    <div class="rounded-xl border border-success/40 bg-success/10 px-4 py-3 text-sm text-success">{{ session('ok') }}</div>
  @endif

  @if (session('import_ok') === true)
    <div class="rounded-xl border border-success/40 bg-success/10 px-4 py-3 text-sm text-success">Import complete.</div>
  @endif

  @if (session('import_failures'))
    <div class="rounded-xl border border-amber-300/40 bg-amber-100/20 px-4 py-3 text-sm text-amber-500 dark:border-amber-400/30 dark:bg-amber-400/10 dark:text-amber-200">
      <p class="font-semibold">Some rows failed validation:</p>
      <ul class="mt-2 list-disc space-y-1 pl-5">
        @foreach (session('import_failures') as $failure)
          <li>Row {{ $failure->row() }}: {{ implode('; ', $failure->errors()) }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card-surface overflow-hidden">
    <div class="flex items-center justify-between border-b border-border/60 px-6 py-4">
      <h2 class="text-lg font-semibold">Question bank</h2>
      <span class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">{{ $questions->total() }} total</span>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-border/60 text-sm">
        <thead class="bg-secondary/60 text-secondary-foreground">
          <tr>
            <th scope="col" class="px-4 py-3 text-left font-semibold">Type</th>
            <th scope="col" class="px-4 py-3 text-left font-semibold">Difficulty</th>
            <th scope="col" class="px-4 py-3 text-left font-semibold">Question</th>
            <th scope="col" class="px-4 py-3 text-left font-semibold">Status</th>
            <th scope="col" class="px-4 py-3 text-right font-semibold">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border/60">
          @forelse ($questions as $q)
            <tr class="transition hover:bg-secondary/40">
              <td class="px-4 py-3 text-xs font-medium uppercase tracking-wide text-muted-foreground">{{ $q->type }}</td>
              <td class="px-4 py-3 text-sm text-muted-foreground">{{ ucfirst($q->difficulty) }}</td>
              <td class="px-4 py-3 text-sm text-foreground">{{ $q->stem }}</td>
              <td class="px-4 py-3 text-sm">
                @if ($q->is_active)
                  <span class="inline-flex items-center rounded-full border border-success/30 bg-success/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-success">Active</span>
                @else
                  <span class="inline-flex items-center rounded-full border border-muted/50 bg-muted px-3 py-1 text-xs font-semibold uppercase tracking-wide text-muted-foreground">Hidden</span>
                @endif
              </td>
              <td class="px-4 py-3">
                <div class="flex flex-wrap items-center justify-end gap-2">
                  <a class="btn btn-muted text-xs" href="{{ route('admin.modules.questions.edit', [$module, $q]) }}">Edit</a>
                  <form method="POST" action="{{ route('admin.modules.questions.destroy', [$module, $q]) }}" onsubmit="return confirm('Delete question?')" class="inline-flex">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-destructive text-xs">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-6 py-8 text-center text-sm text-muted-foreground">No questions yet.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="flex justify-end">
    {{ $questions->links() }}
  </div>
</section>
@endsection
