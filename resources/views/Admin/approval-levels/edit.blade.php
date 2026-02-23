@extends('master')
@section('title', 'Edit Approval Level')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Edit Approval Level</h2>
    <a href="{{ route('approval.levels.index') }}" class="btn btn-secondary vip-btn">
        <i class="bi bi-arrow-left-circle"></i> Go Back
    </a>
</div>

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning">
        {{ session('warning') }}
    </div>
@endif

@if($activeRequestsCount > 0)
    <div class="alert alert-info">
        <strong>Note:</strong> This level has {{ $activeRequestsCount }} active request(s). Changing sequence or department may not be allowed.
    </div>
@endif

<div class="">
    <div class="card-body">

    <form action="{{ route('approval.levels.update', $level->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Department Name <span class="text-danger">*</span></label>
            <select name="department_id" class="form-control">
                @foreach($departments as $d)
                <option value="{{ $d->id }}" {{ $level->department_id == $d->id ? 'selected' : '' }}>
                    {{ $d->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Level Name</label>
            <input type="text" name="name" class="form-control" value="{{ $level->name }}">
        </div>

        <div class="mb-3">
            <label for="sequence" class="form-label">Order (1,2,3...)</label>
            <input type="number" name="sequence" class="form-control" value="{{ $level->sequence }}">
        </div>


            <div class="d-flex justify-content-end gap-2">
        <button type="submit" class="vip-btn btn-submit">
            <i class="bi bi-arrow-repeat"></i> Update
        </button>
        <a href="{{ route('approval.levels.index') }}" class="btn btn-light vip-btn">
            <i class="bi bi-x-octagon"></i> Cancel
        </a>
            </div>
    </form>
    </div>
</div>
@endsection