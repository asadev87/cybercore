{{-- resources/views/admin/questions/_form.blade.php --}}

@csrf
@if(isset($question)) @method('PUT') @endif

<div class="row g-3">
  <div class="col-md-3">
    <label class="form-label">Type</label>
    <select name="type" class="form-select" required>
      @foreach(['mcq'=>'Multiple Choice','truefalse'=>'True/False','fib'=>'Fill in the Blank'] as $k=>$v)
        <option value="{{ $k }}" @selected(old('type',$question->type??'')===$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label">Difficulty</label>
    <select name="difficulty" class="form-select">
      @foreach(['easy','medium','hard'] as $d)
        <option value="{{ $d }}" @selected(old('difficulty',$question->difficulty??'medium')===$d)>{{ ucfirst($d) }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-6">
    <label class="form-label">Section</label>
    <select class="form-select" name="section_id">
      <option value="">(none)</option>
      @foreach($module->sections as $s)
        <option value="{{ $s->id }}" @selected(old('section_id',$question->section_id??null)==$s->id)>
          {{ $s->order }} — {{ $s->title }}
        </option>
      @endforeach
    </select>
  </div>

  <div class="col-12">
    <label class="form-label">Question</label>
    <textarea class="form-control" name="stem" rows="3" required>{{ old('stem',$question->stem??'') }}</textarea>
  </div>

  <div class="col-12">
    <label class="form-label">Choices (MCQ: one per line)</label>
    <textarea class="form-control" name="choices" rows="3">{{ old('choices', isset($question)?implode("\n",(array)$question->choices):'') }}</textarea>
  </div>

  <div class="col-12">
    <label class="form-label">Correct Answer(s)</label>
    <textarea class="form-control" name="answer" rows="2">{{ old('answer', isset($question)?implode("\n",(array)$question->answer):'') }}</textarea>
  </div>

  <div class="col-12">
    <div class="form-check">
      <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active',$question->is_active??1)?'checked':'' }}>
      <label class="form-check-label">Active</label>
    </div>
  </div>
</div>

<div class="mt-3">
  <button class="btn btn-primary">{{ isset($question)?'Save changes':'Create question' }}</button>
  <a class="btn btn-outline-secondary" href="{{ route('admin.modules.questions.index',$module) }}">Cancel</a>
</div>

