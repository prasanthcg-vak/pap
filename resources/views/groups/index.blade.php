<!-- resources/views/groups/index.blade.php -->

@extends('layouts.app')

@section('content')
    <!-- Table -->
    <div class="CM-main-content">
        <div class="container-fluid p-0">
            <!-- Table -->
            <div class="campaingn-table pb-3 common-table">

                <!-- campaigns-contents -->
                <div class="col-lg-12 task campaigns-contents">
                    <div class="campaigns-title">
                        <h3>GROUPS</h3>
                    </div>

                    <a href="#" class="create-task-btn" data-bs-toggle="modal" data-bs-target="#creategroup">Create
                        Group</a>

                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- campaigns-contents -->
                <div class="table-wrapper">
                    <table id="add-row">
                        <thead>
                            <tr>

                                <th class="slno">
                                    <span>S.No</span>
                                </th>
                                <th class="campaingn-title1">
                                    <span>Group Name</span>
                                </th>
                                <th class="active">
                                    <span>Action</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groups as $group)
                                <tr>
                                    <td class="slno">
                                        <span>{{ $loop->iteration }}</span>
                                    </td>
                                    <td class="campaingn-title1">
                                        <span>{{ $group->client_group_name }}</span>
                                    </td>
                                    <td class="active action-btn-icons">
                                        <!-- Edit Button -->
                                        <button type="button" class="btn search" onclick="editGroup({{ $group }})">
                                            <i class="bx bx-edit"></i>
                                        </button>

                                        <!-- Delete Form -->
                                        <form action="{{ route('groups.destroy', $group->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this group?');"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn delete">
                                                <i class="bx bx-trash"></i>
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
    <!-- Modal -->
    <div class="modal fade createTask-modal" id="creategroup" tabindex="-1" aria-labelledby="ModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalTitle">Create Group</h1>
                    <span class="btn-close" id="model-close" data-bs-dismiss="modal" aria-label="Close"></span>
                </div>
                <div class="modal-body">
                    <form id="groupForm" action="{{ route('groups.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="_method" value="POST" id="groupFormMethod">
                        <input type="hidden" id="group_id" name="group_id">

                        <div class="row m-0">
                            <div class="col-xl-12">
                                <input type="text" id="client_group_name" name="client_group_name" class="form-control"
                                    placeholder="Enter Client Group Name" required>
                            </div>
                        </div>
                        <div class="sic-action-btns d-flex justify-content-md-end justify-content-center flex-wrap m-4">
                            <div class="sic-btn">
                                <span class="btn link-asset" data-bs-dismiss="modal" aria-label="Close">Cancel</span>
                            </div>
                            <div class="sic-btn">
                                <button type="submit" class="btn download" id="save">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function editGroup(group) {
            // Update modal title and form action for editing
            document.getElementById('modalTitle').innerText = 'Edit Group';
            document.getElementById('groupForm').action = `/groups/${group.id}`;
            document.getElementById('groupFormMethod').value = 'PUT';

            // Populate the form with existing group data
            document.getElementById('group_id').value = group.id;
            document.getElementById('client_group_name').value = group.client_group_name;

            // Show the modal
            $('#creategroup').modal('show');
        }

        function resetGroupModal() {
            // Reset the form to create mode
            document.getElementById('modalTitle').innerText = 'Create Group';
            document.getElementById('groupForm').action = '{{ route('groups.store') }}';
            document.getElementById('groupFormMethod').value = 'POST';

            // Clear form fields
            document.getElementById('group_id').value = '';
            document.getElementById('client_group_name').value = '';
        }

        $('#creategroup').on('hidden.bs.modal', function() {
            resetGroupModal();
        });
    </script>


@endsection
