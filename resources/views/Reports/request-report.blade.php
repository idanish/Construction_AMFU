@extends('..master')
@section('title', 'request reports page')
@section('content')
    <div class="container mt-5">
  <div class="card shadow-lg">
    <div class="card-header bg-primary text-white">
      <h4>Request Report</h4>
    </div>
    <div class="card-body">
      <form>
        <div class="row mb-3">
          <div class="col-md-6">
            <label class="form-label">From Date</label>
            <input type="date" class="form-control">
          </div>
          <div class="col-md-6">
            <label class="form-label">To Date</label>
            <input type="date" class="form-control">
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
    <label class="form-label">Department</label>
    <select name="department_id" class="form-select">
    <option value="">Select Department</option>
    @foreach($allDepartments as $dept)
        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
    @endforeach
</select>

            </div>
          <div class="col-md-6">  
            <label class="form-label">Status</label>
            <select class="form-select">
              <option value="">All</option>
              <option>Pending</option>
              <option>Approved</option>
              <option>Rejected</option>
            </select>
          </div>
        </div>
        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-primary">Generate</button>
          <div>
            <button type="button" class="btn btn-success">Export Excel</button>
            <button type="button" class="btn btn-danger">Export PDF</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection
