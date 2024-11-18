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
        <div class="task campaingn-table pb-3 common-table">
            <div class="col-lg-12 task campaigns-contents">
                <div class="campaigns-title">
                    <h3>Asset Types</h3>
                </div>
                 <button class="common-btn mb-3" onclick="openModal()">Add Asset Type</button>
            </div>
        </div>
    </div>
    <div class="table-wrapper">
        <table id="assetTypesTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Type Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($assetTypes as $index => $assetType)
                <tr>
                    <td>{{ $index + 1 }}</td> 
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
</div>



<!-- Modal -->
<div class="modal fade modal-margin" id="assetTypeModal" tabindex="-1" role="dialog" aria-labelledby="assetTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-l modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assetTypeModalLabel">Asset Type</h5>
                <button type="button" class="btn-close" id="model-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="assetTypeForm">
                    @csrf
                    <div class="row m-0">
                        <input type="hidden" name="asset_type_id" id="asset_type_id">
                        <div class="col-lg-12">
                            <label for="type_name" class="common-label">Type Name:</label>
                            <input type="text" name="type_name" id="type_name" class="form-control  common-input" required>
                        </div>
                        <div class="col-lg-12"> 
                            <label for="type_description" class="common-label">Description:</label>
                            <textarea name="type_description" id="type_description" class="form-control  common-textarea" required></textarea>
                        </div>
                        <div class="col-lg-12">
                            <label class="common-label">Status:</label>
                            <div class="form-check form-switch">
                                <input type="checkbox" name="is_active" value="1" id="is_active" class="form-check-input">
                                <label class="form-check-label" for="is_active">Active</label>
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
        $('#assetTypesTable').DataTable({
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

        $('#assetTypeModal').modal({
            backdrop: 'static',
            keyboard: false
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
        $('#modalLoader').show();

        $.ajax({
            url: url,
            method: method,
            data: $.param(formData),
            success: function(response) {
                $('#modalLoader').hide();
                showToast(response.success);
                $('#assetTypeModal').modal('hide');
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
