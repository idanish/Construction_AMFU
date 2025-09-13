@extends('master')

@section('content')
<div class="container">
    <h2>Edit Request #{{ $requestModel->id }}</h2>

    <form action="{{ route('requests.update', $requestModel->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="mb-3">
            <label for="department_id" class="form-label">Department</label>
            <select name="department_id" id="department_id" class="form-control" required>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ $requestModel->department_id == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="3" class="form-control" required>{{ $requestModel->description }}</textarea>
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" name="amount" id="amount" class="form-control" value="{{ $requestModel->amount }}" required>
        </div>

        <div class="mb-3">
            <label for="comments" class="form-label">Comments</label>
            <textarea name="comments" id="comments" rows="2" class="form-control">{{ $requestModel->comments }}</textarea>
        </div>

        <div class="mb-3">
            <label for="attachments" class="form-label">New Attachments</label>
            <input type="file" name="attachments[]" class="form-control" multiple>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('requests.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
