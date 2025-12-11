@extends('master')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between mb-3">
        <h3>Approval Levels</h3>
        <a href="{{ route('approval.levels.create') }}" class="btn btn-primary">+ Add Level</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Department</th>
                <th>Level Name</th>
                <th>Sequence</th>
                <th width="100">Actions</th>
            </tr>
        </thead>

        <tbody>
            @foreach($levels as $l)
            <tr>
                <td>{{ $l->department->name }}</td>
                <td>{{ $l->name }}</td>
                <td>{{ $l->sequence }}</td>
                <td>
                    <a href="{{ route('approval.levels.edit', $l->id) }}" class="btn btn-sm btn-info">Edit</a>

                    <form action="{{ route('approval.levels.destroy', $l->id) }}" method="POST" style="display:inline-block">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Delete this level?')" class="btn btn-sm btn-danger">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>

</div>
@endsection
