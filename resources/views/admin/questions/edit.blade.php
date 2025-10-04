{{-- resources/views/admin/questions/edit.blade.php --}}

@extends('admin.layout')

@section('content')
<section class="space-y-8">
  <header class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h1 class="text-2xl font-semibold tracking-tight">Edit Question</h1>
      <p class="text-sm text-muted-foreground">Updating <span class="font-semibold text-foreground">{{ $module->title }}</span>.</p>
    </div>
    <a href="{{ route('admin.modules.questions.index', $module) }}" class="btn btn-outline">Back to questions</a>
  </header>

  @if($errors->any())
    <div class="rounded-xl border border-destructive/40 bg-destructive/10 px-4 py-3 text-sm text-destructive">
      <p class="font-semibold">Please fix the following issues:</p>
      <ul class="mt-2 list-disc space-y-1 pl-5">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card-surface">
    <form method="POST" action="{{ route('admin.modules.questions.update', [$module, $question]) }}" class="grid gap-6">
      @include('admin.questions._form', ['question' => $question])
    </form>
  </div>
</section>
@endsection
