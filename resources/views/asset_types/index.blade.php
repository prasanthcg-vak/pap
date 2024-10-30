@extends('layouts.app')

@section('content')
<style>
    .toast {
        transition: opacity 0.3s linear;
    }

    .toast.show {
        opacity: 1;
    }

    .toast.hide {
        opacity: 0;
    }

    .toast.error {
        background-color: #dc3545; /* Bootstrap danger color */
        color: white;
    }
</style>
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
        <div class="task campaingn-table pb-3 common-table">
            <div class="col-lg-12 task campaigns-contents">
                <div class="campaigns-title">
                    <h3>Asset Types</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <button class="btn btn-primary mb-3" onclick="openModal()">Create New Asset Type</button>

    <table id="assetTypesTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Type Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($assetTypes as $assetType)
            <tr>
                <td>{{ $assetType->id }}</td>
                <td>{{ $assetType->type_name }}</td>
                <td>{{ $assetType->type_description }}</td>
                <td>{{ $assetType->is_active ? 'Active' : 'Inactive' }}</td>
                <td>
                    <a href="#" class="btn btn-warning" onclick="editAssetType({{ json_encode($assetType) }})">
                        <i class="fa-solid fa-pencil"></i>
                    </a>

                    <form action="{{ route('asset-types.destroy', $assetType->id) }}" method="POST" class="d-inline-block"
                        onsubmit="return confirm('Are you sure you want to delete this asset type?');">
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

<!-- Modal -->
<div class="modal fade" id="assetTypeModal" tabindex="-1" role="dialog" aria-labelledby="assetTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-l modal-dialog-scrollable">
        <div class="modal-content">
            <form id="assetTypeForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="assetTypeModalLabel">Asset Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="asset_type_id" id="asset_type_id">
                    <div class="form-group">
                        <label for="type_name">Type Name:</label>
                        <input type="text" name="type_name" id="type_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="type_description">Description:</label>
                        <textarea name="type_description" id="type_description" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Status:</label>
                        <div class="form-check form-switch">
                            <input type="checkbox" name="is_active" value="1" id="is_active" class="form-check-input">
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
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
        $('#assetTypesTable').DataTable({
            responsive: true,
            pageLength: 10,
        });
    });

    function openModal() {
        $('#assetTypeForm')[0].reset();
        $('#asset_type_id').val('');
        $('#is_active').prop('checked', false); // Reset checkbox
        $('#assetTypeModal').modal('show');
    }

    function editAssetType(assetType) {
        $('#asset_type_id').val(assetType.id);
        $('#type_name').val(assetType.type_name);
        $('#type_description').val(assetType.type_description);
        $('#is_active').prop('checked', assetType.is_active);
        $('#assetTypeModal').modal('show');
    }

    $('#assetTypeForm').on('submit', function(e) {
        e.preventDefault();

        let assetTypeId = $('#asset_type_id').val();
        let url = assetTypeId ? `/asset-types/${assetTypeId}` : '{{ route("asset-types.store") }}';
        let method = assetTypeId ? 'PUT' : 'POST';

        let formData = $(this).serializeArray();
        $.ajax({
            url: url,
            method: method,
            data: $.param(formData),
            success: function(response) {
                // Show toast notification
                showToast(response.success);
                $('#assetTypeModal').modal('hide');
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
