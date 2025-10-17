@extends('master')
@section('title', 'Edit Request')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1>Edit Request</h1>
                <form action="{{ route('requests.update', $request->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="requestor_id" value="{{ $request->requestor_id }}">
                    @method('PUT')
                    <div class="form-group mb-3">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" class="form-control"
                            value="{{ old('title', $request->title) }}" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="department_id">Department</label>
                        <select name="department_id" id="department_id" class="form-control" required>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" @if ($department->id == $request->department_id) selected @endif>
                                    {{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="5" required>{{ old('description', $request->description) }}</textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="amount">Estimated Amount</label>
                        <input type="number" name="amount" id="amount" class="form-control" step="0.01"
                            value="{{ old('amount', $request->amount) }}" required>
                    </div>

                    <button type ="submit"class="btn btn-info text-dark vip-btn">
                        <i class="bi bi-arrow-repeat"></i> Update
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
