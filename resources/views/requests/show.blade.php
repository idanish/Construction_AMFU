@extends('master')

@section('content')
<div class="container">
    <h2>Request #{{ $requestModel->id }}</h2>

    <p><strong>Description:</strong> {{ $requestModel->description }}</p>
    <p><strong>Amount:</strong> {{ $requestModel->amount }}</p>
    <p><strong>Status:</strong> <span class="badge bg-info">{{ ucfirst($requestModel->status) }}</span></p>
    <p><strong>Department:</strong> {{ $requestModel->department->name ?? '-' }}</p>
    <p><strong>Requestor:</strong> {{ $requestModel->requestor->name ?? '-' }}</p>
    <p><strong>Comments:</strong> {{ $requestModel->comments ?? '-' }}</p>

    <h5>Attachments:</h5>
    <ul>
       @if($requestModel->attachments->count() > 0)
        @foreach($requestModel->attachments as $attachment)
            <li>
                <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank">
                    {{ $attachment->file_name }}
                </a>
            </li>
        @endforeach
    @else
        <li>No attachments uploaded</li>
    @endif
    </ul>

    <a href="{{ route('requests.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
