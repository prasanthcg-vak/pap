@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Assign Role to User: {{ $user->name }}</h1>

    <form id="Model-Form" action="{{ route('users.roles.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="role_id">Select Role</label>
            <select class="form-control" id="role_id" name="role_id">
                <option value="">Select a role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ $user->roles->contains($role->id) ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Assign Role</button>
    </form>
</div>
@endsection
