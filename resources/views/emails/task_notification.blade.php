<!DOCTYPE html>
<html>
<head>
    <title>Task Notification</title>
</head>
<body>
    <p>Hi {{ $task->account_name }},</p>

    <p>A task has been <strong>{{ ucfirst($status) }}</strong>.</p>

    <p><strong>Campaign Name:</strong> {{ $task->campaign->name }} for {{ $task->campaign->client->name }}</p>
    <p><strong>Task Name:</strong> {{ $task->name }}</p>

    <p><a href="{{ url('/tasks/' . $task->id) }}">Login Now</a></p>

    <p>Kind regards,</p>
    <p>The Digital Asset Portal Team</p>
    <p><a href="{{ url('/') }}">Visit Digital Asset Portal</a></p>
</body>
</html>
