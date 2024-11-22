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
            background-color: #dc3545;
            /* Bootstrap danger color */
            color: white;
        }
    </style>
    @php
        $store = Auth::user()->hasRolePermission('categories.store');
        $edit = Auth::user()->hasRolePermission('categories.update');
        $delete = Auth::user()->hasRolePermission('categories.destroy');
    @endphp
    <div class="CM-main-content">
        <div class="container-fluid p-0">
            @if (session('success'))
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
                        <h3>Category</h3>
                    </div>
                    @if($store)
                    <button class="common-btn mb-3" onclick="openModal()">Add Category</button>
                    @endif
                </div>
            </div>
        </div>

        <div class="table-wrapper">
            <table id="categoriesTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        @if ($edit || $delete)
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->category_name }}</td>
                            <td>{{ $category->category_description }}</td>
                            <td>{{ $category->is_active ? 'Active' : 'Inactive' }}</td>
                            @if ($edit || $delete)
                                <td>
                                    @if ($edit)
                                        <!-- Edit Button -->
                                        <a href="#" class="btn search" data-bs-toggle="tooltip" title="Edit"
                                            onclick="editCategory({{ json_encode($category) }})">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                    @endif

                                    @if ($delete)
                                        <!-- Delete Form -->
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                            class="d-inline-block"
                                            onsubmit="return confirm('Are you sure you want to delete this category?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn delete" data-bs-toggle="tooltip"
                                                title="Delete">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade modal-margin" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">Category</h5>
                    <button type="button" class="btn-close" id="model-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="categoryForm">
                        @csrf
                        <div class="row m-0">
                            <input type="hidden" name="category_id" id="category_id">
                            <div class="col-lg-12">
                                <label for="category_name" class="common-label">Name:</label>
                                <input type="text" name="category_name" id="category_name"
                                    class="form-control common-input" required>
                            </div>
                            <div class="col-lg-12">
                                <label for="category_description" class="common-label">Description:</label>
                                <textarea name="category_description" id="category_description" class="form-control common-textarea" required></textarea>
                            </div>
                            <div class="col-lg-12">
                                <label class="common-label">Status:</label>
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="is_active" value="1" id="is_active"
                                        class="form-check-input">
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
            

            $('#categoryModal').modal({
                backdrop: 'static',
                keyboard: false
            });
        });

        function openModal() {
            $('#categoryForm')[0].reset();
            $('#category_id').val('');
            $('#is_active').prop('checked', false); // Reset checkbox
            $('#categoryModal').modal('show');
        }

        function editCategory(category) {
            $('#category_id').val(category.id);
            $('#category_name').val(category.category_name);
            $('#category_description').val(category.category_description);
            $('#is_active').prop('checked', category.is_active);
            $('#categoryModal').modal('show');
        }

        $('#categoryForm').on('submit', function(e) {
            e.preventDefault();

            let categoryId = $('#category_id').val();
            let url = categoryId ? `/categories/${categoryId}` : '{{ route('categories.store') }}';
            let method = categoryId ? 'PUT' : 'POST';

            let formData = $(this).serializeArray();
            $('#modalLoader').show();

            $.ajax({
                url: url,
                method: method,
                data: $.param(formData),
                success: function(response) {
                    $('#modalLoader').hide();
                    showToast(response.success);
                    $('#categoryModal').modal('hide');
                    location.reload(); // Reload the page to see updated categories
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
                        showToast('Failed to save category', 'error');
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
