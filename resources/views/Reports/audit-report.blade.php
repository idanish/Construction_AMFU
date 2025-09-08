@extends('..master')
@section('title', 'audit report Page')
@section('content')
  <div class="container mt-5">
  <div class="card shadow-lg">
    <div class="card-header bg-dark text-white">
      <h4>Audit Report</h4>
    </div>
    <div class="card-body">

      {{-- Laravel Form --}}
      <form method="POST" action="{{ route('reports.audit.generate') }}">
        @csrf
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">User</label>
            <input type="text" name="user" class="form-control" placeholder="Enter Username" value="{{ old('user') }}">
          </div>
          <div class="col-md-6">
            <label class="form-label">Action</label>
            <select name="action" class="form-select">
              <option value="">Select Action</option>
              <option value="Login" {{ old('action')=='Login'?'selected':'' }}>Login</option>
              <option value="Create" {{ old('action')=='Create'?'selected':'' }}>Create</option>
              <option value="Update" {{ old('action')=='Update'?'selected':'' }}>Update</option>
              <option value="Delete" {{ old('action')=='Delete'?'selected':'' }}>Delete</option>
            </select>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">From Date</label>
            <input type="date" name="from_date" class="form-control" value="{{ old('from_date') }}">
          </div>
          <div class="col-md-6">
            <label class="form-label">To Date</label>
            <input type="date" name="to_date" class="form-control" value="{{ old('to_date') }}">
          </div>
        </div>

        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-dark">Generate</button>
          <div>
            <a href="{{ route('reports.audit.export', 'excel') }}" class="btn btn-success">Export Excel</a>
            <a href="{{ route('reports.audit.export', 'pdf') }}" class="btn btn-danger">Export PDF</a>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>
@endsection
