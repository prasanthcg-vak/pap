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
                    <h3>Category</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <button class="btn btn-primary mb-3" onclick="openModal()">Create New Category</button> 

    <table id="categoriesTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Category Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->category_name }}</td>
                <td>{{ $category->category_description }}</td>
                <td>{{ $category->is_active ? 'Active' : 'Inactive' }}</td>
                <td>
                    <!-- Edit Button -->
                    <a href="#" class="btn btn-warning" data-bs-toggle="tooltip" title="Edit"
                       onclick="editCategory({{ json_encode($category) }})">
                        <i class="fa-solid fa-pencil"></i>
                    </a>

                    <!-- Delete Form -->
                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline-block"
                          onsubmit="return confirm('Are you sure you want to delete this category?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" data-bs-toggle="tooltip" title="Delete">
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
<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-l modal-dialog-scrollable">
        <div class="modal-content">
            <form id="categoryForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="category_id" id="category_id">
                    <div class="form-group">
                        <label for="category_name">Category Name:</label>
                        <input type="text" name="category_name" id="category_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="category_description">Description:</label>
                        <textarea name="category_description" id="category_description" class="form-control" required></textarea>
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
            $('#categoriesTable').DataTable({
                responsive: true,
                pageLength: 10,
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
            let url = categoryId ? `/categories/${categoryId}` : '{{ route("categories.store") }}';
            let method = categoryId ? 'PUT' : 'POST';

            let formData = $(this).serializeArray();
            $.ajax({
                url: url,
                method: method,
                data: $.param(formData),
                success: function(response) {
                    // Show toast notification
                    showToast(response.success);
                    $('#categoryModal').modal('hide');
                    location.reload(); // Reload the page to see updated categories
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
