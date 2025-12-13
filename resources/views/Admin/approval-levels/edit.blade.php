@extends('master')

@section('content')
<div class="container">

    <h3>Edit Approval Level</h3>

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

    <form action="{{ route('approval.levels.update', $level->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Department</label>
            <select name="department_id" class="form-control">
                @foreach($departments as $d)
                <option value="{{ $d->id }}" {{ $level->department_id == $d->id ? 'selected' : '' }}>
                    {{ $d->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Level Name</label>
            <input type="text" name="name" class="form-control" value="{{ $level->name }}">
        </div>

        <div class="mb-3">
            <label>Sequence (1,2,3...)</label>
            <input type="number" name="sequence" class="form-control" value="{{ $level->sequence }}">
        </div>

        <button class="btn btn-success">Update</button>
        <a href="{{ route('approval.levels.index') }}" class="btn btn-secondary">Back</a>
    </form>

</div>
@endsection