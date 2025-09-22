<!DOCTYPE html>
<html>
<head>
    <title>Requests Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>Requests Report</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Department</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $req)
            <tr>
                <td>{{ $req->id }}</td>
                <td>{{ $req->user->name ?? 'N/A' }}</td>
                <td>{{ $req->department->name ?? 'N/A' }}</td>
                <td>{{ ucfirst($req->status) }}</td>
                <td>{{ $req->created_at->format('d-M-Y h:i A') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
