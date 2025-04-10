<div class="modal fade" id="clientModal" tabindex="-1" aria-labelledby="clientModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form id="clientForm" enctype="multipart/form-data">
        <input type="hidden" name="client_id" id="client_id">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clientModalLabel">Add/Edit Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-6">
                    <label>Client Name</label>
                    <input type="text" name="name" id="name" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Description</label>
                    <input type="text" name="description" id="description" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Admin Name</label>
                    <input type="text" name="client_admin_name" id="client_admin_name" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Admin Email</label>
                    <input type="email" name="email" id="email" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Role</label>
                    <select name="role_id" id="role_id" class="form-control">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
                <div class="col-md-6">
                    <label>Logo</label>
                    <input type="file" name="logo" class="form-control">
                </div>
                <div class="col-md-6 form-check mt-3">
                    <input type="checkbox" class="form-check-input" name="is_active" id="is_active">
                    <label class="form-check-label" for="is_active">Is Active</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save Client</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </form>
  </div>
</div>
