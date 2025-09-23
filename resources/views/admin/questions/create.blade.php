{{-- resources/views/admin/questions/create.blade.php --}}

@extends('admin.layout')
@section('content')
<h4 class="mb-3">Edit Question — {{ $module->title }}</h4>
@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
<form method="POST" action="{{ route('admin.modules.questions.update',[$module,$question]) }}">
  @include('admin.questions._form')
</form>
@endsection
