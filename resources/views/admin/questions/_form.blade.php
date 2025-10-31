{{-- resources/views/admin/questions/_form.blade.php --}}

@csrf
@php($isEditing = isset($question) && $question)
@if($isEditing)
  @method('PUT')
@endif

@php($currentQuestion = $isEditing ? $question : null)
@php($noteValues = $currentQuestion ? (array) $currentQuestion->notes : [])

<div class="grid gap-6 lg:grid-cols-3">
  <div class="grid gap-2">
    <label for="type" class="input-label">Type</label>
    <select id="type" name="type" class="input-field" required>
      @foreach(['mcq' => 'Multiple choice', 'truefalse' => 'True / False', 'fib' => 'Fill in the blank'] as $k => $label)
        <option value="{{ $k }}" @selected(old('type', $currentQuestion->type ?? '') === $k)>{{ $label }}</option>
      @endforeach
    </select>
  </div>

  <div class="grid gap-2">
    <label for="difficulty" class="input-label">Difficulty</label>
    <select id="difficulty" name="difficulty" class="input-field">
      @foreach(['easy','medium','hard'] as $d)
        <option value="{{ $d }}" @selected(old('difficulty', $currentQuestion->difficulty ?? 'medium') === $d)>{{ ucfirst($d) }}</option>
      @endforeach
    </select>
  </div>

  <div class="grid gap-2">
    <label for="section_id" class="input-label">Section</label>
    <select id="section_id" name="section_id" class="input-field">
      <option value="">No section</option>
      @foreach($module->sections as $s)
        <option value="{{ $s->id }}" @selected(old('section_id', $currentQuestion->section_id ?? null) == $s->id)>
          {{ $s->order }} — {{ $s->title }}
        </option>
      @endforeach
    </select>
  </div>
</div>

<div class="grid gap-6">
  <div class="grid gap-2">
    <label for="stem" class="input-label">Question text</label>
    <textarea id="stem" name="stem" rows="3" class="input-field" required>{{ old('stem', $currentQuestion->stem ?? '') }}</textarea>
  </div>

  <div class="grid gap-2">
    <label for="choices" class="input-label">Choices</label>
    <textarea id="choices" name="choices" rows="3" class="input-field" placeholder="Add one choice per line">{{ old('choices', $currentQuestion ? implode("\n", (array) $currentQuestion->choices) : '') }}</textarea>
    <p class="input-hint">Used for multiple choice questions. Leave blank for other types.</p>
  </div>

  <div class="grid gap-2">
    <label for="answer" class="input-label">Correct answer(s)</label>
    <textarea id="answer" name="answer" rows="2" class="input-field" placeholder="Enter the correct answer(s)">{{ old('answer', $currentQuestion ? implode("\n", (array) $currentQuestion->answer) : '') }}</textarea>
    <p class="input-hint">For MCQ, list the correct option. For True/False use <code>true</code> or <code>false</code>. For fill-in, list acceptable answers on separate lines.</p>
  </div>

  <fieldset class="grid gap-4 rounded-2xl border border-border/60 bg-secondary/30 p-4">
    <legend class="px-1 text-xs font-semibold uppercase tracking-[0.28em] text-muted-foreground">Study notes (optional)</legend>
    <div class="grid gap-4 md:grid-cols-3">
      <div class="grid gap-2">
        <label for="note-core" class="input-label text-xs uppercase tracking-wide text-muted-foreground">Core concept</label>
        <textarea id="note-core" name="notes[core_concept]" rows="4" class="input-field text-sm" placeholder="Distill the main takeaway learners should remember.">{{ old('notes.core_concept', $noteValues['core_concept'] ?? '') }}</textarea>
      </div>
      <div class="grid gap-2">
        <label for="note-context" class="input-label text-xs uppercase tracking-wide text-muted-foreground">Real-world context</label>
        <textarea id="note-context" name="notes[context]" rows="4" class="input-field text-sm" placeholder="Share a short scenario that reinforces the concept.">{{ old('notes.context', $noteValues['context'] ?? '') }}</textarea>
      </div>
      <div class="grid gap-2">
        <label for="note-examples" class="input-label text-xs uppercase tracking-wide text-muted-foreground">Examples</label>
        <textarea id="note-examples" name="notes[examples]" rows="4" class="input-field text-sm" placeholder="Bullet examples or tips (one per line).">{{ old('notes.examples', $noteValues['examples'] ?? '') }}</textarea>
      </div>
    </div>
    <p class="text-xs text-muted-foreground">Learners see these notes after answering to reinforce the lesson.</p>
  </fieldset>

  <label class="flex items-center gap-3 rounded-xl border border-border/60 bg-secondary px-4 py-3 text-sm text-secondary-foreground">
    <input class="h-4 w-4 rounded border-border/60 text-primary focus:ring-ring" type="checkbox" name="is_active" value="1" {{ old('is_active', $currentQuestion->is_active ?? 1) ? 'checked' : '' }}>
    <span>Active (visible to learners)</span>
  </label>

  <div class="flex items-center justify-end gap-3">
    <a class="btn btn-muted" href="{{ route('admin.modules.questions.index', $module) }}">Cancel</a>
    <button class="btn btn-primary">{{ $isEditing ? 'Save changes' : 'Create question' }}</button>
  </div>
</div>
