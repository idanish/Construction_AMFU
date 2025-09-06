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
    <h1 class="text-3xl font-bold text-blue-600 mb-6">PMO Dashboard</h1>
    <p class="text-gray-700 mb-8">Welcome, PMO! Oversee project progress and approvals.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-4">
        <div class="dashboard-card">
            <h2 class="font-semibold text-lg">Ongoing Projects</h2>
            <p class="text-2xl mt-2">8</p>
        </div>
        <div class="dashboard-card">
            <h2 class="font-semibold text-lg">Pending Approvals</h2>
            <p class="text-2xl mt-2">4</p>
        </div>
        <div class="dashboard-card">
            <h2 class="font-semibold text-lg">Completed Milestones</h2>
            <p class="text-2xl mt-2">15</p>
        </div>
    </div>
</div>
@endsection
