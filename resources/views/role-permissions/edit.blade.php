@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Assign Permissions to Role: {{ $role->name }}</h1>

    <form action="{{ route('roles.permissions.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Permissions</label>
            <div>
                @foreach($permissions as $permission)
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input"
                               id="permission-{{ $permission->id }}"
                               name="permissions[]"
                               value="{{ $permission->id }}"
                               {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                        <label class="form-check-label" for="permission-{{ $permission->id }}">
                            {{ $permission->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Save Permissions</button>
    </form>
</div>
@endsection
