@extends('admin.layout')

@section('content')
<section class="space-y-10 text-base">
  <header class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
      <h1 class="text-3xl font-semibold tracking-tight">Module builder</h1>
      <p class="text-lg text-muted-foreground">Curate content, questions, and visibility for <span class="font-semibold text-foreground">{{ $module->title }}</span>.</p>
    </div>
    <div class="flex flex-wrap items-center gap-3">
      <a href="{{ route('admin.modules.index') }}" class="btn btn-muted">Back to catalogue</a>
      <a href="{{ route('learn.show', $module) }}" class="btn btn-outline">Preview in Learn</a>
    </div>
  </header>

  @if (session('ok'))
    <div class="rounded-xl border border-success/40 bg-success/10 px-4 py-3 text-base text-success">
      {{ session('ok') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="rounded-xl border border-destructive/40 bg-destructive/10 px-4 py-3 text-base text-destructive">
      <p class="font-semibold">Please fix the following issues:</p>
      <ul class="mt-2 list-disc space-y-1 pl-5">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="grid gap-8 xl:grid-cols-[minmax(0,2fr)_minmax(0,1fr)]">
    <div class="space-y-8">
      <div class="card-surface">
        <div class="flex items-center justify-between border-b border-border/60 pb-5">
          <div>
            <h2 class="text-xl font-semibold">Module details</h2>
            <p class="text-sm text-muted-foreground">Update title, slug, description, and passing rules.</p>
          </div>
        </div>

        <form method="POST" action="{{ route('admin.modules.update', $module) }}" class="mt-6 grid gap-6">
          @csrf
          @method('PUT')
          <input type="hidden" name="redirect_to" value="builder">

          @php
            $defaultModuleNote = config('module_notes.defaults')[$module->slug] ?? null;
            $moduleNoteTemplate = <<<TXT
TL;DR: Summarize the single biggest takeaway in one sentence.

Core Concept Explanation:
[Describe the core security concept in two or three concise sentences.]

Contextual Narrative:
[Share a short story or scenario that helps the learner relate to the concept.]

Real-World Checklist:
- ✅ Do add two or three concrete actions teammates should take.
- ❌ Don't repeat jargon or fluff—explain what to avoid clearly.
TXT;
            $initialModuleNote = old('note', $module->note ?? $defaultModuleNote ?? '');
          @endphp

          <div class="grid gap-3">
            <label for="title" class="input-label">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $module->title) }}" required class="input-field" autocomplete="off">
          </div>

          <div class="grid gap-3">
            <label for="slug" class="input-label">Slug</label>
            <input type="text" id="slug" name="slug" value="{{ old('slug', $module->slug) }}" required class="input-field" autocomplete="off">
            <p class="input-hint">Used in URLs and share links.</p>
          </div>

          @can('assignLecturer', App\Models\Module::class)
            <div class="grid gap-3">
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

          <div class="grid gap-3">
            <label for="description" class="input-label">Description</label>
            <textarea id="description" name="description" rows="3" class="input-field">{{ old('description', $module->description) }}</textarea>
          </div>

          <div
            x-data="moduleNoteEditor({
              initial: @json($initialModuleNote),
              template: @json(trim($moduleNoteTemplate)),
              defaultNote: @json($defaultModuleNote)
            })"
            class="rounded-2xl border border-border/70 bg-secondary/40 p-5"
          >
            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
              <div>
                <h3 class="text-base font-semibold uppercase tracking-[0.24em] text-muted-foreground">Learners note</h3>
                <p class="mt-2 text-sm text-muted-foreground/80">Provide guidance that appears before learners start and while they progress through the quiz.</p>
              </div>
              <div class="flex flex-wrap items-center gap-3 text-sm font-semibold uppercase tracking-wide text-muted-foreground/70">
                <span>Characters: <span x-text="characterCount"></span></span>
                <button type="button" class="btn btn-muted text-sm" @click="applyDefault()" x-show="hasDefault" x-cloak>Use saved default</button>
                <button type="button" class="btn btn-outline text-sm" @click="applyTemplate()">Load template</button>
              </div>
            </div>

            <div class="mt-5 grid gap-5 lg:grid-cols-2">
              <div class="space-y-1">
                <textarea
                  name="note"
                  x-model="note"
                  x-ref="noteInput"
                  rows="14"
                  class="input-field h-full min-h-[18rem] resize-y"
                  placeholder="Start typing or load a template…"
                ></textarea>

                <div class="flex flex-wrap gap-3 text-sm mt-4 mb-2">
                  <span class="w-full text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Section templates</span>
                  <button type="button" class="inline-flex items-center rounded-full border border-border/60 bg-secondary/70 px-3 py-1.5 font-medium text-muted-foreground transition hover:border-primary hover:bg-primary/10 hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary/20" @click="appendSection('tldr')">+ TL;DR</button>
                  <button type="button" class="inline-flex items-center rounded-full border border-border/60 bg-secondary/70 px-3 py-1.5 font-medium text-muted-foreground transition hover:border-primary hover:bg-primary/10 hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary/20" @click="appendSection('core')">+ Core concept</button>
                  <button type="button" class="inline-flex items-center rounded-full border border-border/60 bg-secondary/70 px-3 py-1.5 font-medium text-muted-foreground transition hover:border-primary hover:bg-primary/10 hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary/20" @click="appendSection('narrative')">+ Narrative</button>
                  <button type="button" class="inline-flex items-center rounded-full border border-border/60 bg-secondary/70 px-3 py-1.5 font-medium text-muted-foreground transition hover:border-primary hover:bg-primary/10 hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary/20" @click="appendSection('checklist')">+ Checklist</button>
                </div>
              </div>

              <div class="flex flex-col gap-4">
                <div class="rounded-2xl border border-border/70 bg-background/80 p-5 shadow-sm">
                  <p class="text-sm font-semibold uppercase tracking-[0.24em] text-muted-foreground">Live preview</p>
                  <div class="mt-3 space-y-3 text-base leading-relaxed text-foreground" x-html="rendered"></div>
                </div>
                <p class="text-sm text-muted-foreground/80">Preview renders plain text with lightweight formatting. Links and Markdown are stripped for safety.</p>
              </div>
            </div>
          </div>

          <div class="grid gap-3">
            <label for="pass_score" class="input-label">Pass score (%)</label>
            <input type="number" id="pass_score" name="pass_score" value="{{ old('pass_score', $module->pass_score) }}" required min="1" max="100" class="input-field">
          </div>

          <label class="flex items-center gap-4 rounded-xl border border-border/60 bg-secondary px-5 py-4 text-base text-secondary-foreground">
            <input class="h-4 w-4 rounded border-border/60 text-primary focus:ring-ring" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $module->is_active) ? 'checked' : '' }}>
            <span>Active &mdash; visible to students in Learn</span>
          </label>

          <div class="flex items-center justify-end gap-4">
            <a href="{{ route('admin.modules.index') }}" class="btn btn-muted">Cancel</a>
            <button type="submit" class="btn btn-primary">Save module</button>
          </div>
        </form>
      </div>

      <div class="card-surface">
        <div class="flex items-center justify-between border-b border-border/60 pb-5">
          <h2 class="text-xl font-semibold">Sections overview</h2>
          <a href="{{ route('admin.modules.sections.index', $module) }}" class="btn btn-outline text-sm">Manage sections</a>
        </div>
        <div class="mt-5 space-y-4">
          @forelse($module->sections as $section)
            <div class="flex flex-wrap items-center justify-between gap-4 rounded-xl border border-border/60 bg-secondary/30 px-5 py-4 text-base">
              <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Order {{ $section->order }}</p>
                <p class="mt-1 font-semibold text-foreground">{{ $section->title }}</p>
                @if($section->description)
                  <p class="mt-1 text-sm text-muted-foreground">{{ \Illuminate\Support\Str::limit($section->description, 140) }}</p>
                @endif
              </div>
              <div class="flex items-center gap-3 text-sm">
                <span class="inline-flex items-center rounded-full border {{ $section->is_active ? 'border-success/30 bg-success/10 text-success' : 'border-muted/50 bg-muted text-muted-foreground' }} px-3 py-1 font-semibold uppercase tracking-wide">{{ $section->is_active ? 'Active' : 'Hidden' }}</span>
                <a href="{{ route('admin.modules.sections.edit', [$module, $section]) }}" class="btn btn-muted text-sm">Edit</a>
              </div>
            </div>
          @empty
            <div class="rounded-2xl border border-dashed border-border/60 p-6 text-center text-base text-muted-foreground">
              No sections defined yet. Use the quick add form to start building your outline.
            </div>
          @endforelse
        </div>
      </div>

      <div class="card-surface">
        <div class="flex items-center justify-between border-b border-border/60 pb-5">
          <h2 class="text-xl font-semibold">Question bank</h2>
          <a href="{{ route('admin.modules.questions.create', $module) }}" class="btn btn-outline text-sm">Full question editor</a>
        </div>

        <div class="mt-5 space-y-4">
          @forelse($questions as $question)
            <article class="rounded-2xl border border-border/60 bg-secondary/40 p-5 text-base shadow-sm">
              <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                  <p class="text-sm font-semibold uppercase tracking-[0.28em] text-muted-foreground">{{ strtoupper($question->type) }} • {{ ucfirst($question->difficulty ?? 'medium') }}</p>
                  <h3 class="mt-2 font-medium text-foreground">{{ \Illuminate\Support\Str::limit($question->stem, 160) }}</h3>
                  @if($question->section)
                    <p class="mt-2 text-sm text-muted-foreground">Section: {{ $question->section->title }}</p>
                  @endif
                </div>
                <div class="flex flex-col items-end gap-2 text-sm">
                  <span class="inline-flex items-center rounded-full border {{ $question->is_active ? 'border-success/30 bg-success/10 text-success' : 'border-muted/50 bg-muted text-muted-foreground' }} px-3 py-1 font-semibold uppercase tracking-wide">{{ $question->is_active ? 'Active' : 'Hidden' }}</span>
                  <div class="flex items-center gap-2">
                    <a href="{{ route('admin.modules.questions.edit', [$module, $question]) }}" class="btn btn-muted text-sm">Edit</a>
                    <form action="{{ route('admin.modules.questions.destroy', [$module, $question]) }}" method="POST" onsubmit="return confirm('Delete this question?')">
                      @csrf
                      @method('DELETE')
                      <input type="hidden" name="redirect_to" value="builder">
                      <button class="btn btn-destructive text-sm">Delete</button>
                    </form>
                  </div>
                </div>
              </div>
            </article>
          @empty
            <div class="rounded-2xl border border-dashed border-border/60 p-6 text-center text-base text-muted-foreground">
              No questions yet. Use the quick add form or the full editor to start building assessments.
            </div>
          @endforelse
        </div>
      </div>
    </div>

    <div class="grid gap-8 lg:grid-cols-2">
      <div class="card-surface h-full">
        <div class="border-b border-border/60 pb-5">
          <h2 class="text-xl font-semibold">Quick add section</h2>
          <p class="mt-2 text-sm text-muted-foreground">Create a module section with ordering and optional description.</p>
        </div>

        <form method="POST" action="{{ route('admin.modules.sections.store', $module) }}" class="mt-6 grid gap-6">
          @csrf
          <input type="hidden" name="redirect_to" value="builder">

          <div class="grid gap-3">
            <label for="section_title" class="input-label">Title</label>
            <input type="text" id="section_title" name="title" value="{{ old('title') }}" required class="input-field">
          </div>

          <div class="grid gap-3">
            <label for="section_slug" class="input-label">Slug</label>
            <input type="text" id="section_slug" name="slug" value="{{ old('slug') }}" required class="input-field" autocomplete="off">
          </div>

          <div class="grid gap-3">
            <label for="section_order" class="input-label">Order</label>
            <input type="number" id="section_order" name="order" value="{{ old('order') }}" min="1" class="input-field" placeholder="Auto">
          </div>

          <div class="grid gap-3">
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

      <div class="card-surface h-full">
        <div class="border-b border-border/60 pb-5">
          <h2 class="text-xl font-semibold">Quick add question</h2>
          <p class="mt-2 text-sm text-muted-foreground">Draft an assessment item. For complex logic, use the full editor.</p>
        </div>

        <form method="POST" action="{{ route('admin.modules.questions.store', $module) }}" class="mt-6 grid gap-6">
          @csrf
          <input type="hidden" name="redirect_to" value="builder">

          <div class="grid gap-3">
            <label for="question_type" class="input-label">Type</label>
            <select id="question_type" name="type" class="input-field" required>
              <option value="mcq" @selected(old('type') === 'mcq')>Multiple choice</option>
              <option value="truefalse" @selected(old('type') === 'truefalse')>True / False</option>
              <option value="fib" @selected(old('type') === 'fib')>Fill in the blank</option>
            </select>
          </div>

          <div class="grid gap-3">
            <label for="question_difficulty" class="input-label">Difficulty</label>
            <select id="question_difficulty" name="difficulty" class="input-field">
              @foreach(['easy','medium','hard'] as $level)
                <option value="{{ $level }}" @selected(old('difficulty', 'medium') === $level)>{{ ucfirst($level) }}</option>
              @endforeach
            </select>
          </div>

          <div class="grid gap-3">
            <label for="question_section" class="input-label">Link to section</label>
            <select id="question_section" name="section_id" class="input-field">
              <option value="">No section</option>
              @foreach($module->sections as $s)
                <option value="{{ $s->id }}" @selected(old('section_id') == $s->id)>{{ $s->order }} — {{ $s->title }}</option>
              @endforeach
            </select>
          </div>

          <div class="grid gap-3">
            <label for="question_stem" class="input-label">Question text</label>
            <textarea id="question_stem" name="stem" rows="3" class="input-field" required>{{ old('stem') }}</textarea>
          </div>

          <div class="grid gap-3">
            <label for="question_choices" class="input-label">Choices</label>
            <textarea id="question_choices" name="choices" rows="3" class="input-field" placeholder="One option per line">{{ old('choices') }}</textarea>
            <p class="input-hint">Required for multiple choice. Ignored for other types.</p>
          </div>

          <div class="grid gap-3">
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

    </div>
  </div>
