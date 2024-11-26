<!-- resources/views/groups/edit.blade.php -->
<h1>Edit Group</h1>
<form id="Model-Form" action="{{ route('groups.update', $group->id) }}" method="POST">
    @csrf
    @method('PUT')
    <label>Client Group Name:</label>
    <input type="text" name="client_group_name" value="{{ $group->client_group_name }}" required>
    <button type="submit">Update</button>
</form>
