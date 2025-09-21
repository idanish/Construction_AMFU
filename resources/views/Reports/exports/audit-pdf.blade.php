<!DOCTYPE html>
<html>
<head>
    <title>Audit Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h2>Audit Report</h2>
    <table>
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Invoice No</th>
                <th>Request Description</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Payment Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $pay)
            <tr>
                <td>{{ $pay->id }}</td>
                <td>{{ $pay->invoice->invoice_no ?? 'N/A' }}</td>
                <td>{{ $pay->invoice->request->description ?? 'N/A' }}</td>
                <td>{{ $pay->amount }}</td>
                <td>{{ ucfirst($pay->status) }}</td>
                <td>{{ $pay->payment_date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
