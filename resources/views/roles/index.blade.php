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
        <div class="campaingn-table pb-3 common-table">
            <div class="col-lg-12 task campaigns-contents">
                <div class="campaigns-title">
                    <h3>Role Management </h3>
                </div>
                {{-- <button class="common-btn mb-3" onclick="openModal()">Add Role</button> --}}
            </div>
        </div>
    </div>
    <div class="table-wrapper">
        <table id="rolesTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
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
                            {{-- <a href="#" class="btn search" onclick="editAssetType({{ json_encode($role) }})">
                                <i class="fa-solid fa-pencil"></i> --}}
                            </a>
                           {{-- <a data-bs-toggle="tooltip" class="btn btn-warning" href="{{ route('roles.edit', $role) }}"
                                data-bs-placement="top" title="Edit">
                                <i class="fa-solid fa-pencil"></i>
                            </a> --}}
                            {{-- <form action="{{  route('roles.destroy', $role) }}" class="d-inline-block" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this role?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn trash" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </form> --}}
                            <a class="btn edit" data-bs-toggle="tooltip" href="{{ route('roles.permissions.edit', ['role' => $role->id]) }}"
                                title="Assign Permission">
                                <i class="fa-solid fa-user-shield"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<!-- Create Role Modal -->
<div class="modal fade roleModal modal-margin" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
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
                        <div class="row m-0">
                            <div class="col-lg-12">
                                <label for="name" class="common-label">Role Name:</label>
                                <input type="text" name="name" id="role_name" class="common-input" required>
                            </div>
                            <div class="col-lg-12">
                                <label for="description" class="common-label">Description:</label>
                                <textarea name="description" id="role_description" class="common-textarea" required></textarea>
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
<!-- End create Role Modal -->

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#rolesTable').DataTable({
                responsive: true,
                pageLength: 10,
                columnDefs: [
                    { 
                        searchable: false, 
                        orderable: false, 
                        targets: 0 
                    }
                ],
                order: [[1, 'asc']], // Initial sort
                drawCallback: function(settings) {
                    var api = this.api();
                    api.column(0, { order: 'applied' }).nodes().each(function(cell, i) {
                        cell.innerHTML = i + 1; // Number rows dynamically
                    });
                }
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
        $('#modalLoader').show();
        $.ajax({
            url: url,
            method: method,
            data: $.param(formData),
            success: function(response) {
                $('#modalLoader').hide();
                showToast(response.success);
                $('#roleModal').modal('hide');
                location.reload(); // Reload the page to see updated asset types
            },
            error: function(error) {
                $('#modalLoader').hide();
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
