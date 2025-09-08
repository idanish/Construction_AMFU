@extends('..master')
@section('title', 'finnance reports page')
@section('content')

<div class="container mt-5">
  <div class="card shadow-lg">
    <div class="card-header bg-success text-white">
      <h4>Finance Report</h4>
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
            <select class="form-select">
              <option value="">Select Department</option>
              <option>Finance</option>
              <option>HR</option>
              <option>IT</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Report Type</label>
            <select class="form-select">
              <option value="">Select Type</option>
              <option>Budget vs Expense</option>
              <option>Procurement Spend</option>
            </select>
          </div>
        </div>
        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-success">Generate</button>
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
