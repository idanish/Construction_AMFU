@extends('master')

@section('content')
<div class="container">

    <h3>Edit Approval Level</h3>

    <form action="{{ route('approval.levels.update', $level->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Department</label>
            <select name="department_id" class="form-control">
                @foreach($departments as $d)
                <option value="{{ $d->id }}" @if($d->id == $level->department_id) selected @endif>
                    {{ $d->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Level Name</label>
            <input type="text" name="name" value="{{ $level->name }}" class="form-control">
        </div>

        <div class="mb-3">
            <label>Sequence</label>
            <input type="number" name="sequence" value="{{ $level->sequence }}" class="form-control">
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('approval.levels.index') }}" class="btn btn-secondary">Back</a>
    </form>

</div>
@endsection
