@extends('admin.layout')

@section('content')
<section class="space-y-8">
  <header class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h1 class="text-2xl font-semibold tracking-tight">Edit Module</h1>
      <p class="text-sm text-muted-foreground">Update details for <span class="font-semibold text-foreground">{{ $module->title }}</span>.</p>
    </div>
    <div class="flex items-center gap-3">
      <a href="{{ route('admin.modules.builder', $module) }}" class="btn btn-primary">Open builder</a>
      <a href="{{ route('admin.modules.index') }}" class="btn btn-outline">Back to modules</a>
    </div>
  </header>

  @if ($errors->any())
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
    <form method="POST" action="{{ route('admin.modules.update', $module) }}" class="grid gap-6">
      @csrf
      @method('PUT')

      <div class="grid gap-2">
        <label for="title" class="input-label">Title</label>
        <input type="text" id="title" name="title" value="{{ old('title', $module->title) }}" required class="input-field" autocomplete="off">
      </div>

      <div class="grid gap-2">
        <label for="slug" class="input-label">Slug</label>
        <input type="text" id="slug" name="slug" value="{{ old('slug', $module->slug) }}" required class="input-field" autocomplete="off">
        <p class="input-hint">Unique identifier used in URLs.</p>
      </div>

      @can('assignLecturer', App\Models\Module::class)
        <div class="grid gap-2">
          <label for="user_id" class="input-label">Assign to lecturer</label>
          <select id="user_id" name="user_id" class="input-field">
            @foreach($lecturers as $lecturer)
              <option value="{{ $lecturer->id }}" {{ old('user_id', $module->user_id) == $lecturer->id ? 'selected' : '' }}>
                {{ $lecturer->name }} ({{ $lecturer->email }})
              </option>
            @endforeach
          </select>
          <p class="input-hint">Visible only to administrators.</p>
        </div>
      @endcan

      <div class="grid gap-2">
        <label for="description" class="input-label">Description</label>
        <textarea id="description" name="description" rows="3" class="input-field">{{ old('description', $module->description) }}</textarea>
      </div>

      <div class="grid gap-2">
        <label for="note" class="input-label">Learner note</label>
        <textarea id="note" name="note" rows="3" class="input-field" placeholder="Optional guidance shown to learners before they begin and during the quiz.">{{ old('note', $module->note) }}</textarea>
        <p class="input-hint">Blank values fall back to the default message for this module.</p>
      </div>

      <div class="grid gap-2">
        <label for="pass_score" class="input-label">Pass score (%)</label>
        <input type="number" id="pass_score" name="pass_score" value="{{ old('pass_score', $module->pass_score) }}" required min="1" max="100" class="input-field">
      </div>

      <label class="flex items-center gap-3 rounded-xl border border-border/60 bg-secondary px-4 py-3 text-sm text-secondary-foreground">
        <input class="h-4 w-4 rounded border-border/60 text-primary focus:ring-ring" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $module->is_active) ? 'checked' : '' }}>
        <span>Active (visible to learners)</span>
      </label>

      <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.modules.index') }}" class="btn btn-muted">Cancel</a>
        <button type="submit" class="btn btn-primary">Update module</button>
      </div>
    </form>
  </div>
</section>
@endsection
