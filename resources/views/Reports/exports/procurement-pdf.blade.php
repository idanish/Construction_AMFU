<!DOCTYPE html>
<html>
<head>
    <title>Procurement Analysis Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>Procurement Analysis Report</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Supplier</th>
                <th>Status</th>
                <th>Date</th>
                <th>Total Items</th>
            </tr>
        </thead>
        <tbody>
            @foreach($procurements as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->supplier->name ?? 'N/A' }}</td>
                <td>{{ ucfirst($p->status) }}</td>
                <td>{{ $p->procurement_date }}</td>
                <td>{{ $p->items->count() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
