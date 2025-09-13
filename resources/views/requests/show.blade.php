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
        @foreach($requestModel->getMedia('attachments') as $media)
            <li>
                <a href="{{ $media->getUrl() }}" target="_blank">{{ $media->file_name }}</a>
            </li>
        @endforeach
    </ul>

    <a href="{{ route('requests.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection
