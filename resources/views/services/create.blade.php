@extends('master')

@section('content')
<div class="container">
    <h2 class="mb-4">Add New Service Request</h2>

    <form action="{{ route('services.store') }}" method="POST">
        @csrf

        <!-- Request No -->
        <div class="mb-3">
            <label for="request_no" class="form-label">Request No</label>
            <input type="text" name="request_no" class="form-control" id="request_no" value="{{ old('request_no') }}">
            @error('request_no') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        
      <div class="mb-3">
    <label for="title" class="form-label">Title</label>
    <input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}">
    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
</div>



        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control" id="description">{{ old('description') }}</textarea>
            @error('description') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <!-- Status -->
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <!-- Visible disabled input -->
            <input type="text" class="form-control" value="pending" disabled>
            <!-- Hidden input to submit -->
            <input type="hidden" name="status" value="pending">
            @error('status') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-success" onclick="this.disabled=true; this.form.submit();">Save</button>


    </form>
</div>
@endsection
