<!DOCTYPE html>
<html>
<head>
    <title>Workflow Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>Workflow Report</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Workflow Name</th>
                <th>Created By</th>
                <th>Department</th>
                <th>Status</th>
                <th>Started At</th>
                <th>Completed At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($workflows as $wf)
            <tr>
                <td>{{ $wf->id }}</td>
                <td>{{ $wf->name }}</td>
                <td>{{ $wf->createdBy->name ?? 'N/A' }}</td>
                <td>{{ $wf->department->name ?? 'N/A' }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $wf->status)) }}</td>
                <td>{{ $wf->created_at->format('Y-m-d') }}</td>
                <td>{{ $wf->completed_at ? $wf->completed_at->format('d-M-Y h:i A') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
