@extends('../master')

@section('title', ' Dashboard - ' . Auth::user()->roles->pluck('name')->first() )

@section('content')

<div class="text-center mt-10">
    <h1 class="text-2xl font-bold text-green-600">
        {{ Auth::user()->roles->pluck('name')->first() }} Dashboard
    </h1>
    <p>Hi {{ Auth::user()->name }}! Welcome Back</p>
</div>

<div class="container-fluid py-2">
    <!-- Top Cards -->
    <div class="row g-4">

        @can('read-budget')
        <!-- Total Budgets -->
        <div class="col-lg-3 col-md-6 col-sm-12">
            <div class="p-4 bg-white rounded shadow-sm border-bottom border-5 border-warning">
                <p class="text-muted mb-1">Total Budgets</p>
                <h3 class="fw-bold text-warning">${{ number_format($totalBudgets ?? 0, 2) }}</h3>
            </div>
        </div>
        @endcan

        @can('read-payment')
        <!-- Monthly Payments -->
        <div class="col-md-6 col-lg-3 col-sm-12 ">
            <div class="p-4 bg-white rounded shadow-sm border-bottom border-5 border-success">
                <p class="text-muted mb-1">Payments</p>
                <h3 class="fw-bold text-success">${{ number_format($monthlyPayments ?? 0, 2) }}</h3>
            </div>
        </div>
        @endcan

        @can('read-invoice')
        <!-- Total Invoices -->
        <div class="col-md-6 col-lg-3 col-sm-12 ">
            <div class="p-4 bg-white rounded shadow-sm border-bottom border-5 border-danger">
                <p class="text-muted mb-1">Total Invoices</p>
                <h3 class="fw-bold text-danger">{{ $totalInvoices ?? 0 }}</h3>
            </div>
        </div>
        @endcan

        @can('read-procurement')
        <!-- Procurements -->
        <div class="col-md-6 col-lg-3 col-sm-12 ">
            <div class="p-4 bg-white rounded shadow-sm border-bottom border-5 border-info">
                <p class="text-muted mb-1">Total Procurements</p>
                <h3 class="fw-bold text-info">{{ $totalProcurements ?? 0 }}</h3>
            </div>
        </div>
    </div>
    @endcan

    <div class="row mt-4">
        @can('read-budget')
        <!-- Recent budgets -->
        <div class="col-md-6 col-sm-12">
            <div class="p-4 bg-white rounded shadow-sm">
                <h4>Pending Budgets</h4>
                @foreach($pendingBudget as $key => $budget)
                <a href="{{ route('finance.budgets.index') }}" class="menu-link">
                    <p>{{ $key + 1 }}. The budget from {{ $budget->department->name ?? 'N/A' }} is Currently
                        {{ ucfirst($budget->status) }}
                        with a amount of ${{ number_format($budget->balance, 2) }} </p>
                </a>
                @endforeach
            </div>
        </div>
        @endcan

        @can('read-invoice')
        <!-- Recent Payments -->
        <div class="col-md-6 col-sm-12">
            <div class="p-4 bg-white rounded shadow-sm">
                <h4>Unpaid Invoices</h4>

                @foreach($pendingInvoices as $key => $invoice)
                <a href="{{ route('finance.invoices.index') }}" class="menu-link">
                    <p>{{ $key + 1 }}. The {{ $invoice->invoice_no }} is Currently
                        {{ ucfirst($invoice->status) }}
                        with a amount of ${{ number_format($invoice->amount, 2) }} </p>
                </a>
                @endforeach
            </div>
        </div>
        @endcan
    </div>

    @can('read-request')
    <!-- Tables -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="p-4 bg-white rounded shadow-sm">
                <h4>Pending Requests</h4>
                @foreach($pendingRequest as $key => $pendingRequest)
                <a href="{{ route('requests.index') }}" class="menu-link">
                    <p>{{ $key + 1 }}. The {{ $pendingRequest->title }} is Currently
                        {{ ucfirst($pendingRequest->status) }}
                        with a amount of ${{ number_format($pendingRequest->amount) }} </p>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endcan

@endsection