<table class="table">
    <thead>
        <tr>
            <th>Impersonator</th>
            <th>Impersonated User</th>
            <th>Started At</th>
            <th>Ended At</th>
        </tr>
    </thead>
    <tbody>
        @foreach($logs as $log)
        <tr>
            <td>{{ $log->impersonator->name }} ({{ $log->impersonator->email }})</td>
            <td>{{ $log->impersonatedUser->name }} ({{ $log->impersonatedUser->email }})</td>
            <td>{{ $log->started_at }}</td>
            <td>{{ $log->ended_at ?? 'In Progress' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $logs->links() }}
