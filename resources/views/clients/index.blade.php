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
                                <th class="w-25">Description</th>
                                <th>Client Admin</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th style="display: flex;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clients as $index => $client)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $client->name }}</td>
                                    <td class="w-25">
                                        @php
                                            $words = explode(' ', strip_tags($client->description)); // Strip HTML tags and split into words
                                            $truncated = implode(' ', array_slice($words, 0, 15)); // Get the first 15 words
                                        @endphp
                                        <span class="truncated-text">
                                            {!! $truncated !!}{{ count($words) > 15 ? '...' : '' }}
                                        </span>
                                        @if (count($words) > 15)
                                            <span class="read-more"
                                                style="color: orange; cursor: pointer; text-decoration: underline;"> Read
                                                more</span>
                                            <span class="full-text" style="display: none;">
                                                {!! $client->description !!}
                                                <span class="read-less"
                                                    style="color: orange; cursor: pointer; text-decoration: underline;">
                                                    Read
                                                    less</span>
                                            </span>
                                        @endif
                                    </td>
                                    
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
                                    <td style="">
                                        <a href="#" class="btn search m-1"
                                            onclick="editClient({{ json_encode($client) }})">
                                            <i class="fa-solid fa-pencil" title="Edit"></i>
                                        </a>
                                        <form id="Model-Form" action="{{ route('clients.destroy', $client->id) }}"
                                            method="POST" class="d-inline-block m-1"
                                            onsubmit="return confirm('Are you sure you want to delete this client?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn trash">
                                                <i class="fa-regular fa-trash-can" title="Delete"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('impersonate', optional($client->users->first())->id) }}" class="btn btn-sm edit me-1">
                                            <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="300.000000pt"
                                                height="260.000000pt" viewBox="0 0 300.000000 260.000000"
                                                preserveAspectRatio="xMidYMid meet">
                                                <g transform="translate(0.000000,260.000000) scale(0.100000,-0.100000)"
                                                    fill="#21b9ad" stroke="none">
                                                    <path
                                                        d="M1265 2550 c-54 -10 -158 -59 -197 -94 -22 -20 -61 -41 -118 -63 -41 -17 -91 -63 -126 -119 -27 -42 -36 -69 -44 -136 -5 -46 -16 -108 -25 -138 -16 -60 -18 -124 -6 -185 9 -39 9 -39 10 20 1 33 8 78 16 100 8 22 15 46 15 53 0 26 66 101 106 121 23 12 56 21 74 21 39 0 143 -25 294 -71 37 -12 151 -12 174 0 27 13 93 34 137 42 22 4 58 12 80 18 85 22 150 5 207 -56 32 -34 69 -137 78 -218 6 -50 7 -41 8 56 2 146 -8 172 -97 259 -63 61 -100 123 -101 170 -1 26 -64 108 -110 143 -25 18 -71 43 -102 55 -66 24 -199 35 -273 22z" />
                                                    <path
                                                        d="M924 2019 c-48 -25 -74 -129 -74 -301 l0 -109 -29 16 c-52 26 -63 13 -58 -69 2 -39 10 -93 17 -121 16 -63 67 -142 97 -150 16 -4 25 -16 33 -48 39 -160 55 -204 95 -257 54 -70 142 -158 166 -165 10 -4 19 -10 19 -16 0 -5 18 -12 40 -15 22 -4 43 -11 46 -15 8 -13 118 -11 151 2 82 33 122 56 161 94 48 48 48 50 27 191 -30 198 -18 304 42 393 29 42 93 101 111 101 6 0 12 4 12 10 0 5 17 12 38 15 78 14 112 26 112 41 0 23 -27 28 -55 10 -13 -9 -27 -16 -30 -16 -3 0 -5 62 -5 139 0 101 -4 152 -16 190 -25 75 -42 86 -131 84 -68 -1 -99 -8 -219 -50 -47 -17 -220 -14 -269 4 -117 44 -242 62 -281 42z m190 -315 c27 -8 61 -14 75 -14 77 0 123 -52 82 -94 -20 -19 -22 -19 -74 -3 -30 9 -64 17 -77 17 -33 0 -152 30 -167 42 -19 15 -16 55 5 67 20 11 85 5 156 -15z m613 20 c27 -7 38 -36 24 -62 -7 -13 -34 -23 -93 -36 -46 -9 -113 -24 -150 -32 -65 -16 -67 -15 -88 6 -39 39 7 90 84 90 19 0 47 5 63 11 15 6 51 15 78 19 28 4 52 8 55 9 3 0 15 -2 27 -5z m-544 -200 c34 -6 37 -9 37 -38 0 -47 -37 -79 -90 -78 -55 1 -80 29 -80 90 l0 45 48 -7 c26 -3 64 -9 85 -12z m467 -3 c15 -29 12 -43 -19 -80 -23 -28 -34 -34 -69 -34 -52 0 -82 30 -82 80 0 29 3 33 28 34 15 0 36 4 47 9 37 16 84 11 95 -9z m-190 -351 c43 -23 102 -101 88 -115 -2 -2 -17 2 -33 10 -43 20 -134 45 -164 45 -36 0 -153 -31 -178 -47 -16 -10 -23 -11 -28 -2 -4 6 -4 14 1 18 5 3 17 18 27 34 25 39 88 75 138 79 57 4 118 -5 149 -22z" />
                                                    <path
                                                        d="M1880 1498 c-25 -5 -52 -16 -61 -24 -8 -8 -18 -14 -22 -14 -17 0 -75 -72 -91 -113 -33 -88 -3 -373 61 -582 49 -160 108 -268 195 -355 59 -59 79 -75 118 -90 14 -6 33 -15 43 -20 51 -30 210 -47 286 -31 78 17 174 52 184 68 4 7 12 13 18 13 11 0 21 8 77 60 60 56 149 207 168 286 4 16 11 34 15 40 9 11 18 46 39 154 7 30 15 69 20 85 13 45 23 286 15 346 -6 46 -14 59 -59 105 -84 83 -92 84 -563 83 -220 -1 -418 -6 -443 -11z m342 -263 c22 -50 -25 -66 -239 -82 -88 -6 -113 2 -113 38 0 42 87 64 268 68 68 1 73 0 84 -24z m422 15 c112 -13 128 -21 124 -63 l-3 -32 -85 1 c-116 1 -210 13 -237 30 -22 14 -31 53 -16 67 9 10 115 8 217 -3z m-531 -149 c83 -32 89 -130 10 -170 -50 -26 -123 20 -123 77 0 42 11 72 26 72 8 0 14 4 14 9 0 8 15 15 42 20 4 0 18 -3 31 -8z m509 -22 c23 -17 28 -28 28 -62 0 -35 -6 -47 -31 -70 -42 -36 -82 -36 -120 2 -37 37 -40 89 -6 124 39 42 75 44 129 6z m-476 -419 c18 -18 157 -52 194 -47 43 5 133 29 140 38 3 3 15 9 28 13 57 18 -7 -78 -82 -121 -32 -19 -171 -16 -209 3 -34 18 -42 25 -75 68 -31 40 -27 77 4 46z" />
                                                    <path
                                                        d="M1020 791 c0 -51 -94 -135 -220 -197 -60 -30 -113 -54 -117 -54 -3 0 -27 -9 -52 -21 -110 -50 -143 -65 -221 -94 -114 -45 -237 -112 -277 -153 -75 -76 -103 -169 -67 -220 l15 -22 1025 0 1024 0 0 85 c0 84 0 85 -27 91 -45 12 -141 74 -196 128 -60 58 -113 130 -146 198 -12 27 -27 50 -32 54 -5 3 -9 16 -9 29 0 13 -4 26 -9 30 -5 3 -12 18 -16 34 -3 16 -14 50 -24 75 l-17 46 -31 -27 c-29 -25 -63 -45 -147 -84 -37 -18 -231 -18 -248 -1 -7 7 -22 12 -34 12 -12 0 -27 7 -34 15 -7 8 -17 15 -23 15 -5 0 -28 15 -51 33 -60 49 -66 51 -66 28z" />
                                                    <path
                                                        d="M2558 212 c-33 -13 -37 -18 -41 -61 -3 -25 -6 -63 -6 -84 l-2 -37 51 0 c60 0 90 24 90 72 0 30 -33 114 -48 121 -4 2 -24 -3 -44 -11z" />
                                                    <path
                                                        d="M2222 103 l3 -68 94 -3 c69 -2 97 1 103 10 4 7 8 39 8 71 l0 57 -105 0 -106 0 3 -67z" />
                                                </g>
                                            </svg>
                                        </a>
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
                                </div><!-- password_confirmation Field -->
                                <div class="col-lg-12">
                                    <label for="password_confirmation" class="common-label">Confirm Password</label>
                                    <input type="password"
                                        class="form-control @error('password_confirmation') is-invalid @enderror common-input"
                                        id="password_confirmation" name="password_confirmation" minlength="8" >
                                    @error('password_confirmation')
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
