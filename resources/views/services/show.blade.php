@extends('master')

@section('content')
<div class="container">
    <h2 class="mb-4">Service Request Details</h2>

    <table class="table table-bordered">
        <tr>
            <th>ID</th>
            <td>{{ $serviceRequest->id }}</td>
        </tr>
        <tr>
            <th>Request No</th>
            <td>{{ $serviceRequest->request_no }}</td>
        </tr>
        <tr>
            <th>Description</th>
            <td>{{ $serviceRequest->description }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $serviceRequest->status }}</td>
        </tr>
        <tr>
            <th>Created At</th>
            <td>{{ $serviceRequest->created_at->format('d-m-Y') }}</td>
        </tr>
    </table>

    <a href="{{ route('services.index') }}" class="btn btn-primary">Back</a>
</div>
@endsection
