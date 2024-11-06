@extends('layouts.app')

@section('content')
    <!-- ========== Start CM-main-content ========== -->
    <div class="CM-main-content">
        <div class="container-fluid p-0">
            <!-- Table -->
            <div class="campaingn-table pb-3 common-table">
                <!-- campaigns-contents -->
                <div class="col-lg-12 task campaigns-contents">
                    <div class="campaigns-title">
                        <h3>Users Management</h3>
                    </div>
                    <form>
                        {{-- <input type="text" name="search" placeholder="Search..."> --}}
                        <button type="button" class="create-task-btn" data-bs-toggle="modal"
                            data-bs-target="#createUserModal">
                            Create User
                        </button>
                    </form>
                </div>
                <!-- campaigns-contents -->
                <div class="table-wrapper ">
                    <table id="datatable">
                        <thead>
                            <tr style="width: 149% !important">
                                <th class="slno">
                                    <span>S.No</span>
                                </th>
                                <th class="campaingn-title">
                                    <span>Name</span>
                                </th>
                                <th class="email">
                                    <span>Email</span>
                                </th>
                                <th class="role">
                                    <span>Role</span>
                                </th>

                                <th class="active">
                                    <span>Action</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody style="width: 149% !important">
                            @foreach ($users as $user)
                                <tr>

                                    <td class="slno">
                                        <span>{{ $loop->iteration }}</span>
                                    </td>
                                    <td class="campaingn-title">
                                        <span>{{ $user->name }}</span>
                                    </td>
                                    <td class="email">
                                        <span>{{ $user->email }}</span>
                                    </td>
                                    <td class="role">
                                        <span>{{ $user->role->name ?? 'No Role Assigned' }}</span> <!-- Access role name -->
                                    </td>
                                    <td class="active">
                                        <div class="action-btn-group">
                                            <div class="left-group">
                                                <!-- Button trigger modal -->
                                                <button type="button" class="btn view-btn btn-primary"
                                                    data-bs-toggle="modal" data-bs-target="#editUserModal"
                                                    data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                                    data-email="{{ $user->email }}" data-role-id="{{ $user->role_id }}"
                                                    data-is-active="{{ $user->is_active }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <!-- Modal added below -->
                                                <!-- Modal ends -->
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                    style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-default btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this user?');">
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                </form>


                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Table -->
        </div>
    </div>
    <!-- ========== End CM-main-content ========== -->


    <!-- Modal contents -->

    <!-- User Modal Structure -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createUserModalLabel">CREATE USER</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Laravel Form with Validation and CSRF Token -->
                    <form action="{{ $route }}" method="post" id="data-form">
                        @csrf
                        @if ($method === 'PUT')
                            @method('PUT')
                        @endif

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

                        <!-- Submit Buttons -->
                        <div class="mb-3 row offset-md-5" style="text-align: center;">
                            <input type="submit" class="col-md-2 create-task-btn" value="Save">
                            <button type="button" class="col-md-2 create-task-btn" data-bs-dismiss="modal"
                                aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST" id="edit-user-form">
                        @csrf
                        @method('PUT') <!-- Use PUT for updating -->
    
                        <div class="mb-3 row">
                            <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="edit-name" name="name" value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
    
                        <div class="mb-3 row">
                            <label for="email" class="col-md-4 col-form-label text-md-end">Email</label>
                            <div class="col-md-6">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="edit-email" name="email" value="{{ old('email', $user->email) }}">
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
    
                        <div class="mb-3 row">
                            <label for="role_id" class="col-md-4 col-form-label text-md-end">Role</label>
                            <div class="col-md-6">
                                <select id="role_id" name="role_id" class="form-control @error('role_id') is-invalid @enderror">
                                    @foreach (get_roles() as $value => $label)
                                        <option value="{{ $value }}" {{ old('role_id', $user->role_id) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
    
                        <div class="mb-3 row">
                            <label for="is_active" class="col-md-4 col-form-label text-md-end">Active</label>
                            <div class="col-md-6">
                                <input type="checkbox" class="@error('is_active') is-invalid @enderror" id="edit-is_active" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }}>
                                @error('is_active')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
    
                        <div class="mb-3 row offset-md-5" style="text-align: center;">
                            <input type="submit" class="col-md-2 create-task-btn" value="Update">
                            <button type="button" class="col-md-2 create-task-btn" data-bs-dismiss="modal"
                                aria-label="Close">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
