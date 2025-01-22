@extends('layouts.app')

@section('content')
    <style>
        #hover-message {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
    <div class="CM-main-content">
        <div class="container-fluid p-0">
            <!-- Table -->
            <div class=" p-3">
                <!-- campaigns-contents -->
                <div class="col-lg-12 task campaigns-contents">
                    <div class="campaigns-title">
                        <h3>CLIENT PARTNERS</h3>
                    </div>
                    @if (Auth::user()->hasRolePermission('client-groups.store'))
                        {{-- @if (Auth::user()->hasRolePermission('clientpartner.create')) --}}
                        <a href="{{ route('clientpartner.create') }}" class="Edit-My-Profile-btn"> <i
                                class="fa-solid fa-plus"></i> Add Partner</a>
                        {{-- @endif --}}
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
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($partners as $index => $partner)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $partner->user->name }}</td>
                                    <td>
                                        <a href="mailto:{{ $partner->user->email }}"
                                            class="text-decoration-none active-email">{{ $partner->user->email }}</a>
                                    </td>

                                    <td>
                                        <span>
                                            <p class="status {{ $partner->user->is_active ? 'green' : 'red' }}">
                                                {{ $partner->user->is_active ? 'Active' : 'Inactive' }}</p>
                                        </span>
                                    </td>
                                    <td style="display: flex;">
                                        @if ($partner->partnerexist == 0)
                                            <!-- Show Delete Form -->
                                            <form id="Model-Form" action="{{ route('users.destroy', $partner->id) }}"
                                                method="POST" class="d-inline-block"
                                                onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn trash btn-sm me-1">
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>
                                            </form>
                                        @else
                                            <!-- Show Disabled Icon with Tooltip -->
                                            <div style="position: relative; display: inline-block;">
                                                <!-- Trash Button -->
                                                <button type="button" class="btn trash btn-sm me-1" id="trash-button"
                                                    disabled>
                                                    <i class="fa-regular fa-trash-can"></i>
                                                </button>

                                                <!-- Hidden Message Div -->
                                                <div id="hover-message"
                                                    style="display: none; position: absolute; top: 120%; left: 50%; transform: translateX(-50%); 
                                                     padding: 8px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; 
                                                     font-size: 14px; white-space: nowrap; z-index: 100;">
                                                    This partner is assigned to a campaign and cannot be deleted.
                                                </div>
                                            </div>
                                        @endif
                                        <a href="{{ route('clientpartner.edit', $partner->user->id) }}" class="btn search ">
                                            <i class="bx bx-edit"></i>
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
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            // Show the message when hovering over the button
            $('#trash-button').hover(
                function() {
                    $('#hover-message').fadeIn();
                },
                function() {
                    $('#hover-message').fadeOut();
                }
            );
        });
    </script>
@endsection
