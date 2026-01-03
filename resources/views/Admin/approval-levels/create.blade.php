@extends('master')
@section('title', 'Add Approval Level')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Add Approval Level</h2>
    <a href="{{ route('approval.levels.index') }}" class="btn btn-secondary vip-btn">
        <i class="bi bi-arrow-left-circle"></i> Go Back
    </a>
</div>

<div class="">
    <div class="card-body">

        <form action="{{ route('approval.levels.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Department Name <span class="text-danger">*</span></label>
                <select name="department_id" class="form-control">
                    @foreach($departments as $d)
                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Level Name</label>
                <input type="text" name="name" class="form-control">
            </div>

            <div class="mb-3">
                <label for="sequence" class="form-label">Sequence (1,2,3...)</label>
                <input type="number" name="sequence" class="form-control">
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="vip-btn btn-submit">
                    <i class="bi bi-plus-lg"></i> Add
                </button>
                <a href="{{ route('approval.levels.index') }}" class="btn btn-light vip-btn">
                    <i class="bi bi-x-octagon"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection