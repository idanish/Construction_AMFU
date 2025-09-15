@extends('master')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Add New Budget</h2>

    <form action="{{ route('finance.budgets.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Department --}}
        <div class="mb-3">
            <label for="department_id" class="form-label">Department</label>
            <select name="department_id" id="department_id" class="form-select" required>
                @foreach($departments as $department)
                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                    {{ $department->name }}
                </option>
                @endforeach
            </select>
            @error('department_id')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Year --}}
        <div class="mb-3">
            <label for="year" class="form-label">Year</label>
            <input type="number" name="year" class="form-control" id="year" value="{{ old('year') }}" required>
            @error('year')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Allocated --}}
        <div class="mb-3">
            <label for="allocated" class="form-label">Allocated Amount</label>
            <input type="number" step="0.01" name="allocated" class="form-control" id="allocated"
                value="{{ old('allocated') }}" required>
            @error('allocated')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Spent --}}
        <div class="mb-3">
            <label for="spent" class="form-label">Spent Amount</label>
            <input type="number" step="0.01" name="spent" class="form-control" id="spent" value="{{ old('spent') }}">
            @error('spent')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Balance --}}
        <div class="mb-3">
            <label for="balance" class="form-label">Balance</label>
            <input type="number" class="form-control" id="balance" value="{{ old('balance', 0) }}" disabled>
        </div>

        {{-- Notes --}}
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" id="notes" class="form-control">{{ old('notes') }}</textarea>
            @error('notes')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Attachment WhatsApp-style --}}
        <div class="mb-3">
            <label class="form-label">Attachment (Optional)</label>
            <div class="upload-box" id="uploadBox">
                <i class="bi bi-paperclip"></i>
                <p>Drag & Drop file here or click to upload</p>
                <input type="file" name="attachment" id="attachmentInput" hidden
                    accept=".pdf,.jpg,.jpeg,.png,.docx,.xlsx">
            </div>
            <div id="filePreview" class="mt-2 text-muted"></div>
            @error('attachment')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            @if(auth()->check() && auth()->user()->role === 'admin')
            <select name="status" id="status" class="form-select">
                <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Approved" {{ old('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                <option value="Rejected" {{ old('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
            @else
            <select class="form-select" disabled>
                <option>Pending</option>
            </select>
            <input type="hidden" name="status" value="Pending">
            @endif
        </div>

        <button type="submit" class="btn btn-success">Save Budget</button>
        <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>

{{-- Styles --}}
<style>
.upload-box {
    border: 2px dashed #6c757d;
    border-radius: 10px;
    padding: 30px;
    text-align: center;
    cursor: pointer;
    background-color: #f8f9fa;
    transition: background 0.3s;
}

.upload-box:hover {
    background: #e9ecef;
}

.upload-box i {
    font-size: 30px;
    color: #0d6efd;
}

#filePreview {
    font-size: 14px;
    color: #198754;
    font-weight: 500;
}
</style>

{{-- Scripts --}}
<script>
const allocatedInput = document.getElementById('allocated');
const spentInput = document.getElementById('spent');
const balanceInput = document.getElementById('balance');

function updateBalance() {
    const allocated = parseFloat(allocatedInput.value) || 0;
    const spent = parseFloat(spentInput.value) || 0;
    balanceInput.value = (allocated - spent).toFixed(2);
}
allocatedInput.addEventListener('input', updateBalance);
spentInput.addEventListener('input', updateBalance);

// WhatsApp-style attachment
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
        showFileName(attachmentInput.files[0]);
    }
    uploadBox.style.background = '#f8f9fa';
});

attachmentInput.addEventListener('change', () => {
    if (attachmentInput.files.length > 0) {
        showFileName(attachmentInput.files[0]);
    }
});

function showFileName(file) {
    if (file) {
        filePreview.textContent = "📎 " + file.name + " attached";
    }
}
</script>

@endsection