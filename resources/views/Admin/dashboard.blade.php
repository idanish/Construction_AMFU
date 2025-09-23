@extends('../master')

@section('title', Auth::user()->roles->pluck('name')->first() . ' Dashboard')

@section('content')
    <div class="text-center mt-10">
        <h1 class="text-2xl font-bold text-green-600">
            {{ Auth::user()->roles->pluck('name')->first() }} Dashboard
        </h1>
        <p>Hi {{ Auth::user()->name }}! Welcome Back</p>
    </div>

    <div class="grid grid-cols-4 gap-6 mt-10">
        <div class="p-6 bg-white shadow rounded-lg">
            <h2 class="text-lg font-semibold">💰 Total Budgets</h2>
            <p class="text-2xl font-bold text-green-700">{{ $totalBudgets }}</p>
        </div>
        <div class="p-6 bg-white shadow rounded-lg">
            <h2 class="text-lg font-semibold">🧾 Total Invoices</h2>
            <p class="text-2xl font-bold text-blue-700">{{ $totalInvoices }}</p>
        </div>
        <div class="p-6 bg-white shadow rounded-lg">
            <h2 class="text-lg font-semibold">💳 Payments (This Month)</h2>
            <p class="text-2xl font-bold text-purple-700">{{ number_format($monthlyPayments, 2) }}</p>
        </div>
        <div class="p-6 bg-white shadow rounded-lg">
            <h2 class="text-lg font-semibold">📦 Procurements</h2>
            <p class="text-2xl font-bold text-orange-700">{{ $totalProcurements }}</p>
        </div>
    </div>
@endsection
