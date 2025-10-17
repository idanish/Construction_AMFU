@extends('../master')

@section('title', Auth::user()->roles->pluck('name')->first() . ' Dashboard')

@section('content')
<div class="text-center mt-10">
    <h1 class="text-2xl font-bold text-green-600">
        {{ Auth::user()->roles->pluck('name')->first() }} Dashboard
    </h1>
    <p>Hi {{ Auth::user()->name }}! Welcome Back</p>
</div>

<div class="container-fluid py-4">
    <!-- Top Cards -->
    <div class="row">
        <!-- Total Budgets -->
        <div class="col-sm-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <h5 class="card-title fw-semibold d-block text-muted">💰 Total Budgets</h5>
                    <h3 class="card-text mt-2 text-success">${{ number_format($totalBudgets ?? 0, 2) }}</h3>
                </div>
            </div>
        </div>

        <!-- Total Invoices -->
        <div class="col-sm-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <h5 class="card-title fw-semibold d-block text-muted">🧾 Total Invoices</h5>
                    <h3 class="card-text mt-2 text-success">{{ $totalInvoices ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <!-- Monthly Payments -->
        <div class="col-sm-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <h5 class="card-title fw-semibold d-block text-muted">💳 Payments</h5>
                    <!-- <p class="text-muted">(This Month)</p> -->
                    <h3 class="card-text mt-2 text-success">${{ number_format($monthlyPayments ?? 0, 2) }}</h3>
                </div>
            </div>
        </div>

        <!-- Procurements -->
        <div class="col-sm-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body text-center">
                    <h5 class="card-title fw-semibold d-block text-muted">📦 Procurements</h5>
                    <h3 class="card-text mt-2 text-success">{{ $totalProcurements ?? 0 }}</h3>
                </div>
            </div>
        </div>
    </div>


    <!-- Graphs -->
    <!-- <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light fw-bold">📈 Monthly Revenue (Payments)</div>
                <div class="card-body">
                    <canvas id="revenueChart" height="150"></canvas>
                </div>
            </div>
        </div>
    </div> -->

    <!-- Tables -->
   



    <!-- Alerts -->
    <div class="card shadow-sm border-0 my-4">
        <div class="card-header bg-light fw-bold">⚠️ Alerts</div>
        <div class="card-body">
            <ul>
                <!-- Unpaid Invoices -->
                @foreach ($unpaidInvoices ?? [] as $inv)
                <li>🧾 Unpaid Invoice #{{ $inv->id }} due on {{ $inv->due_date->format('d M Y') }}</li>
                @endforeach

                <!-- Unpaid Payments -->
                @foreach ($unpaidPayments ?? [] as $pay)
                <li>💳 Unpaid Payment #{{ $pay->id }} of {{ number_format($pay->amount, 2) }}
                    (Due: {{ $pay->payment_date ? $pay->payment_date->format('d M Y') : 'N/A' }})
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- <script>
    const monthlyRevenues = @json($monthlyRevenues);

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: Object.keys(monthlyRevenues).map(m => 'Month ' + m),
            datasets: [{
                label: 'Revenue',
                data: Object.values(monthlyRevenues),
                borderColor: 'green',
                fill: false
            }]
        }
    });
</script> --}}


@endsection