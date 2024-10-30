<!-- resources/views/roles/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Roles</h1>
        <a href="{{ route('roles.create') }}" class="btn btn-primary mb-3">Create New Role</a>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <table id="rolesTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>{{ $role->name }}</td>
                        <td>{{ $role->description }}</td>
                        <td>
                            <input type="radio" name="status_{{ $role->id }}" value="1" {{ $role->status ? 'checked' : '' }} onclick="updateStatus({{ $role->id }}, 1)"> Active
                            <input type="radio" name="status_{{ $role->id }}" value="0" {{ !$role->status ? 'checked' : '' }} onclick="updateStatus({{ $role->id }}, 0)"> Inactive
                        </td>
                        <td>
                            <a data-bs-toggle="tooltip" class="btn btn-warning" href="{{ route('roles.edit', $role) }}"
                                data-bs-placement="top" title="Edit">
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                            <form action="{{  route('roles.destroy', $role) }}" class="d-inline-block" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this role?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form>
                            <a class="btn btn-success" data-bs-toggle="tooltip" href="{{ route('roles.permissions.edit', ['role' => $role->id]) }}"
                                title="Assign Permission">
                                <i class="fa-solid fa-user-shield"></i></a>
                            <!-- <a href="{{ route('roles.show', $role) }}" class="btn btn-info">View</a>
                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning">Edit</a>
                            <a class="btn btn-success" href="{{ route('roles.permissions.edit', ['role' => $role->id]) }}">Assign Permission</a>
                            <form action="{{ route('roles.destroy', $role) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form> -->
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#rolesTable').DataTable({
                responsive: true,
                pageLength: 10,
            });
        });

        function updateStatus(roleId, status) {
            $.ajax({
                url: '{{ route("roles.updateStatus") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    role_id: roleId,
                    status: status
                },
                success: function(response) {
                    alert(response.message);
                },
                error: function(error) {
                    alert('Failed to update status');
                }
            });
        }
    </script>
@endsection
