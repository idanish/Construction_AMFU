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
        <div class="row g-4 mb-4">
            <!-- Total Budgets -->
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body text-center">
                        <div class="avatar bg-light-success p-3 rounded-circle mb-3 mx-auto">
                            <i class="fas fa-chart-pie text-success" style="font-size:28px;"></i>
                        </div>
                        <span class="fw-semibold d-block text-muted">💰 Total Budgets</span>
                        <h3 class="card-title mt-2 text-success">{{ number_format($totalBudgets ?? 0, 2) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Total Invoices -->
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body text-center">
                        <div class="avatar bg-light-primary p-3 rounded-circle mb-3 mx-auto">
                            <i class="fas fa-file-invoice text-primary" style="font-size:28px;"></i>
                        </div>
                        <span class="fw-semibold d-block text-muted">🧾 Total Invoices</span>
                        <h3 class="card-title mt-2 text-primary">{{ $totalInvoices ?? 0 }}</h3>
                    </div>
                </div>
            </div>

            <!-- Monthly Payments -->
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body text-center">
                        <div class="avatar bg-light-purple p-3 rounded-circle mb-3 mx-auto">
                            <i class="fas fa-credit-card text-purple" style="font-size:28px;"></i>
                        </div>
                        <span class="fw-semibold d-block text-muted">💳 Payments (This Month)</span>
                        <h3 class="card-title mt-2 text-purple">{{ number_format($monthlyPayments ?? 0, 2) }}</h3>
                    </div>
                </div>
            </div>

            <!-- Procurements -->
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body text-center">
                        <div class="avatar bg-light-warning p-3 rounded-circle mb-3 mx-auto">
                            <i class="fas fa-boxes text-warning" style="font-size:28px;"></i>
                        </div>
                        <span class="fw-semibold d-block text-muted">📦 Procurements</span>
                        <h3 class="card-title mt-2 text-warning">{{ $totalProcurements ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>


        <!-- Graphs -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light fw-bold">📈 Monthly Revenue (Payments)</div>
                    <div class="card-body">
                        <canvas id="revenueChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tables -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-light fw-bold">🧾 Latest Invoices / Payments</div>
                    <div class="card-body">
                        <table class="table table-sm table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Invoice</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @forelse ($latestPayments as $p)
                                    <tr>
                                        <td>{{ $p->id }}</td>
                                        <td>{{ $p->invoice_id }}</td>
                                        <td>{{ ucfirst($p->status) }}</td>
                                        <td>{{ number_format($p->amount, 2) }}</td>
                                        <td>{{ $p->payment_date ? $p->payment_date->format('d M Y') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            No payments found
                                        </td>
                                    </tr>
                                @endforelse --}}
                            </tbody>
                        </table>

                        <!-- Pagination Links -->
                        <div class="d-flex justify-content-center">
                            {{-- {{ $latestPayments->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Alerts -->
        <!-- Alerts -->
        <div class="card shadow-sm border-0">
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
