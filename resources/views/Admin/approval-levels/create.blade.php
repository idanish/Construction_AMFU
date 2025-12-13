@extends('master')

@section('content')
<div class="container">

    <h3>Create Approval Level</h3>

    <form action="{{ route('approval.levels.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Department</label>
            <select name="department_id" class="form-control">
                @foreach($departments as $d)
                <option value="{{ $d->id }}">{{ $d->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Level Name</label>
            <input type="text" name="name" class="form-control">
        </div>

        <div class="mb-3">
            <label>Sequence (1,2,3...)</label>
            <input type="number" name="sequence" class="form-control">
        </div>

        <button class="btn btn-success">Save</button>
        <a href="{{ route('approval.levels.index') }}" class="btn btn-secondary">Back</a>
    </form>

</div>
@endsection
