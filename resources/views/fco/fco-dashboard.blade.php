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
    <h1 class="text-3xl font-bold text-purple-600 mb-6">FCO Dashboard</h1>
    <p class="text-gray-700 mb-8">Welcome, FCO! Manage field compliance and inspections.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-4">
        <div class="dashboard-card">
            <h2 class="font-semibold text-lg">Open Inspections</h2>
            <p class="text-2xl mt-2">12</p>
        </div>
        <div class="dashboard-card">
            <h2 class="font-semibold text-lg">Resolved Issues</h2>
            <p class="text-2xl mt-2">9</p>
        </div>
        <div class="dashboard-card">
            <h2 class="font-semibold text-lg">Reports Submitted</h2>
            <p class="text-2xl mt-2">20</p>
        </div>
    </div>
</div>
@endsection
