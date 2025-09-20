@extends('master')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1>Request Details</h1>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $request->title }}</h5>
                    <p class="card-text"><strong>Requestor:</strong> {{ $request->requestor->name }}</p>
                    <p class="card-text"><strong>Department:</strong> {{ $request->department->name }}</p>
                    <p class="card-text"><strong>Amount:</strong> {{ $request->amount }}</p>
                    <p class="card-text"><strong>Status:</strong> 
                        @if($request->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($request->status == 'approved')
                            <span class="badge bg-success">Approved</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </p>
                    <hr>
                    <p class="card-text"><strong>Description:</strong></p>
                    <p>{{ $request->description }}</p>
                    <hr>
                    <p class="card-text"><strong>Comments:</strong></p>
                    <p>{{ $request->comments }}</p>

                    <a href="{{ route('requests.index') }}" class="btn btn-secondary mt-3">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection