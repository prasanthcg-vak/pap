@extends('layouts.app')

@section('content')
<div class="CM-main-content">
    <div class="container-fluid p-0">
        <div class="task campaingn-table pb-3 ">
            <div class="col-lg-12 task campaigns-contents">
                <div class="campaigns-title">
                    <h3>CLIENT GROUPS</h3>
                </div>
                <form>
                    <a class="common-btn mb-3" id="addClientGroup" onclick="openCreateModal()">Add Client Group</a>
                </form>
            </div>
            <div class="table-wrapper">
                <table class="table table-bordered table-striped" id="clientGroupTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Group Name</th>
                            <th>Client Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientGroups as $group)
                        <tr>
                            <td>{{ $group->id }}</td>
                            <td>{{ $group->name }}</td>
                            <td>{{ $group->client->name }}</td>
                            <td>
                                <a href="#" class="btn search"onclick="openEditModal({{ $group->id }}, '{{ $group->name }}', {{ $group->client_id }})">
                                    <i class="fa-solid fa-pencil" title="Edit"></i>
                                </a>
                                <form action="{{ route('client-groups.destroy', $group->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this client?');">
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
    </div>
</div>

<!-- Modal -->
<div class="modal fade modal-margin" id="clientGroupModal" tabindex="-1" aria-labelledby="clientGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clientGroupModalLabel">Client Groups</h5>
                <button type="button" class="btn-close" id="model-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="clientGroupForm">
                    @csrf
                    <input type="hidden" name="group_id" id="group_id">
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
                            <label for="client_id" class="common-label">Client</label>
                            <select class="form-control" id="client_id" name="client_id" required>
                                <option value="">Select Client</option>
                                @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
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
    $('#clientGroupTable').DataTable().destroy();
    $('#clientGroupTable').DataTable({
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

    $('#clientGroupModal').modal({
        backdrop: 'static',
        keyboard: false
    });
});

function openCreateModal() {
    $('#clientGroupModalLabel').text('Add Client Group');
    $('#clientGroupForm')[0].reset();
    $('#client_id').val('');
    $('#clientGroupModal').modal('show');
}

function openEditModal(id, name, clientId) {
    $('#clientGroupModalLabel').text('Edit Client Group');
    $('#name').val(name);
    $('#client_id').val(clientId);
    $('#group_id').val(id);
    $('#clientGroupModal').modal('show');
}

$(document).off('submit', '#clientGroupForm').on('submit', '#clientGroupForm', function(e) {
    e.preventDefault();

    // Reset validation feedback
    $('.is-invalid').removeClass('is-invalid');

    // Collect form data
    let formData = $(this).serializeArray();
    let groupId = $('#group_id').val();
    const method = groupId ? 'PUT' : 'POST';
    const url = groupId ? `/client-groups/${groupId}` : '{{ route('client-groups.store') }}';

    // Show loader
    $('#modalLoader').show();

    // Perform AJAX request
    $.ajax({
        url: url,
        method: method,
        data: $.param(formData),
        success: function(response) {
            $('#modalLoader').hide();
            $('#clientGroupModal').modal('hide');
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
