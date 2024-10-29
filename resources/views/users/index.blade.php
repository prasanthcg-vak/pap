@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Users</h1>


    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>

                        <a href="{{ route('users.roles.edit', $user->id) }}" class="btn btn-success">Assign role</a>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
