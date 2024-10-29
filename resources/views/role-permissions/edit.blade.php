@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Assign Permissions to Role: <strong>{{ $role->name }}</strong></h1>

    <form action="{{ route('roles.permissions.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Permission Group</th>
                    <th>View</th>
                    <th>Create</th>
                    <th>Edit</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $permissionGroups = [
                        'Roles' => ['roles.index', 'roles.create', 'roles.edit', 'roles.update', 'roles.destroy'],
                        'Tasks' => ['tasks.index', 'tasks.create', 'tasks.edit', 'tasks.update', 'tasks.destroy'],
                        'Asset Types' => ['asset-types.index', 'asset-types.create', 'asset-types.edit', 'asset-types.update', 'asset-types.destroy'],
                    ];
                @endphp

                @foreach ($permissionGroups as $groupName => $permissions)
                    <tr>
                        <td colspan="6" class="font-weight-bold">{{ $groupName }}</td>
                    </tr>
                    @foreach ($permissions as $permissionName)
                        <tr>
                            <td>{{ ucfirst(str_replace('.', ' ', $permissionName)) }}</td> <!-- Convert permission name to readable format -->
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input"
                                           id="view-{{ $permissionName }}"
                                           name="permissions[{{ $permissionName }}][view]"
                                           value="1"
                                           {{ $role->permissions->contains($permissionName) && $role->permissions->contains('view') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="view-{{ $permissionName }}">Allow</label>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input"
                                           id="create-{{ $permissionName }}"
                                           name="permissions[{{ $permissionName }}][create]"
                                           value="1"
                                           {{ $role->permissions->contains($permissionName) && $role->permissions->contains('create') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="create-{{ $permissionName }}">Allow</label>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input"
                                           id="edit-{{ $permissionName }}"
                                           name="permissions[{{ $permissionName }}][edit]"
                                           value="1"
                                           {{ $role->permissions->contains($permissionName) && $role->permissions->contains('edit') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="edit-{{ $permissionName }}">Allow</label>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input"
                                           id="update-{{ $permissionName }}"
                                           name="permissions[{{ $permissionName }}][update]"
                                           value="1"
                                           {{ $role->permissions->contains($permissionName) && $role->permissions->contains('update') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="update-{{ $permissionName }}">Allow</label>
                                </div>
                            </td>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input"
                                           id="delete-{{ $permissionName }}"
                                           name="permissions[{{ $permissionName }}][delete]"
                                           value="1"
                                           {{ $role->permissions->contains($permissionName) && $role->permissions->contains('delete') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="delete-{{ $permissionName }}">Allow</label>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary mt-3">Save Permissions</button>
    </form>
</div>








{{--
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
--}}
@endsection
