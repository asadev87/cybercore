@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h1>{{ $module->title }}</h1>
        </div>
        <div class="card-body">
            <p><strong>Taught by:</strong> {{ $module->user->name ?? 'N/A' }}</p>
            <hr>
            <p>{{ $module->description }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('learn.index') }}" class="btn btn-primary">Back to Courses</a>
        </div>
    </div>
</div>
@endsection