@extends('master')

@section('content')
<div class="container">
    <h2>All Approvals</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Approval ID</th>
                <th>Request Title</th>
                <th>Status</th>
                <th>Comments</th>
                <th>Approved By</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($approvals as $approval)
                <tr>
                    <td>{{ $approval->id }}</td>
                    <td>{{ $approval->request->title ?? 'N/A' }}</td>
                    <td>{{ ucfirst($approval->status) }}</td>
                    <td>{{ $approval->comments }}</td>
                    <td>{{ $approval->approver_id }}</td>
                    <td>{{ $approval->created_at->format('d-m-Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection