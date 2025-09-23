<!DOCTYPE html>
<html>
<head>
    <title>Procurement Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h2>Procurement Report</h2>
    <table>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Quantity</th>
                <th>Cost Estimate</th>
                <th>Department</th>
                <th>Remarks</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($procurements as $procurement)
                <tr>
                    <td>{{ $procurement->item_name }}</td>
                    <td>{{ $procurement->quantity }}</td>
                    <td>{{ number_format($procurement->cost_estimate, 2) }}</td>
                    <td>{{ optional($procurement->department)->name ?? 'N/A' }}</td>
                    <td>{{ $procurement->justification ?? '-' }}</td>
                    <td>{{ $procurement->created_at->format('d-M-Y h:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
