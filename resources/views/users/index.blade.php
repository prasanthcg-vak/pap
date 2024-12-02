@extends('layouts.app')

@section('content')
    @php
        $store = Auth::user()->hasRolePermission('users.store');
        $edit = Auth::user()->hasRolePermission('users.update');
        $delete = Auth::user()->hasRolePermission('users.destroy');
        $impersonate = Auth::user()->hasRolePermission('impersonate');

    @endphp
    <div class="CM-main-content">
        <div class="container-fluid p-0">
            <!-- Table -->
            <div class=" p-3">
                <!-- campaigns-contents -->
                <div class="col-lg-12 task campaigns-contents">
                    <div class="campaigns-title">
                        <h3>USER MANAGEMENT</h3>
                    </div>
                    @if ($store)
                        <form>
                            {{-- <input type="text" name="search" placeholder="Search..."> --}}
                            <a class="common-btn mb-3" id="" onclick="openModal()">Add User</a>
                        </form>
                    @endif
                </div>
                <!-- campaigns-contents -->
                <div class="table-wrapper">
                    <table id="datatable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Client</th>
                                <th>Client Group</th>
                                <th>Status</th>
                                @if ($edit || $delete)
                                    <th>Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>
                                        <a href="mailto:{{ $user->email }}"
                                            class="text-decoration-none active-email">{{ $user->email }}</a>
                                    </td>
                                    <td>
                                        @foreach ($user->roles as $role)
                                            {{ $role->name }}{{ !$loop->last ? ', ' : '' }}
                                        @endforeach
                                    </td>
                                    <td>{{ $user->client ? $user->client->name : '-' }}</td>
                                    <td>{{ $user->group ? $user->group->name : '-' }}</td>

                                    <td>
                                        <span>
                                            <p class="status {{ $user->is_active ? 'green' : 'red' }}">
                                                {{ $user->is_active ? 'Active' : 'Inactive' }}</p>
                                        </span>
                                    </td>
                                    @if ($edit || $delete)
                                        <td>

                                            @if ($edit)
                                                <a href="#" class="btn search"
                                                    onclick="editUser({{ json_encode($user) }})">
                                                    <i class="fa-solid fa-pencil"></i>
                                                </a>
                                            @endif
                                            {{-- <a href="#"
                                                class="btn search  align-items-center justify-content-center"
                                                onclick="resendMail({{ $user->id }}, this)">
                                                <span class="spinner-border spinner-border-sm me-2 d-none" role="status"
                                                    aria-hidden="true"></span>
                                                Resend Mail
                                            </a> --}}
                                            @if ($delete)
                                                <form id="Model-Form" action="{{ route('users.destroy', $user->id) }}"
                                                    method="POST" class="d-inline-block"
                                                    onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn trash">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if ($impersonate)
                                                <a href="{{ route('impersonate', $user->id) }}" class="btn btn-sm edit">
                                                    <i class='bx bx-link-external'></i>
                                                </a>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Table -->
        </div>
    </div>


    <!-- user modal for create and edit -->
    <div class="modal fade modal-margin" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Users</h5>
                    <button type="button" class="btn-close" id="model-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
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
                                <label for="role_id" class="common-label">Role</label>
                                <select id="role_id" name="role_id" onchange="toggleGroupSection()"
                                    class="form-select @error('role_id') is-invalid @enderror common-select">
                                    <option value="">-Select-</option>
                                    @foreach (get_roles() as $value => $label)
                                        @if (!in_array($value, [1]))
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

                            <!-- Group Field -->
                            <div class="col-lg-12" id="client-section"
                                style="display: {{ isset($data) && ($data->role_id == 4 || $data->role_id == 5 || $data->role_id == 6) ? 'block' : 'none' }};">
                                <label for="client_id_inUser" class="common-label">Client</label>
                                <select id="client_id_inUser" name="client_id_inUser"
                                    class="form-select @error('client_id_inUser') is-invalid @enderror common-select">
                                    <option value="">-Select-</option>
                                    @foreach (get_clients() as $value => $label)
                                        <option value="{{ $value }}"
                                            {{ isset($data) && $data->client_id == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="client_idError"></div>
                                @error('client_id_inUser')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-lg-12" id="group-section"
                                style="display: {{ isset($data) && $data->role_id == 6 ? 'block' : 'none' }};">
                                <label for="group_id" class="form-label">Select Client Group</label>
                                <select name="group_id" id="group_id" class="form-control" disabled>
                                    <option value="" disabled selected>Select Client Group</option>
                                </select>
                                <div class="invalid-feedback" id="group_idError"></div>
                                @error('group_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Password Field -->
                            <div class="col-lg-12">
                                <label for="password" class="common-label">Password</label>
                                <input type="password"
                                    class="form-control @error('password') is-invalid @enderror common-input"
                                    id="password" name="password" minlength="8" required>
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="col-lg-12">
                                <label for="password_confirmation" class="common-label">Confirm Password</label>
                                <input type="password"
                                    class="form-control @error('password_confirmation') is-invalid @enderror common-input"
                                    id="password_confirmation" name="password_confirmation" minlength="8" required>
                                @error('password_confirmation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
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
            $('#usersTable').DataTable().destroy();
            $('#usersTable').DataTable({
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

        $('#userForm').off('submit').on('submit', function(e) {
            e.preventDefault();

            // Reset validation feedback
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            // Collect form data
            let formData = $(this).serializeArray();
            let userId = $('#user_id').val();
            let url = userId ? `/users/${userId}` : '{{ route('users.store') }}';
            let method = userId ? 'PUT' : 'POST';

            // Show loader
            $('#modalLoader').show();

            // Perform AJAX request
            $.ajax({
                url: url,
                method: method,
                data: $.param(formData),
                success: function(response) {
                    location.reload();
                    $('#modalLoader').hide();
                    $('#userModal').modal('hide');
                    showToast(response.success, 'success');
                },
                error: function(xhr) {
                    $('#modalLoader').hide();

                    if (xhr.status === 422) { // Validation error
                        let errors = xhr.responseJSON.errors;

                        // Clear all previous error messages and 'is-invalid' classes
                        $('.text-danger').remove(); // Remove previous error messages
                        $('.is-invalid').removeClass('is-invalid'); // Remove invalid class from inputs

                        for (let field in errors) {
                            let errorMessage = errors[field][0];
                            let inputField = $(`[name="${field}"]`);

                            // Add 'is-invalid' class to the input field
                            inputField.addClass('is-invalid');

                            // Append the new error message
                            inputField.after(`<span class="text-danger">${errorMessage}</span>`);
                        }
                    }

                }
            });
        });


        function showToast(message, type) {
            // Use a simple alert or a toast library for a better UI
            alert(type.toUpperCase() + ": " + message);
        }

        function editUser(user) {
    // Populate basic fields
    $('#user_id').val(user.id);
    $('#name').val(user.name);
    $('#email').val(user.email);
    $('#is_active').prop('checked', user.is_active); // Check/uncheck based on value

    // Populate the role dropdown
    if (user.roles && user.roles.length > 0) {
        $('#role_id').val(user.roles[0].id).change(); // Set role and trigger change event
    } else {
        $('#role_id').val('').change(); // Reset role if no roles exist
    }

    // Populate the client dropdown
    $('#client_id_inUser').val(user.client_id).change(); // Set client and trigger change

    // Show/hide group and client sections based on role
    const groupSection = $('#group-section');
    const clientSection = $('#client-section');
    if (user.roles[0].id == 4 || user.roles[0].id == 5 || user.roles[0].id == 6) {
        clientSection.show(); // Show Client section
        if (user.roles[0].id == 6) {
            groupSection.show(); // Show Group section
            // Populate group dropdown
            const groupDropdown = $('#group_id');
            groupDropdown.empty().append('<option value="">-- Select Client Group --</option>'); // Reset options
            $.ajax({
                url: `/get-client-groups/${user.client_id}`, // Fetch groups based on client_id
                type: 'GET',
                success: function (data) {
                    // Populate group dropdown with fetched data
                    data.forEach(function (group) {
                        groupDropdown.append(`<option value="${group.id}" ${group.id == user.group_id ? 'selected' : ''}>${group.name}</option>`);
                    });
                    groupDropdown.prop('disabled', false); // Enable the dropdown
                },
                error: function () {
                    alert('Failed to fetch client groups.');
                }
            });
        } else {
            groupSection.hide(); // Hide Group section if role is not 6
        }
    } else {
        clientSection.hide(); // Hide Client section for other roles
        groupSection.hide(); // Hide Group section for other roles
    }

    // Remove 'required' attribute from password fields
    $('input[type="password"]').removeAttr('required');

    // Show the modal
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
    <script>
        function resendMail(userId, button) {
            const spinner = button.querySelector('.spinner-border');

            // Show the spinner
            spinner.classList.remove('d-none');
            button.setAttribute('disabled', true); // Disable the button to prevent multiple clicks

            fetch(`/user/${userId}/resend-email`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.success);
                    } else {
                        alert(data.error || 'Failed to resend email.');
                    }
                })
                .catch(err => console.error(err))
                .finally(() => {
                    // Hide the spinner and re-enable the button
                    spinner.classList.add('d-none');
                    button.removeAttribute('disabled');
                });
        }
        const groupDropdown = document.getElementById('clientGroup');
    </script>
@endsection
