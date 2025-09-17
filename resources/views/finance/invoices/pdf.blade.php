<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_no }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid #000; padding: 8px; }
    </style>
</head>
<body>
    <h2>Invoice #{{ $invoice->invoice_no }}</h2>
    <p><strong>Date:</strong> {{ $invoice->invoice_date }}</p>
    <p><strong>Request:</strong> {{ $invoice->serviceRequest->title ?? 'N/A' }}</p>
    <p><strong>Amount:</strong> {{ number_format($invoice->amount, 2) }}</p>
    <p><strong>Status:</strong> {{ $invoice->status }}</p>
    <p><strong>Notes:</strong> {{ $invoice->notes ?? 'N/A' }}</p>
</body>
</html>
