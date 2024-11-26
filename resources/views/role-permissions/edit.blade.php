@extends('layouts.app')

@section('content')
<div class="CM-main-content">
    <div class="container-fluid p-0">
        <!-- Table -->
        <div class="task campaingn-table pb-3 common-table">
            <!-- campaigns-contents -->
            <div class="col-lg-12 task campaigns-contents">
                <div class="campaigns-title">
                    <h3>Assign Permissions to Role: {{ $role->name }}</h3>
                </div>
            </div>
            <!-- campaigns-contents -->
        </div>
        <form id="Model-Form" action="{{ route('roles.permissions.update', $role->id) }}" method="POST">
        @csrf
        <div class="accordion" id="permissionsAccordion">
            @foreach ($permissions as $group => $groupPermissions)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading-{{ $group }}">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $group }}" aria-expanded="true" aria-controls="collapse-{{ $group }}">
                            {{ $group }}
                        </button>
                    </h2>
                    <div id="collapse-{{ $group }}" class="accordion-collapse collapse show" aria-labelledby="heading-{{ $group }}" data-bs-parent="#permissionsAccordion">
                        <div class="accordion-body">
                            @foreach ($groupPermissions as $permission)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission-{{ $permission->id }}"
                                    {{ $role->permissions->contains($permission->id) ? 'checked' : '' }} >
                                    <label class="form-check-label" for="permission-{{ $permission->id }}">
                                        {{ $permission->label }}
                                    </label>
                                    <!-- <small class="text-muted">{{ $permission->description }}</small> -->
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="submit" class="common-btn mt-3"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
        <a type="button" class="cancel-btn my-4" href="{{ route('roles.index') }}">
                <i class="fas fa-ban"></i>
                Cancel
            </a>
    </form>
    </div>
</div>
<div class="container">
    
</div>
@endsection

