@extends('master')
@section('title', 'Edit Budget')
@section('content')
<div class="app-page-title">
    <div class="page-title-wrapper d-flex justify-content-between align-items-center">
        <div class="page-title-heading m-0">
            <div class="page-title-icon">
                <i class="pe-7s-cash icon-gradient bg-tempting-azure"></i>
            </div>
            <div class="h4 m-0">
                Edit Budget
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


<div class="main-card mb-3">
    <div class="card-body">

        {{-- Flash Messages --}}
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        {{-- Popup for flash messages --}}
        @if(session('error') || session('success'))
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var msg = @json(session('error') ?? session('success'));
            var icon = @json(session('error') ? 'error' : 'success');
            if (msg) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: icon === 'error' ? 'Error' : 'Success',
                        text: msg,
                        icon: icon,
                        confirmButtonText: 'OK',
                        allowOutsideClick: false
                    });
                } else {
                    alert(msg);
                }
            }
        });
        </script>
        @endif

        <form action="{{ route('finance.budgets.update', $budget->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Department --}}
            <div class="mb-4">
                <label for="department_id"  class="form-label fw-bold">Department</label>
                <select name="department_id" id="department_id"
                    class="form-select @error('department_id') is-invalid @enderror" required>
                    @foreach ($departments as $department)
                    <option value="{{ $department->id }}"
                        {{ old('department_id', $budget->department_id) == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                    @endforeach
                </select>
                @error('department_id')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-4">
                <label for="month"  class="form-label fw-bold">Month</label>
                <select name="month" id="month" class="form-select @error('month') is-invalid @enderror" required>
                    <option value="">Select Month</option>
                    @php
                    $months = [
                    1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                    5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                    9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                    ];
                    @endphp
                    @foreach ($months as $key => $name)
                    <option value="{{ $key }}" {{ old('month', $budget->month) == $key ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                    @endforeach
                </select>
                @error('month')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Year --}}
            <div class="mb-4">
                <label for="year"  class="form-label fw-bold">Year</label>
                <input type="number" name="year" id="year" class="form-control @error('year') is-invalid @enderror"
                    value="{{ old('year', $budget->year) }}" required>
                @error('year')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Allocated --}}
            <div class="mb-4">
                <label for="allocated"  class="form-label fw-bold">Allocated Amount</label>
                <input type="number" step="0.01" name="allocated" id="allocated"
                    class="form-control @error('allocated') is-invalid @enderror"
                    value="{{ old('allocated', $budget->allocated) }}" required>
                @error('allocated')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Spent --}}
            <div class="mb-4">
                <label for="spent"  class="form-label fw-bold">Spent Amount</label>
                <input type="number" step="0.01" name="spent" id="spent"
                    class="form-control @error('spent') is-invalid @enderror"
                    value="{{ old('spent', $budget->spent) }}">
                @error('spent')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Balance (Read-only/Disabled) --}}
            <div class="mb-4">
                <label for="balance"  class="form-label fw-bold">Balance</label>

                <input type="number" id="balance" class="form-control" value="{{ old('balance', $budget->balance) }}"
                    disabled>

                <input type="hidden" name="balance" id="hidden_balance" value="{{ old('balance', $budget->balance) }}">
            </div>

            {{-- Notes --}}
            <div class="mb-4">
                <label for="notes"  class="form-label fw-bold">Notes</label>
                <textarea name="notes" id="notes" rows="6"
                    class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $budget->notes) }}</textarea>
                @error('notes')
                <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Attachments</label>
                @php
                $mediaItems = $budget->getMedia('attachments');
                @endphp
                @if ($mediaItems->count() > 0)
                <p class="mt-2 fw-bold">Existing Files:</p>
                <ul class="list-group mb-4">
                    @foreach($mediaItems as $media)
                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                        <span><i class="bi bi-paperclip me-2"></i> {{ $media->file_name }}</span>
                        <div>
                            <a href="{{ $media->getUrl() }}" target="_blank"
                                class="btn btn-outline-info me-2">View</a>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="mt-2 text-muted">No existing attachments.</p>
                @endif
                <div class="upload-box" id="uploadBox">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <p>Drag & Drop file here or click to upload </br> .jpg, .jpeg, .png, .pdf, .doc, .docx Max: 2 MB</p>
                    <input type="file" id="attachmentInput" name="attachments[]" multiple hidden>
                </div>
                <div id="filePreview" class="mt-2 text-success"></div>

                @error('attachments.*')
                <small class="text-danger mt-1">File upload error: {{ $message }}</small>
                @enderror
            </div>

            {{-- Status --}}
            <div class="mb-4" hidden>
                <label for="status"  class="form-label fw-bold">Status</label>
                @if (auth()->check() && auth()->user()->role === 'admin')
                <select name="status" id="status" class="form-select">
                    <option value="pending" {{ old('status', $budget->status) == 'pending' ? 'selected' : '' }}>Pending
                    </option>
                    <option value="approved" {{ old('status', $budget->status) == 'approved' ? 'selected' : '' }}>
                        Approved</option>
                    <option value="rejected" {{ old('status', $budget->status) == 'rejected' ? 'selected' : '' }}>
                        Rejected</option>
                </select>
                @else
                <select class="form-select" disabled>
                    <option>{{ ucfirst($budget->status) }}</option>
                </select>
                <input type="hidden" name="status" value="{{ $budget->status }}">
                @endif
            </div>

            {{-- Submit --}}
            <div class="d-flex gap-2">
                <button type="submit" class="vip-btn btn-submit">
                    <i class="bi bi-arrow-repeat"></i> Update
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
// Live Balance Script (Same as before)
const allocatedInput = document.getElementById('allocated');
const spentInput = document.getElementById('spent');
const balanceInput = document.getElementById('balance');
const hiddenBalanceInput = document.getElementById('hidden_balance');

function updateBalance() {
    const allocated = parseFloat(allocatedInput.value) || 0;
    const spent = parseFloat(spentInput.value) || 0;
    const balance = (allocated - spent).toFixed(2);

    balanceInput.value = balance;
    hiddenBalanceInput.value = balance;
}

allocatedInput.addEventListener('input', updateBalance);
spentInput.addEventListener('input', updateBalance);

// Ensure balance is calculated on page load if values exist
updateBalance();

// 🔑 Attachments Scripts (Create View se shamil kiye gaye)
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

uploadBox.addEventListener('drop', (e) => {
    e.preventDefault();
    if (e.dataTransfer.files.length > 0) {
        attachmentInput.files = e.dataTransfer.files;
        showFileNames(attachmentInput.files);
    }
    uploadBox.style.background = '#f8f9fa';
});

attachmentInput.addEventListener('change', () => {
    if (attachmentInput.files.length > 0) showFileNames(attachmentInput.files);
});

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