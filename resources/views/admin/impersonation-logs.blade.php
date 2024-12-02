@extends('layouts.app')

@section('content')
    <div class="CM-main-content">
        <div class="table-wrapper">
            <table id="assetTypesTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Impersonator</th>
                        <th>Impersonated User</th>
                        <th>Started At</th>
                        <th>Ended At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $log)
                    <tr>
                        <td></td>
                        <td>{{ $log->impersonator->name }} ({{ $log->impersonator->email }})</td>
                        <td>{{ $log->impersonatedUser->name }} ({{ $log->impersonatedUser->email }})</td>
                        <td>{{ $log->started_at }}</td>
                        <td>{{ $log->ended_at ?? 'In Progress' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection