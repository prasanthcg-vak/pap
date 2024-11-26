<!-- resources/views/groups/create.blade.php -->
<h1>Create Group</h1>
<form id="Model-Form" action="{{ route('groups.store') }}" method="POST">
    @csrf
    <label>Client Group Name:</label>
    <input type="text" name="client_group_name" required>
    <button type="submit">Save</button>
</form>
