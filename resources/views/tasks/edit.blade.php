@extends('layouts.app')
<style>
    .check {
        margin: .5rem !important;
    }

    .status {
        float: right;
    }

    .status span {
        color: green;
    }
</style>
@section('content')
    <div class="container">
        <h1>{{ isset($task) ? 'Edit Task' : 'Create Task' }}</h1>

        <form action="{{ isset($task) ? route('tasks.update', $task->id) : route('tasks.store') }}" method="POST">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @csrf
            @if (isset($task))
                @method('PUT')
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="campaign_id">Campaign</label>
                        <select name="campaign_id" id="campaign_id" class="form-control">
                            <option value="">Select Campaign</option>
                            @foreach ($campaigns as $campaign)
                                <option value="{{ $campaign->id }}"
                                    {{ isset($task) && $task->campaign_id == $campaign->id ? 'selected' : '' }}>
                                    {{ $campaign->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="status">
                            <label for="task_urgent"><b>Status :</b> <span>STARTED</span></label>
                            <input type="hidden" name="status" id="status" class="form-control"
                                value="{{ isset($task) ? $task->status : '' }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Task Name</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ isset($task) ? $task->name : '' }}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date_required">Date Required</label>
                        <input type="date" name="date_required" id="date_required" class="form-control"
                            value="{{ isset($task) ? $task->date_required : '' }}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="task_urgent">Urgent</label>
                        <input type="checkbox" name="task_urgent" id="task_urgent" class="check" value="1"
                            {{ isset($task) && $task->task_urgent ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="asset_type">Asset Type</label>
                        <input type="text" name="asset_type" id="asset_type" disabled class="form-control"
                            value="{{ isset($task) ? $task->asset_type : '' }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }} "
                                    {{ isset($task) && $task->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="size_width">Size (Width)</label>
                        <input type="number" name="size_width" id="size_width" class="form-control"
                            value="{{ isset($task) ? $task->size_width : '' }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="size_height">Size (Height)</label>
                        <input type="number" name="size_height" id="size_height" class="form-control"
                            value="{{ isset($task) ? $task->size_height : '' }}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="description">Task Brief</label>
                    <textarea name="description" id="description" class="form-control" rows="3" required>{{ isset($task) ? $task->description : '' }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">{{ isset($task) ? 'Update Task' : 'Save Task' }}</button>
        </form>
    </div>
@endsection
