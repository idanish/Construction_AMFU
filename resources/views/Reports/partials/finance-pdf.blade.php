<!DOCTYPE html>
<html>
<head>
    <title>Finance Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Finance Report</h2>
    <table>
        <thead>
            <tr>
                <th>Department</th>
                <th>Allocated</th>
                <th>Spent</th>
                <th>Balance</th>
                <th>Status</th>
                <th>Transaction</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($budgets as $budget)
            <tr>
                <td>{{ $budget->department->name ?? '-' }}</td>
                <td>{{ $budget->allocated }}</td>
                <td>{{ $budget->spent }}</td>
                <td>{{ $budget->balance }}</td>
                <td>{{ $budget->status }}</td>
                <td>{{ $budget->transaction_no }}</td>
                <td>{{ $budget->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
