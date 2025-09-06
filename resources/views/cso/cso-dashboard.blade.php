@extends('../master')

@section('content')
<style>
    .dashboard-card {
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        padding: 20px;
        background-color: #fff;
        transition: transform 0.3s;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
    }
</style>

<div class="text-center mt-16">
    <h1 class="text-3xl font-bold text-red-600 mb-6">CSO Dashboard</h1>
    <p class="text-gray-700 mb-8">Welcome, CSO! Monitor compliance and safety operations.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-4">
        <div class="dashboard-card">
            <h2 class="font-semibold text-lg">Total Projects</h2>
            <p class="text-2xl mt-2">12</p>
        </div>
        <div class="dashboard-card">
            <h2 class="font-semibold text-lg">Pending Reports</h2>
            <p class="text-2xl mt-2">5</p>
        </div>
        <div class="dashboard-card">
            <h2 class="font-semibold text-lg">Compliance Alerts</h2>
            <p class="text-2xl mt-2">3</p>
        </div>
    </div>
</div>
@endsection
