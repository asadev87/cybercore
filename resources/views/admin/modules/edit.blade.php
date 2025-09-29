@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Edit Module: {{ $module->title }}</h4>
  <a href="{{ route('admin.modules.index') }}" class="btn btn-secondary btn-sm">Back to Modules</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
  <div class="card-body">
    <form method="POST" action="{{ route('admin.modules.update', $module) }}">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $module->title) }}" required>
      </div>

      <div class="mb-3">
        <label for="slug" class="form-label">Slug</label>
        <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug', $module->slug) }}" required>
        <div class="form-text">A unique, URL-friendly identifier.</div>
      </div>

      @can('assignLecturer', App\Models\Module::class)
      <div class="mb-3">
        <label for="user_id" class="form-label">Assign to Lecturer</label>
        <select class="form-select" id="user_id" name="user_id">
          @foreach($lecturers as $lecturer)
            <option value="{{ $lecturer->id }}" {{ old('user_id', $module->user_id) == $lecturer->id ? 'selected' : '' }}>
              {{ $lecturer->name }} ({{ $lecturer->email }})
            </option>
          @endforeach
        </select>
        <div class="form-text">This option is only visible to admins.</div>
      </div>
      @endcan

      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $module->description) }}</textarea>
      </div>

      <div class="mb-3">
        <label for="pass_score" class="form-label">Pass Score (%)</label>
        <input type="number" class="form-control" id="pass_score" name="pass_score" value="{{ old('pass_score', $module->pass_score) }}" required min="1" max="100">
      </div>

      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $module->is_active) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">
          Active (visible to students)
        </label>
      </div>

      <button type="submit" class="btn btn-primary">Update Module</button>
    </form>
  </div>
</div>
@endsection