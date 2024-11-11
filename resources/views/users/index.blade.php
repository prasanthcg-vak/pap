@extends('layouts.app')

@section('content')
<div class="CM-main-content">
    <div class="container-fluid p-0">
        <div class="campaingn-table pb-3 common-table">
            <div class="col-lg-12 task campaigns-contents">
                <div class="campaigns-title">
                    <h3>Users Management </h3>
                </div>
                <button class="common-btn mb-3" onclick="openModal()">Add User</button>
            </div>
        </div>
    </div>
    <div class="table-wrapper">
        <table id="usersTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td> 
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                {{ $role->name }}{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </td>
                        <td>{{ $user->is_active ? 'Active' : 'Inactive' }}</td>
                        <td>
                        <a href="#" class="btn btn-warning" onclick="editUser({{ json_encode($user) }})">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline-block"
                            onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- user modal for create and edit -->
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <form id="userForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Users</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="user_id">
                    <!-- Name Field -->
                    <div class="mb-3 row">
                        <label for="name" class="col-md-4 col-form-label text-md-end text-start">Name</label>
                        <div class="col-md-6">
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name', @$data->name) }}" required>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div class="mb-3 row">
                        <label for="email" class="col-md-4 col-form-label text-md-end text-start">Email</label>
                        <div class="col-md-6">
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email', @$data->email) }}" required>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                        <!-- Password Field -->
                        <div class="mb-3 row">
                        <label for="password" class="col-md-4 col-form-label text-md-end text-start">Password</label>
                        <div class="col-md-6">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" value="" required>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Role Field -->
                    <div class="mb-3 row">
                        <label for="role_id" class="col-md-4 col-form-label text-md-end text-start">Role</label>
                        <div class="col-md-6">
                            <select id="role_id" name="role_id"
                                class="form-control @error('role_id') is-invalid @enderror">
                                @foreach (get_roles() as $value => $label)
                                    <option value="{{ $value }}"
                                        {{ isset($data) && $data->role_id == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- Active Checkbox -->
                    <div class="mb-3 row">
                        <label for="is_active" class="col-md-4 col-form-label text-md-end text-start">Active</label>
                        <div class="col-md-6">
                            <input type="checkbox" class="@error('is_active') is-invalid @enderror" id="is_active"
                                name="is_active" value="1" {{ @$data->is_active ? 'checked' : '' }}>
                            @error('is_active')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    {{-- <div class="form-group">
                        <label>Status:</label>
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active" value="1" id="is_active" class="form-check-input">
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
$(document).ready(function() {
    $('#usersTable').DataTable({
        responsive: true,
        pageLength: 10,
        columnDefs: [
            { 
                searchable: false, 
                orderable: false, 
                targets: 0 
            }
        ],
        order: [[1, 'asc']], // Initial sort by name
        drawCallback: function(settings) {
            var api = this.api();
            api.column(0, { order: 'applied' }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1; // Number rows dynamically
            });
        }
    });
});

function openModal() {
    $('#userForm')[0].reset();
    $('#user_id').val('');
    $('#is_active').prop('checked', false); // Reset checkbox
    $('#userModal').modal('show');
}
</script>

@endsection

