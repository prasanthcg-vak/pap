@extends('layouts.app')

@section('content')
    <div class="CM-main-content">
        <div class="container-fluid p-0">
            <!-- Table -->
            <div class="task campaingn-table pb-3 ">
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
                                <th>Client Admin</th>
                                <th>Email</th>
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
                                        {{ optional(optional($client->users->first())->user)->name ?? 'No User Found' }}
                                    </td>
                                    <td>
                                        {{ optional(optional($client->users->first())->user)->email ?? 'No User Found' }}
                                    </td>
                                    <td>
                                        <span>
                                            <p class="status {{ $client->is_active ? 'green' : 'red' }}">
                                                {{ $client->is_active ? 'Active' : 'Inactive' }}</p>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="#" class="btn search"
                                            onclick="editClient({{ json_encode($client) }})">
                                            <i class="fa-solid fa-pencil" title="Edit"></i>
                                        </a>
                                        <form id="Model-Form" action="{{ route('clients.destroy', $client->id) }}"
                                            method="POST" class="d-inline-block"
                                            onsubmit="return confirm('Are you sure you want to delete this client?');">
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
    <div class="modal fade modal-margin" id="clientModal" tabindex="-1" aria-labelledby="clientModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clientModalLabel">
                        <span class="add-model">Add</span>
                        <span class="edit-model">Edit</span> Clients
                    </h5>
                    <button type="button" class="btn-close" id="model-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="clientForm" enctype="multipart/form-data">
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
                                <label for="description" class="common-label">Description</label>
                                <textarea name="description" id="description" class="form-control common-textarea" required></textarea>
                            </div>
                            <div class="col-lg-12">
                                <label for="logo" class="common-label">Logo</label>
                                <div class="img-upload-con ">
                                    <div class="upload--col w-100">
                                        <div class="drop-zone" id="logoPreview">
                                            <div class="drop-zone__prompt" id="nullInput">
                                                <div class="drop-zone_color-txt">
                                                    <span><img src="assets/images/Image.png" alt=""></span> <br />
                                                    <span><img src="assets/images/fi_upload-cloud.svg" alt="">
                                                        Upload
                                                        Logo</span>
                                                </div>
                                                <div class="file-format">
                                                    {{-- <p>File Format <b>JEPG, PNG, JPG, MP4, PDF</b></p> --}}
                                                </div>
                                            </div>
                                            <input type="file" name="logo" class="drop-zone__input">
                                        </div>

                                    </div>
                                </div>

                                {{-- <input type="file" class="form-control @error('logo') is-invalid @enderror common-input"
                                    id="logo" name="logo"> --}}
                                {{-- @if (isset($data) && $data->logo) --}}

                                <div class="mt-2"></div>

                                {{-- @endif --}}
                                @error('logo')
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
                            <div id="client_details">
                                <h5 class="modal-title" id="">Client Admin Details</h5>
                                <!-- Admin Name Field -->
                                <div class="col-lg-12">
                                    <label for="client_admin_name" class="common-label">Admin Name</label>
                                    <input type="text"
                                        class="form-control @error('client_admin_name') is-invalid @enderror common-input"
                                        id="client_admin_name" name="client_admin_name"
                                        value="{{ old('client_admin_name', @$data->name) }}">
                                    @error('client_admin_name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- Email Field -->
                                <div class="col-lg-12">
                                    <label for="email" class="common-label">Email</label>
                                    <input type="email"
                                        class="form-control @error('email') is-invalid @enderror common-input"
                                        id="email" name="email" value="{{ old('email', @$data->email) }}">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <!-- Password Field -->
                                <div class="col-lg-12">
                                    <label for="password" class="common-label">Password</label>
                                    <input type="password"
                                        class="form-control @error('password') is-invalid @enderror common-input"
                                        id="password" name="password">
                                    @error('password')
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
            // Reset the form
            $('#clientForm')[0].reset();
            $('#client_id').val('');
            $('#description').val('');
            $('#is_active').prop('checked', false); // Reset checkbox

            // Set modal for "Add" mode
            $('.add-model').show(); // Show "Add" label
            $('.edit-model').hide(); // Hide "Edit" label

            // Show modal
            $('#clientModal').modal('show');

            // Manage specific elements for "Add" mode
            $('#client_details').removeClass('d-none'); // Show client details
            // $('#logoPreview').addClass('d-none'); // Hide logo preview (specific to add)
            if (!$('#nullInput').length) {
                    // Add the #nullInput section if it doesn't exist
                    $('#logoPreview').html(`
            <div class="drop-zone__prompt" id="nullInput">
                <div class="drop-zone_color-txt">
                    <span><img src="assets/images/Image.png" alt=""></span> <br />
                    <span><img src="assets/images/fi_upload-cloud.svg" alt="">
                        Upload Logo</span>
                </div>
                <div class="file-format">
                </div>
            </div>
        `);}
        }

        $(document).off('submit', '#clientForm').on('submit', '#clientForm', function(e) {
            e.preventDefault();

            // Reset validation feedback
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            // Collect form data using FormData for file upload
            let formData = new FormData(this);
            let clientId = $('#client_id').val();
            let url = clientId ? `/clients/${clientId}` : '{{ route('clients.store') }}';
            let method = clientId ? 'POST' : 'POST'; // Laravel automatically interprets `_method`

            // Add `_method` as PUT for updates
            if (clientId) {
                formData.append('_method', 'PUT');
            }

            // Show loader
            $('#modalLoader').show();

            // Perform AJAX request
            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false, // Prevent jQuery from processing FormData
                contentType: false, // Let the browser set the content type (necessary for FormData)
                success: function(response) {
                    $('#modalLoader').hide();
                    $('#clientModal').modal('hide');
                    location.reload();
                    showToast(response.success, 'success');

                },
                error: function(xhr) {
                    $('#modalLoader').hide();

                    if (xhr.status === 422) {
                        // Handle validation errors
                        let errors = xhr.responseJSON.errors;

                        $.each(errors, function(field, messages) {
                            $(`#${field}`).addClass('is-invalid');
                            $(`#${field}Error`).text(messages[0]);
                        });

                        showToast(xhr.responseJSON.error, 'error');
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

        const base_url = "{{ url('/') }}";

        function editClient(client) {
            console.log(client.users[0]?.user?.name || 'No user found');

            // Set form values from the passed `client` object
            $('#client_id').val(client.id);
            $('#name').val(client.name);
            $('#description').val(client.description);
            $('#is_active').prop('checked', client.is_active);
            $('#client_admin_name').val(client.users[0]?.user?.name || '');
            $('#email').val(client.users[0]?.user?.email || '');
            // $('#role_id').val(client.role_id || '');

            // Handle logo preview
            if (client.logo) {
                $('#nullInput').remove(); // Use .hide() if you want to keep it hidden instead of removing it completely

                const logoPath = `${base_url}/${client.logo}`; // Ensure `base_url` is set correctly
                const imageName = client.logo.split('/').pop(); // Extract image name from path
                // alert(client.logo);
                $('#logoPreview').html(`
                     <div class="drop-zone__thumb" data-label="${imageName}" style="background-image: url(${logoPath});">
            </div>
        `);
            } else {
                if (!$('#nullInput').length) {
                    // Add the #nullInput section if it doesn't exist
                    $('#logoPreview').html(`
            <div class="drop-zone__prompt" id="nullInput">
                <div class="drop-zone_color-txt">
                    <span><img src="assets/images/Image.png" alt=""></span> <br />
                    <span><img src="assets/images/fi_upload-cloud.svg" alt="">
                        Upload Logo</span>
                </div>
                <div class="file-format">
                </div>
            </div>
        `);
                }
                // $('#logoPreview').html('No logo available');
            }

            // Toggle Add/Edit labels
            $('.add-model').hide(); // Hide "Add" label
            $('.edit-model').show(); // Show "Edit" label

            // Show the modal
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
