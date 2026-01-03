@extends('master')
@section('title', 'Create Budget')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center">
        <div class="page-title-heading m-0">
            <div class="page-title-icon">
                <i class="pe-7s-cash icon-gradient bg-tempting-azure"></i>
            </div>
            <div class="h4 m-0">
                Create Budget
            </div>
        </div>
        <div class="page-title-actions">
            <div class="d-inline-block">
                <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary mb-3 vip-btn">
                    <i class="bi bi-arrow-left-circle"></i> Go Back
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Code start here -->
<div class="main-card mb-3">
    <div class="card-body">
        <form action="{{ route('finance.budgets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Department --}}
            <div class="mb-4">
                <label for="department_id" class="form-label fw-bold">Department</label>
                <select name="department_id" id="department_id"
                    class="form-control @error('department_id') is-invalid @enderror" required>
                    <option value="">Select Department</option>
                    @foreach ($departments as $department)
                    <option value="{{ $department->id }}"
                        {{ old('department_id') == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                    @endforeach
                </select>
                @error('department_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Month --}}
            <div class="mb-4">
                <label for="month" class="form-label fw-bold">Month</label>
                <select name="month" id="month" class="form-control @error('month') is-invalid @enderror" required>
                    <option value="">Select Month</option>
                    @php
                    $months = [
                    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                    ];
                    @endphp
                    @foreach ($months as $key => $name)
                    <option value="{{ $key }}" {{ old('month') == $key ? 'selected' : '' }}>
                        
                        {{ $name }}
                    </option>
                    @endforeach
                </select>
                @error('month')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Year --}}
            <div class="mb-4">
                <label for="year" class="form-label fw-bold">Year</label>
                <input type="number" name="year" id="year" class="form-control @error('year') is-invalid @enderror"
                    value="{{ old('year', date('Y')) }}" required>
                @error('year')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Allocated (Budget Amount) --}}
            <div class="mb-4">
                <label for="allocated" class="form-label fw-bold">Allocated Amount</label>
                <input type="number" step="0.01" name="allocated" id="allocated"
                    class="form-control @error('allocated') is-invalid @enderror"
                    value="{{ old('allocated') }}" required>
                @error('allocated')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Notes --}}
            <div class="mb-4">
                <label for="notes" class="form-label fw-bold">Notes</label>
                <textarea name="notes" id="notes" rows="6"
                    class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Attachment (Multiple Files) --}}
            <div class="mb-3">
                <label class="form-label">Attachments</label>
                <div class="upload-box" id="uploadBox">
                    <i class="bi bi-paperclip"></i>
                    <p>Drag & Drop file here or click to upload </br> .jpg, .jpeg, .png, .pdf, .doc, .docx Max: 2 MB</p>
                    <input type="file" id="attachmentInput" name="attachments[]" multiple hidden>
                </div>
                <div id="filePreview" class="mt-2 text-success"></div>
                @error('attachments.*')
                <div class="text-danger mt-1">File upload error: {{ $message }}</div>
                @enderror
            </div>

            {{-- Status (Hidden field with default Pending) --}}
            <div class="mb-4" hidden>
                <label for="status" class="form-label fw-bold">Status</label>
                @if (auth()->check() && auth()->user()->role === 'admin')
                <select name="status" id="status" class="form-control">
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                @else
                <input type="hidden" name="status" value="pending">
                @endif
            </div>

            {{-- Submit --}}
            <div class="d-flex gap-2">
                <button type="submit" class="vip-btn btn-submit">
                    <i class="bi bi-check-lg"></i> Add Budget
                </button>
                <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary vip-btn">
                    <i class="bi bi-x-octagon"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>
<style>
.upload-box {
    border: 2px dashed #6c757d;
    border-radius: 10px;
    padding: 25px;
    text-align: center;
    cursor: pointer;
    background-color: #f8f9fa;
    transition: background 0.3s;
}

.upload-box:hover {
    background: #e9ecef;
}

.upload-box i {
    font-size: 28px;
    color: #0d6efd;
}

#filePreview {
    font-size: 14px;
    color: #198754;
    font-weight: 500;
}
</style>

<script>
const uploadBox = document.getElementById('uploadBox');
const attachmentInput = document.getElementById('attachmentInput');
const filePreview = document.getElementById('filePreview');

uploadBox.addEventListener('click', () => attachmentInput.click());
uploadBox.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadBox.style.background = '#dee2e6';
});
uploadBox.addEventListener('dragleave', () => {
    uploadBox.style.background = '#f8f9fa';
});

// Drag and Drop files ko sahi se set karna
uploadBox.addEventListener('drop', (e) => {
    e.preventDefault();
    if (e.dataTransfer.files.length > 0) {
        attachmentInput.files = e.dataTransfer.files;
        showFileNames(attachmentInput.files); // Updated function call
    }
    uploadBox.style.background = '#f8f9fa';
});

// Input change par file names display karna
attachmentInput.addEventListener('change', () => {
    if (attachmentInput.files.length > 0) showFileNames(attachmentInput.files);
});

// Multiple file names display karne ke liye updated function
function showFileNames(files) {
    let previewHtml = '';
    if (files.length > 0) {
        for (let i = 0; i < files.length; i++) {
            previewHtml += `<div>📎 ${files[i].name} (${(files[i].size / 1024 / 1024).toFixed(2)} MB)</div>`;
        }
        filePreview.innerHTML = previewHtml;
    } else {
        filePreview.textContent = '';
    }
}

</script>
@endsection