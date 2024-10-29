<!-- resources/views/roles/show.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Role Details</h1>
        <p><strong>ID:</strong> {{ $role->id }}</p>
        <p><strong>Name:</strong> {{ $role->name }}</p>
        <p><strong>Description:</strong> {{ $role->description }}</p>
        <a href="{{ route('roles.index') }}" class="btn btn-primary">Back to List</a>
    </div>
@endsection
