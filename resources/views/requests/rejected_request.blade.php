@extends('master')
@section('title', 'Rejected Requets')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper d-flex justify-content-between align-items-center">
            <div class="page-title-heading m-0">
                <div class="page-title-icon">
                    <i class="pe-7s-close-circle icon-gradient bg-love-kiss"></i>
                </div>
            </div>
        </div>
    </div>



    {{-- Rejected Requests --}}
    @can('read-request')
    <div class="table-responsive-lg mb-4">
        <h5 class="mb-3">💰 Rejected Requests</h5>
        <table class="table table-bordered table-striped">
            <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark">
                <tr>
                    <th>S.NO.</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Requestor</th>
                    <th>Amount</th>
                    <th>Rejection Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rejectedRequest as $key => $rejectedRequest)
                    <tr class="text-center align-middle">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $rejectedRequest->title }}</td>
                        <td>{{ $rejectedRequest->description }}</td>
                        <td>{{ $rejectedRequest->title }}</td>
                        <td>{{ $rejectedRequest->requestor->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($rejectedRequest->updated_at)->format('d-M-Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No rejected request found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endcan



    {{-- Rejected Procurements --}}
    @can('read-procurement')
    <div class="table-responsive-lg mb-4">
        <h5 class="mb-3">🛑 Rejected Procurements</h5>
        <table class="table table-bordered table-striped">
            <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark">
                <tr>
                    <th>S.NO.</th>
                    <th>Item Name</th>
                    <th>Department</th>
                    <th>Rejection Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rejectedProcurement as $key => $procurement)
                    <tr class="text-center align-middle">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $procurement->item_name }}</td>
                        <td>{{ $procurement->department->name ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($procurement->updated_at)->format('d-M-Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No rejected procurements found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endcan


    {{-- Rejected Budget Requests --}}
    @can('read-budget')
    <div class="table-responsive-lg mb-4">
        <h5 class="mb-3">📊 Rejected Budget Requests</h5>
        <table class="table table-bordered table-striped">
            <thead class="table thead-dark text-center align-middle fw-bold bg-light text-dark">
                <tr>
                    <th>S.NO.</th>
                    <th>Department</th>
                    <th>Amount</th>
                    <th>Rejection Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rejectedBudget as $key => $budget)
                    <tr class="text-center align-middle">
                        <td>{{ $budget->id }}</td>
                        <td>{{ $budget->department->name ?? 'N/A' }}</td>
                        <td>{{ number_format($budget->balance, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($budget->updated_at)->format('d-M-Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No rejected budget requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endcan

@endsection
