{{-- resources/views/admin/sections/create.blade.php --}}

@extends('admin.layout')
@section('content')
<h4 class="mb-3">{{ isset($section)?'Edit':'New' }} Section — {{ $module->title }}</h4>
<form method="POST" action="{{ isset($section) ? route('admin.modules.sections.update',[$module,$section]) : route('admin.modules.sections.store',$module) }}">
  @csrf @if(isset($section)) @method('PUT') @endif
  <div class="row g-3">
    <div class="col-md-6"><label class="form-label">Title</label>
      <input class="form-control" name="title" value="{{ old('title',$section->title??'') }}" required></div>
    <div class="col-md-6"><label class="form-label">Slug</label>
      <input class="form-control" name="slug" value="{{ old('slug',$section->slug??'') }}" required></div>
    <div class="col-12"><label class="form-label">Description</label>
      <textarea class="form-control" rows="3" name="description">{{ old('description',$section->description??'') }}</textarea></div>
    <div class="col-md-3"><label class="form-label">Order</label>
      <input type="number" class="form-control" name="order" min="1" value="{{ old('order',$section->order??1) }}"></div>
    <div class="col-md-3 d-flex align-items-end">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active',$section->is_active??true)?'checked':'' }}>
        <label class="form-check-label">Active</label>
      </div>
    </div>
  </div>
  <div class="mt-3">
    <button class="btn btn-primary">{{ isset($section)?'Save changes':'Create section' }}</button>
    <a class="btn btn-outline-secondary" href="{{ route('admin.modules.sections.index',$module) }}">Cancel</a>
  </div>
</form>
@endsection
