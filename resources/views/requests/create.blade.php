@extends('master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h1>Create New Request</h1>
                <form action="{{ route('requests.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="requestor_id" value="{{ auth()->id() }}">

                    <div class="form-group mb-3">
                        <label for="title">Title</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>

                    <select name="department_id" id="department_id" class="form-control" required>
                        <option value="">-- Select Department --</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>

                    <div class="form-group mb-3">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
                    </div>

                    <div class="form-group mb-3">
                        <label for="amount">Estimated Amount</label>
                        <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit Request</button>
                </form>

            </div>
        </div>
    </div>
@endsection
