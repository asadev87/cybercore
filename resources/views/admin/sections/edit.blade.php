{{-- resources/views/admin/sections/edit.blade.php --}}

@extends('admin.layout')

@section('content')
@php
  $isEditing = true;
@endphp
<section class="space-y-8">
  <header class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h1 class="text-2xl font-semibold tracking-tight">Edit section</h1>
      <p class="text-sm text-muted-foreground">{{ $module->title }}</p>
    </div>
    <a href="{{ route('admin.modules.sections.index', $module) }}" class="btn btn-outline">Back to sections</a>
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
    <form method="POST" action="{{ route('admin.modules.sections.update', [$module, $section]) }}" class="grid gap-6">
      @csrf
      @method('PUT')

      <div class="grid gap-6 md:grid-cols-2">
        <div class="grid gap-2">
          <label for="title" class="input-label">Title</label>
          <input id="title" name="title" value="{{ old('title', $section->title ?? '') }}" required class="input-field" autocomplete="off">
        </div>
        <div class="grid gap-2">
          <label for="slug" class="input-label">Slug</label>
          <input id="slug" name="slug" value="{{ old('slug', $section->slug ?? '') }}" required class="input-field" autocomplete="off">
        </div>
      </div>

      <div class="grid gap-2">
        <label for="description" class="input-label">Description</label>
        <textarea id="description" name="description" rows="3" class="input-field">{{ old('description', $section->description ?? '') }}</textarea>
      </div>

      <div class="grid gap-6 md:grid-cols-[minmax(0,220px)_1fr]">
        <div class="grid gap-2">
          <label for="order" class="input-label">Display order</label>
          <input type="number" id="order" name="order" min="1" value="{{ old('order', $section->order ?? 1) }}" class="input-field">
          <p class="input-hint">Controls the sequence sections appear to learners.</p>
        </div>
        <label class="mt-6 flex items-center gap-3 rounded-xl border border-border/60 bg-secondary px-4 py-3 text-sm text-secondary-foreground md:mt-0">
          <input class="h-4 w-4 rounded border-border/60 text-primary focus:ring-ring" type="checkbox" name="is_active" value="1" {{ old('is_active', $section->is_active ?? true) ? 'checked' : '' }}>
          <span>Active (visible to learners)</span>
        </label>
      </div>

      <div class="flex items-center justify-end gap-3">
        <a class="btn btn-muted" href="{{ route('admin.modules.sections.index', $module) }}">Cancel</a>
        <button class="btn btn-primary">Save changes</button>
      </div>
    </form>
  </div>
</section>
@endsection
