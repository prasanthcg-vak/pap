@extends('layouts.app')

@section('content')
<div class="CM-main-content">
    <div class="container-fluid p-0">
        <!-- Table -->
        <div class="task campaingn-table pb-3 common-table">
            <!-- campaigns-contents -->
            <div class="col-lg-12 task campaigns-contents">
                <div class="campaigns-title">
                    <h3>CLIENT MANAGEMENT</h3>
                </div>
                <form>
                    <a class="common-btn mb-3" id="addClient" onclick="openModal()">Add Client</a>
                </form>
            </div>
            <div class="table-wrapper">
                <table id="clientsTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                        <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($clients as $index => $client)
                            <tr>
                                <td>{{ $index + 1 }}</td> 
                                <td>{{ $client->name }}</td>
                                <td>{{ $client->description }}</td>
                                <td>
                                    <span><p class="status {{ $client->is_active ? 'green' : 'red' }}">
                                    {{ $client->is_active ? 'Active' : 'Inactive' }}</p></span>
                                </td>
                                <td>
                                    <a href="#" class="btn search" onclick="editClient({{ json_encode($client) }})">
                                        <i class="fa-solid fa-pencil" title="Edit"></i>
                                    </a>
                                    <form id="Model-Form" action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this client?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn trash">
                                            <i class="fa-regular fa-trash-can" title="Delete"></i>
                                        </button>
                                    </form>
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
  

    <!-- client modal for create and edit -->
    <div class="modal fade modal-margin" id="clientModal" tabindex="-1" aria-labelledby="clientModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clientModalLabel">Clients</h5>
                    <button type="button" class="btn-close" id="model-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="clientForm">
                        @csrf
                        <input type="hidden" name="client_id" id="client_id">
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
                            <div class="col-lg-12"> 
                                <label for="description" class="common-label">Description:</label>
                                <textarea name="description" id="description" class="form-control  common-textarea" required></textarea>
                            </div>
                            <!-- Active Checkbox -->
                            <div class="col-lg-12">
                                <div class="status-radio-btn">
                                    <label for="status" class="common-label">Status</label>
                                    <div>
                                        <input type="checkbox" class="@error('is_active') is-invalid @enderror"
                                            id="is_active" name="is_active" value="1"
                                            {{ @$data->is_active ? 'checked' : '' }}>
                                        @error('is_active')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <label for="html">Active</label><br>
                                    </div>
                                </div>
                            </div>
                            <div id="client_details">
                                <h5 class="modal-title" id="">Client Admin Details</h5>
                                <!-- client user details -->
                                <!-- Name Field -->
                                <div class="col-lg-12">
                                    <label for="client_admin_name" class="common-label">Admin Name</label>
                                    <input type="text" class="form-control @error('client_admin_name') is-invalid @enderror common-input"
                                        id="client_admin_name" name="client_admin_name" value="{{ old('client_admin_name', @$data->name) }}" required>
                                    @error('client_admin_name')
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
                                    <label for="role_id" class="common-label">Role</label>
                                    <select id="role_id" name="role_id"
                                        class="form-select @error('role_id') is-invalid @enderror common-select">
                                        @foreach (get_roles() as $value => $label)
                                            @if (in_array($value, [4]))
                                                <option value="{{ $value }}"
                                                    {{ isset($data) && $data->role_id == $value ? 'selected' : '' }}>
                                                    {{ $label }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
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
                                <button class="btn link-asset" id="cancel" data-bs-dismiss="modal"
                                    aria-label="Close">
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
        $('#clientsTable').DataTable().destroy();
        $('#clientsTable').DataTable({
            responsive: true,
            pageLength: 10,
            columnDefs: [{
                searchable: false,
                orderable: false,
                targets: 0
            }],
            order: [
                [1, 'asc']
            ], // Initial sort by name
            drawCallback: function(settings) {
                var api = this.api();
                api.column(0, {
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1; // Number rows dynamically
                });
            }
        });

        $('#clientModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    });

    function openModal() {
        $('#clientForm')[0].reset();
        $('#client_id').val('');
        $('#description').val('');
        $('#is_active').prop('checked', false); // Reset checkbox
        $('#clientModal').modal('show');
    }

    $(document).off('submit', '#clientForm').on('submit', '#clientForm', function(e) {
        e.preventDefault();

        // Reset validation feedback
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');

        // Collect form data
        let formData = $(this).serializeArray();
        let clientId = $('#client_id').val();
        let url = clientId ? `/clients/${clientId}` : '{{ route('clients.store') }}';
        let method = clientId ? 'PUT' : 'POST';

        // Show loader
        $('#modalLoader').show();

        // Perform AJAX request
        $.ajax({
            url: url,
            method: method,
            data: $.param(formData),
            success: function(response) {
                $('#modalLoader').hide();
                $('#clientModal').modal('hide');
                console.log('AJAX call succeeded. Reloading page...');
                location.reload();
                console.log('Reload triggered.');
                showToast(response.success, 'success');
            },
            error: function(xhr) {
                $('#modalLoader').hide();

                if (xhr.status === 422) {
                    // Handle validation errors
                    let errors = xhr.responseJSON.errors;

                    // Loop through the errors and show them in the modal
                    $.each(errors, function(field, messages) {
                        // Add invalid class and error message
                        $(`#${field}`).addClass(
                        'is-invalid'); // Add invalid class to the input field
                        $(`#${field}Error`).text(messages[
                        0]); // Show the first error message under the input field
                    });

                    // Optionally, you can also display the general message in a toast or modal
                    showToast(xhr.responseJSON.message, 'error');
                } else {
                    showToast('An error occurred.', 'error');
                }
            }
        });
    });

    // function showToast(message, type) {
    //     // Use a simple alert or a toast library for a better UI
    //     alert(type.toUpperCase() + ": " + message);
    // }

    function editClient(client) {
        $('#client_id').val(client.id);
        $('#name').val(client.name);
        $('#description').val(client.description);
        $('#is_active').prop('checked', client.is_active);
        $("#client_details").css('display', 'none');
        $('#clientModal').modal('show');
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