</section>
@endsection

@push('scripts')
<script>
  document.addEventListener('alpine:init', () => {
    Alpine.data('moduleNoteEditor', ({ initial = '', template = '', defaultNote = null }) => ({
      note: initial || '',
      rendered: '',
      templateText: template || '',
      defaultNote,
      sectionTemplates: {
        tldr: 'TL;DR: [Summarize the main takeaway in one sentence.]',
        core: 'Core Concept Explanation:\n[Explain the key idea in clear, simple language.]',
        narrative: 'Contextual Narrative:\n[Share a short, relatable story that illustrates the concept in action.]',
        checklist: 'Real-World Checklist:\n- ✅ Do add two or three practical actions learners can take.\n- ❌ Don\'t repeat jargon or vague statements—keep it specific.',
      },
      init() {
        this.rendered = this.render(this.note);
        this.$watch('note', value => {
          this.rendered = this.render(value);
        });
      },
      get characterCount() {
        return this.note.trim().length;
      },
      get hasDefault() {
        return !!(this.defaultNote && this.defaultNote.trim().length);
      },
      applyTemplate() {
        if (!this.templateText) return;
        if (this.note.trim().length && !window.confirm('Replace the current note with the default template?')) {
          return;
        }
        this.note = this.templateText;
      },
      applyDefault() {
        if (!this.defaultNote) return;
        if (this.note.trim().length && !window.confirm('Replace the current note with the saved default text?')) {
          return;
        }
        this.note = this.defaultNote;
      },
      appendSection(key) {
        const snippet = this.sectionTemplates[key];
        if (!snippet) return;
        const trimmed = this.note.trim();
        this.note = trimmed ? `${trimmed}\n\n${snippet}` : snippet;
        this.$nextTick(() => {
          this.$refs.noteInput?.focus();
        });
      },
      render(value) {
        const text = (value || '').trim();
        if (!text) {
          return '<p class="text-xs text-muted-foreground/70">Start typing or load a template to see a live preview.</p>';
        }

        const lines = value.replace(/\r\n/g, '\n').split('\n');
        let html = '';
        let listOpen = false;

        const closeList = () => {
          if (listOpen) {
            html += '</ul>';
            listOpen = false;
          }
        };

        const escape = (str) => str
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;')
          .replace(/'/g, '&#039;');

        lines.forEach(rawLine => {
          const line = rawLine.trim();

          if (line === '') {
            closeList();
            html += '<div class="h-3"></div>';
            return;
          }

          if (/^[-•]/.test(line)) {
            if (!listOpen) {
              html += '<ul class="list-disc space-y-1 pl-5 text-sm text-foreground">';
              listOpen = true;
            }
            const item = escape(line.replace(/^[-•]+\s*/, ''));
            html += `<li>${item}</li>`;
            return;
          }

          closeList();

          if (/^TL;DR:/i.test(line)) {
            const content = escape(line.replace(/^TL;DR:\s*/i, ''));
            html += `<div class="rounded-xl border border-primary/30 bg-primary/10 px-4 py-3 text-sm font-medium text-primary dark:text-primary-foreground"><span class="block text-[10px] font-semibold uppercase tracking-[0.28em] text-primary/70">TL;DR</span><span class="mt-1 block text-foreground/90 dark:text-foreground">${content || 'Add your quick summary here.'}</span></div>`;
            return;
          }

          if (/^[A-Za-z].*:\s*$/.test(line)) {
            const heading = escape(line.replace(/:\s*$/, ''));
            html += `<h3 class="pt-2 text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">${heading}</h3>`;
            return;
          }

          html += `<p class="text-sm text-foreground">${escape(line)}</p>`;
        });

        closeList();
        return html;
      },
    }));
  });
</script>
@endpush
