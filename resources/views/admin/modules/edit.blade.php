@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Edit Module</h4>
  <a href="{{ route('admin.modules.index') }}" class="btn btn-secondary btn-sm">Back</a>
</div>

<div class="card">
  <div class="card-body">
    <form action="{{ route('admin.modules.update', $module) }}" method="POST">
      @csrf
      @method('PUT')
      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $module->title) }}" required>
      </div>
      <div class="mb-3">
        <label for="slug" class="form-label">Slug</label>
        <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $module->slug) }}" required>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $module->description) }}</textarea>
      </div>
      <div class="mb-3">
        <label for="pass_score" class="form-label">Pass Score (%)</label>
        <input type="number" class="form-control" id="pass_score" name="pass_score" value="{{ old('pass_score', $module->pass_score) }}" required>
      </div>
      @role('admin')
      <div class="mb-3">
        <label for="user_id" class="form-label">Lecturer</label>
        <select class="form-select" id="user_id" name="user_id" required>
          @foreach($lecturers as $lecturer)
            <option value="{{ $lecturer->id }}" @if($module->user_id == $lecturer->id) selected @endif>{{ $lecturer->name }}</option>
          @endforeach
        </select>
      </div>
      @else
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
      @endrole
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" @if($module->is_active) checked @endif>
        <label class="form-check-label" for="is_active">
          Active
        </label>
      </div>
      <button type="submit" class="btn btn-primary">Update Module</button>
    </form>
  </div>
</div>
@endsection