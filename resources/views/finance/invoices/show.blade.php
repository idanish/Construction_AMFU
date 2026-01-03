@extends('master')
@section('title', 'Invoice Details')
@section('content')

<style>
    @page {
        margin: 2cm 2cm 3cm 2cm;
    }

    .invoice-body {
        font-family: 'Helvetica Neue', Arial, sans-serif;
        font-size: 13px;
        color: #333;
        line-height: 1.5;
        margin: 0;
        padding: 20px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1cm;
    }

    .header-left {
        width: 60%;
    }

    .logo {
        height: 50px;
        display: block;
        margin-bottom: 8px;
    }

    .company-info {
        line-height: 1.4;
    }

    .invoice-meta {
        text-align: right;
    }

    .invoice-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .recipient {
        margin-bottom: 1cm;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        border-bottom: 1px solid #ddd;
        margin-bottom: 1.5cm;
    }

    th,
    td {
        border-top: 1px solid #ddd;
        padding: 6px 4px;
        text-align: left;
        vertical-align: top;
    }

    th {
        background: #f5f5f5;
        font-weight: 600;
    }

    .totals {
        width: 40%;
        float: right;
        margin-bottom: 0.5cm;
    }

    .totals td {
        padding: 4px 0;
    }

    .bottom-container {
        display: flex;
        justify-content: space-between;
        margin-top: 1.5cm;
        margin-bottom: 1.5cm;
        page-break-inside: avoid;
    }

    .sender-details,
    .bank-info {
        width: 48%;
    }

    .bank-info {
        text-align: right;
    }

    .notes {
        clear: both;
        margin-bottom: 1.5cm;
        text-align: center;
    }
</style>

<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center">
        <div class="page-title-heading m-0">
            <div class="page-title-icon">
                <i class="pe-7s-cash icon-gradient bg-tempting-azure"></i>
            </div>
            <div class="h4 m-0">
                Invoice Details
            </div>
        </div>

        <div class="page-title-actions">
            <a href="{{ route('finance.invoices.index') }}" class="btn btn-secondary vip-btn">
                <i class="bi bi-arrow-left"></i> Back
            </a>

            @can('update-invoice')
            <a href="{{ route('finance.invoices.edit', $invoice->id) }}" class="btn btn-warning vip-btn">
                <i class="fas fa-edit"></i> Edit
            </a>
            @endcan
        </div>
    </div>
</div>


<div class="invoice-body">
    <div class="header">
        <div class="header-left">
            <!--<img class="logo" src="" alt="Logo">-->AMFU
        </div>
        <div class="invoice-meta">
            <div class="invoice-title">Invoice</div>
            <div><strong>Invoice No.:</strong> {{ $invoice->invoice_no }}</div>
            <div><strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d-M-Y') }}</div>
            <div><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('d-M-Y') }}</div>
        </div>
    </div>

    <div class="recipient">
        <strong>Recipient:</strong><br>{{ $invoice->vendor_name }}

    </div>

    <table>
        <thead>
            <tr>
                <th style="width:5%">#</th>
                <th>Description</th>
                <th style="width:10%; text-align: right;">Qty</th>
                <th style="width:15%; text-align: right;">Unit Price</th>
                <th style="width:15%; text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>{{ $invoice->invoice_no }}</td>
                <td style="text-align: right;">1</td>
                <td style="text-align: right;">${{ number_format($invoice->amount, 2) }}</td>
                <td style="text-align: right;">${{ number_format($invoice->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td style="text-align:right;">${{ number_format($invoice->amount, 2) }}</td>
            </tr>
            <!-- <tr><td>VAT (19%):</td><td style="text-align:right;">$ 0</td></tr> -->
            <tr>
                <td><strong>Total:</strong></td>
                <td style="text-align:right;"><strong>${{ number_format($invoice->amount, 2) }}</strong></td>
            </tr>
        </table>
    </div>


    <div class="notes">
        <!-- Notes -->
    </div>


    <div class="bottom-container">
        <div class="sender-details">
            <strong>AMFU</strong><br>
            <!-- Address<br><br>
            Tax ID: Tax ID<br>
            Phone: Phone<br>
            Email: Email -->
        </div>
    </div>

    <div class="">
        <div class="card-body">
            <a href="{{ route('finance.invoices.download', $invoice->id) }}" class="btn btn-secondary vip-btn mb-1"> <i
                    class="bi bi-download"></i> Download </a>
        </div>
    </div>


    @endsection