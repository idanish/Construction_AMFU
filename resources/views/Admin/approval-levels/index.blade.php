@extends('master')
@section('title', 'Approval Level')
@section('content')

<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center">
        <div class="page-title-heading m-0">
            <div class="page-title-icon">
                <i class="pe-7s-note2 icon-gradient bg-tempting-azure"></i>
            </div>
            <div class="h4 m-0">Approval Levels</div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block">
                <a href="{{ route('approval.levels.create') }}" class="btn btn-download mb-3 vip-btn">
                    <i class="bi bi-plus-circle"></i> Add Level
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Success Message --}}
@if (session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Error Message --}}
@if (session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="table-responsive">
    <table class="table datatable table-bordered table-striped table-sm w-100">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Department</th>
                <th>Level Name</th>
                <th>Order</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($levels as $key => $l)
            <tr class="text-center align-middle">
                <td>{{ $key + 1 }}</td>
                <td>{{ $l->department->name }}</td>
                <td>{{ $l->name }}</td>
                <td>{{ $l->sequence }}</td>
                <td>
                    <a href="{{ route('approval.levels.edit', $l->id) }}" class="btn btn-sm btn-download vip-btn"><i
                            class="fas fa-edit"></i> Edit</a>

                    <form action="{{ route('approval.levels.destroy', $l->id) }}" method="POST"
                        style="display:inline-block">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('Delete this level?')" class="btn btn-sm btn-danger vip-btn">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>

</div>
@endsection