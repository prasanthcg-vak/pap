<!-- resources/views/roles/index.blade.php -->

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
    </div>
</div>
    <div class="container">
        <h1>Roles</h1>
        <button class="btn btn-primary mb-3" onclick="openModal()">Create New Role</button>
                
        <table id="rolesTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
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
                            <a href="#" class="btn btn-warning" onclick="editAssetType({{ json_encode($role) }})">
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                           {{-- <a data-bs-toggle="tooltip" class="btn btn-warning" href="{{ route('roles.edit', $role) }}"
                                data-bs-placement="top" title="Edit">
                                <i class="fa-solid fa-pencil"></i>
                            </a> --}}
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
                                <i class="fa-solid fa-user-shield"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Create Role Modal -->
    <div class="modal fade createTask-modal" id="roleModal" tabindex="-1" aria-labelledby="roleMopdal"
        aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">CREATE ROLE</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="roleForm">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="role_id" id="role_id">
                            <div class="form-group">
                                <label for="name" class="common-label">Role Name:</label>
                                <input type="text" name="name" id="role_name" class="common-input" required>
                            </div>
                            <div class="form-group">
                                <label for="description" class="common-label">Description:</label>
                                <textarea name="description" id="role_description" class="common-textarea" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End create Role Modal -->

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#rolesTable').DataTable({
                responsive: true,
                pageLength: 10,
                columnDefs: [
                    { orderable: false, targets: [0, 3] }
                ]
            });
            $('#roleModal').modal({
                backdrop: 'static',
                keyboard: false
            });
        });

    function openModal() {
        $('#roleForm')[0].reset();
        $('#role_id').val('');
        $('#roleModal').modal('show');
    }

    function editAssetType(role) {
        $('#role_id').val(role.id);
        $('#role_name').val(role.name);
        $('#role_description').val(role.description);
        $('#roleModal').modal('show');
    }

    $('#roleForm').on('submit', function(e) {
        e.preventDefault();

        let roleId = $('#role_id').val();
        let url = roleId ? `/roles/${roleId}` : '{{ route("roles.store") }}';
        let method = roleId ? 'PUT' : 'POST';

        let formData = $(this).serializeArray();
        console.log(formData);
        
        $.ajax({
            url: url,
            method: method,
            data: $.param(formData),
            success: function(response) {
                // Show toast notification
                showToast(response.success);
                $('#roleModal').modal('hide');
                location.reload(); // Reload the page to see updated asset types
            },
            error: function(error) {
                if (error.status === 422) {
                    let errors = error.responseJSON.errors;
                    let errorMessage = 'Validation Errors:\n';
                    for (const [field, messages] of Object.entries(errors)) {
                        errorMessage += `${field}: ${messages.join(', ')}\n`;
                    }
                    showToast(errorMessage, 'error');
                } else {
                    showToast('Failed to save asset type', 'error');
                }
            }
        });
    });

    function showToast(message, type = 'success') {
        $('#toast-message').text(message);
        const toastEl = document.getElementById('toast');
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }

    </script>
@endsection
