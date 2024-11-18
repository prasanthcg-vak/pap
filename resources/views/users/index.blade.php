@extends('layouts.app')

@section('content')
<div class="CM-main-content">
    <div class="container-fluid p-0">
         @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div id="toast-container" aria-live="polite" aria-atomic="true" style="position: absolute; top: 20px; right: 20px;">
            <div id="toast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong class="me-auto">Notification</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    <span id="toast-message"></span>
                </div>
            </div>
        </div>
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
                    <th>Group</th>
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
                        <td>
                           {{@$user->group->client_group_name}}
                        </td>
                        
                        <td>{{ $user->is_active ? 'Active' : 'Inactive' }}</td>
                        <td>
                        <a href="#" class="btn search" onclick="editUser({{ json_encode($user) }})">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline-block"
                            onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn trash">
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
<div class="modal fade modal-margin" id="userModal" tabindex="-1" aria-labelledby="userModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Users</h5>
                <button type="button" class="btn-close" id="model-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="userForm">
                    @csrf
                        <input type="hidden" name="user_id" id="user_id">
                        <div class="row m-0">
                            <!-- Name Field -->
                            <div class="col-lg-12">
                                <label for="name" class="common-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror common-input"
                                    id="name" name="name" value="{{ old('name', @$data->name) }}" required>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Email Field -->
                            <div class="col-lg-12">
                                <label for="email" class="common-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror common-input"
                                    id="email" name="email" value="{{ old('email', @$data->email) }}" required>
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Role Field -->
                            <div class="col-lg-12">
                                <label for="group_id" class="common-label">Group</label>
                                <select id="group_id" name="group_id"
                                    class="form-select @error('group_id') is-invalid @enderror common-select">
                                    <option value="-1" >-Select-</option>
                                    @foreach (get_groups() as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ isset($data) && $data->group_id == $value ? 'selected' : '' }}>
                                           {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('group_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-12">
                                <label for="role_id" class="common-label">Role</label>
                                <select id="role_id" name="role_id"
                                    class="form-select @error('role_id') is-invalid @enderror common-select">
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
                            <!-- Active Checkbox -->
                            <div class="col-lg-12">
                                <div class="status-radio-btn">
                                    <label for="status" class="common-label">Status</label>
                                    <div>
                                    <input type="checkbox" class="@error('is_active') is-invalid @enderror" id="is_active"
                                        name="is_active" value="1" {{ @$data->is_active ? 'checked' : '' }}>
                                    @error('is_active')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                        <label for="html">Active</label><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sic-action-btns d-flex justify-content-md-end justify-content-center flex-wrap">
                            <div class="sic-btn">
                                <button class="btn download" id="save" type="submit">
                                    save
                                </button>
                            </div>
                            <div class="sic-btn">
                                <button class="btn link-asset" id="cancel" data-bs-dismiss="modal" aria-label="Close">
                                    cancel
                                </button>
                            </div>
                        </div>
                </form>
            </div>
            <div id="modalLoader" class="modal-loader" style="display: none;">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
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

    $('#userModal').modal({
        backdrop: 'static',
        keyboard: false
    });
});

function openModal() {
    $('#userForm')[0].reset();
    $('#user_id').val('');
    $('#is_active').prop('checked', false); // Reset checkbox
    $('#userModal').modal('show');
}

$('#userForm').on('submit', function(e) {
    e.preventDefault();

    let userId = $('#user_id').val();
    let url = userId ? `/users/${userId}` : '{{ route("users.store") }}';
    let method = userId ? 'PUT' : 'POST';

    let formData = $(this).serializeArray();
    $('#modalLoader').show();
    $.ajax({
        url: url,
        method: method,
        data: $.param(formData),
        success: function(response) {
            $('#modalLoader').hide();
            $('#userModal').modal('hide');
            showToast(response.success, 'success'); 
            location.reload();
        },
        error: function(xhr) {
            $('#modalLoader').hide();
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.error;
                $.each(errors, function(key, message) {
                    showToast(message[0], 'error');
                });
            } else {
                showToast('An error occurred.', 'error');
            }
        }
    });
});

function editUser(user) {
    $('#user_id').val(user.id);
    $('#name').val(user.name);
    $('#email').val(user.email);
    $('#is_active').prop('checked', user.is_active);

    // Set the group_id and role_id for the selects
    $('#group_id').val(user.group_id);
    
    if (user.roles && user.roles.length > 0) {
        $('#role_id').val(user.roles[0].id);
    } else {
        $('#role_id').val('');
    }
    
    $('#userModal').modal('show');
}

function showToast(message, type = 'success') {
    $('#toast-message').text(message);
    const toastEl = document.getElementById('toast');
    const toast = new bootstrap.Toast(toastEl);

    if (type === 'success') {
        $('#toast').removeClass('bg-danger').addClass('bg-success');
    } else {
        $('#toast').removeClass('bg-success').addClass('bg-danger');
    }

    toast.show();
}

</script>

@endsection

