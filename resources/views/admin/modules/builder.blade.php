@extends('admin.layout')

@section('content')
<section class="space-y-8">
  <header class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h1 class="text-2xl font-semibold tracking-tight">Module builder</h1>
      <p class="text-sm text-muted-foreground">Curate content, questions, and visibility for <span class="font-semibold text-foreground">{{ $module->title }}</span>.</p>
    </div>
    <div class="flex flex-wrap items-center gap-3">
      <a href="{{ route('admin.modules.index') }}" class="btn btn-muted">Back to catalogue</a>
      <a href="{{ route('learn.show', $module) }}" class="btn btn-outline">Preview in Learn</a>
    </div>
  </header>

  @if (session('ok'))
    <div class="rounded-xl border border-success/40 bg-success/10 px-4 py-3 text-sm text-success">
      {{ session('ok') }}
    </div>
  @endif

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

  <div class="grid gap-6 lg:grid-cols-[1.1fr,0.9fr]">
    <div class="space-y-6">
      <div class="card-surface">
        <div class="flex items-center justify-between border-b border-border/60 pb-4">
          <div>
            <h2 class="text-lg font-semibold">Module details</h2>
            <p class="text-xs text-muted-foreground">Update title, slug, description, and passing rules.</p>
          </div>
        </div>

        <form method="POST" action="{{ route('admin.modules.update', $module) }}" class="mt-6 grid gap-6">
          @csrf
          @method('PUT')
          <input type="hidden" name="redirect_to" value="builder">

          <div class="grid gap-2">
            <label for="title" class="input-label">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $module->title) }}" required class="input-field" autocomplete="off">
          </div>

          <div class="grid gap-2">
            <label for="slug" class="input-label">Slug</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $module->slug) }}" required class="input-field" autocomplete="off">
            <p class="input-hint">Used in URLs and share links.</p>
          </div>

          @can('assignLecturer', App\Models\Module::class)
            <div class="grid gap-2">
              <label for="user_id" class="input-label">Lecturer owner</label>
              <select id="user_id" name="user_id" class="input-field">
                <option value="">Unassigned</option>
                @foreach($lecturers as $lecturer)
                  <option value="{{ $lecturer->id }}" {{ old('user_id', $module->user_id) == $lecturer->id ? 'selected' : '' }}>
                    {{ $lecturer->name }} ({{ $lecturer->email }})
                  </option>
                @endforeach
              </select>
              <p class="input-hint">Only administrators can change ownership.</p>
            </div>
          @endcan

          <div class="grid gap-2">
            <label for="description" class="input-label">Description</label>
            <textarea id="description" name="description" rows="3" class="input-field">{{ old('description', $module->description) }}</textarea>
          </div>

          <div class="grid gap-2">
            <label for="pass_score" class="input-label">Pass score (%)</label>
            <input type="number" id="pass_score" name="pass_score" value="{{ old('pass_score', $module->pass_score) }}" required min="1" max="100" class="input-field">
          </div>

          <label class="flex items-center gap-3 rounded-xl border border-border/60 bg-secondary px-4 py-3 text-sm text-secondary-foreground">
            <input class="h-4 w-4 rounded border-border/60 text-primary focus:ring-ring" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $module->is_active) ? 'checked' : '' }}>
            <span>Active &mdash; visible to students in Learn</span>
          </label>

          <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.modules.index') }}" class="btn btn-muted">Cancel</a>
            <button type="submit" class="btn btn-primary">Save module</button>
          </div>
        </form>
      </div>

      <div class="card-surface">
        <div class="flex items-center justify-between border-b border-border/60 pb-4">
          <h2 class="text-lg font-semibold">Questions</h2>
          <a href="{{ route('admin.modules.questions.create', $module) }}" class="btn btn-outline text-xs">Full question editor</a>
        </div>

        <div class="mt-4 space-y-4">
          @forelse($questions as $question)
            <article class="rounded-2xl border border-border/60 bg-secondary/40 p-4 text-sm shadow-sm">
              <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                  <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">{{ strtoupper($question->type) }} • {{ ucfirst($question->difficulty ?? 'medium') }}</p>
                  <h3 class="mt-2 font-medium text-foreground">{{ \Illuminate\Support\Str::limit($question->stem, 160) }}</h3>
                  @if($question->section)
                    <p class="mt-2 text-xs text-muted-foreground">Section: {{ $question->section->title }}</p>
                  @endif
                </div>
                <div class="flex flex-col items-end gap-2 text-xs">
                  <span class="inline-flex items-center rounded-full border {{ $question->is_active ? 'border-success/30 bg-success/10 text-success' : 'border-muted/50 bg-muted text-muted-foreground' }} px-3 py-1 font-semibold uppercase tracking-wide">{{ $question->is_active ? 'Active' : 'Hidden' }}</span>
                  <div class="flex items-center gap-2">
                    <a href="{{ route('admin.modules.questions.edit', [$module, $question]) }}" class="btn btn-muted text-xs">Edit</a>
                    <form action="{{ route('admin.modules.questions.destroy', [$module, $question]) }}" method="POST" onsubmit="return confirm('Delete this question?')">
                      @csrf
                      @method('DELETE')
                      <input type="hidden" name="redirect_to" value="builder">
                      <button class="btn btn-destructive text-xs">Delete</button>
                    </form>
                  </div>
                </div>
              </div>
            </article>
          @empty
            <div class="rounded-2xl border border-dashed border-border/60 p-6 text-center text-sm text-muted-foreground">
              No questions yet. Use the quick add form or the full editor to start building assessments.
            </div>
          @endforelse
        </div>
      </div>
    </div>

    <div class="space-y-6">
      <div class="card-surface">
        <div class="border-b border-border/60 pb-4">
          <h2 class="text-lg font-semibold">Quick add section</h2>
          <p class="mt-1 text-xs text-muted-foreground">Create a module section with ordering and optional description.</p>
        </div>

        <form method="POST" action="{{ route('admin.modules.sections.store', $module) }}" class="mt-6 grid gap-5">
          @csrf
          <input type="hidden" name="redirect_to" value="builder">

          <div class="grid gap-2">
            <label for="section_title" class="input-label">Title</label>
            <input type="text" id="section_title" name="title" value="{{ old('title') }}" required class="input-field">
          </div>

          <div class="grid gap-2">
            <label for="section_slug" class="input-label">Slug</label>
            <input type="text" id="section_slug" name="slug" value="{{ old('slug') }}" required class="input-field" autocomplete="off">
          </div>

          <div class="grid gap-2">
            <label for="section_order" class="input-label">Order</label>
            <input type="number" id="section_order" name="order" value="{{ old('order') }}" min="1" class="input-field" placeholder="Auto">
          </div>

          <div class="grid gap-2">
            <label for="section_description" class="input-label">Description</label>
            <textarea id="section_description" name="description" rows="2" class="input-field" placeholder="Optional"></textarea>
          </div>

          <label class="flex items-center gap-3 rounded-xl border border-border/60 bg-secondary px-4 py-3 text-sm text-secondary-foreground">
            <input class="h-4 w-4 rounded border-border/60 text-primary focus:ring-ring" type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
            <span>Active section</span>
          </label>

          <button type="submit" class="btn btn-primary">Add section</button>
        </form>
      </div>

      <div class="card-surface">
        <div class="border-b border-border/60 pb-4">
          <h2 class="text-lg font-semibold">Quick add question</h2>
          <p class="mt-1 text-xs text-muted-foreground">Draft an assessment item. For complex logic, use the full editor.</p>
        </div>

        <form method="POST" action="{{ route('admin.modules.questions.store', $module) }}" class="mt-6 grid gap-5">
          @csrf
          <input type="hidden" name="redirect_to" value="builder">

          <div class="grid gap-2">
            <label for="question_type" class="input-label">Type</label>
            <select id="question_type" name="type" class="input-field" required>
              <option value="mcq" @selected(old('type') === 'mcq')>Multiple choice</option>
              <option value="truefalse" @selected(old('type') === 'truefalse')>True / False</option>
              <option value="fib" @selected(old('type') === 'fib')>Fill in the blank</option>
            </select>
          </div>

          <div class="grid gap-2">
            <label for="question_difficulty" class="input-label">Difficulty</label>
            <select id="question_difficulty" name="difficulty" class="input-field">
              @foreach(['easy','medium','hard'] as $level)
                <option value="{{ $level }}" @selected(old('difficulty', 'medium') === $level)>{{ ucfirst($level) }}</option>
              @endforeach
            </select>
          </div>

          <div class="grid gap-2">
            <label for="question_section" class="input-label">Link to section</label>
            <select id="question_section" name="section_id" class="input-field">
              <option value="">No section</option>
              @foreach($module->sections as $s)
                <option value="{{ $s->id }}" @selected(old('section_id') == $s->id)>{{ $s->order }} — {{ $s->title }}</option>
              @endforeach
            </select>
          </div>

          <div class="grid gap-2">
            <label for="question_stem" class="input-label">Question text</label>
            <textarea id="question_stem" name="stem" rows="3" class="input-field" required>{{ old('stem') }}</textarea>
          </div>

          <div class="grid gap-2">
            <label for="question_choices" class="input-label">Choices</label>
            <textarea id="question_choices" name="choices" rows="3" class="input-field" placeholder="One option per line">{{ old('choices') }}</textarea>
            <p class="input-hint">Required for multiple choice. Ignored for other types.</p>
          </div>

          <div class="grid gap-2">
            <label for="question_answer" class="input-label">Correct answer(s)</label>
            <textarea id="question_answer" name="answer" rows="2" class="input-field" placeholder="Accepted answer(s)">{{ old('answer') }}</textarea>
            <p class="input-hint">Separate multiple answers with new lines. True/False accepts <code>true</code> or <code>false</code>.</p>
          </div>

          <label class="flex items-center gap-3 rounded-xl border border-border/60 bg-secondary px-4 py-3 text-sm text-secondary-foreground">
            <input class="h-4 w-4 rounded border-border/60 text-primary focus:ring-ring" type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
            <span>Active question</span>
          </label>

          <button type="submit" class="btn btn-primary">Add question</button>
        </form>
      </div>

      <div class="card-surface">
        <div class="border-b border-border/60 pb-4">
          <h2 class="text-lg font-semibold">Sections overview</h2>
        </div>
        <div class="mt-4 space-y-3">
          @forelse($module->sections as $section)
            <div class="flex flex-wrap items-center justify-between gap-3 rounded-xl border border-border/60 bg-secondary/40 px-4 py-3 text-sm">
              <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Order {{ $section->order }}</p>
                <p class="font-medium text-foreground">{{ $section->title }}</p>
              </div>
              <div class="flex items-center gap-2 text-xs">
                <span class="inline-flex items-center rounded-full border {{ $section->is_active ? 'border-success/30 bg-success/10 text-success' : 'border-muted/50 bg-muted text-muted-foreground' }} px-3 py-1 font-semibold uppercase tracking-wide">{{ $section->is_active ? 'Active' : 'Hidden' }}</span>
                <a href="{{ route('admin.modules.sections.edit', [$module, $section]) }}" class="btn btn-muted text-xs">Edit</a>
              </div>
            </div>
          @empty
            <div class="rounded-2xl border border-dashed border-border/60 p-6 text-center text-sm text-muted-foreground">
              No sections defined yet.
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
