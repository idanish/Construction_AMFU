<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Audit Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Audit Report</h2>
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Role</th>
                <th>Action</th>
                <th>Model</th>
                <th>Old Value</th>
                <th>New Value</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $activity)
                <tr>
                    <td>{{ $activity->causer?->name }}</td>
                    <td>{{ $activity->causer?->roles->pluck('name')->join(', ') }}</td>
                    <td>{{ ucfirst($activity->event) }}</td>
                    <td>{{ class_basename($activity->subject_type) }}</td>
                    <td>{{ $activity->properties['old']['title'] ?? '' }}</td>
                    <td>{{ $activity->properties['attributes']['title'] ?? '' }}</td>
                    <td>{{ $activity->created_at->format('d-M-Y h:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
