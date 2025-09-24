@extends('master')

@section('content')
    <div class="container py-4">
        <h3>Approval for Request #{{ $requestModel->id }}</h3>

        <form action="{{ route('approvals.store', $requestModel->id) }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="approved">Approve</option>
                    <option value="rejected">Reject</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label>Comments</label>
                <textarea name="comments" class="form-control"></textarea>
            </div>
            <button type="submit" class="vip-btn btn-submit">
                <i class="bi bi-check-lg"></i> Submit
            </button>

        </form>
    </div>
@endsection
