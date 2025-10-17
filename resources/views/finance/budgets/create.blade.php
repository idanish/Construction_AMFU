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

    <div class="main-card mb-3">
        <div class="card-body">
            <form action="{{ route('finance.budgets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Department --}}
                <div class="form-group mb-3">
                    <label for="department_id">Department</label>
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

                {{-- Year --}}
                <div class="form-group mb-3">
                    <label for="year">Year</label>
                    <input type="number" name="year" id="year"
                        class="form-control @error('year') is-invalid @enderror" value="{{ old('year') }}" required>
                    @error('year')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Allocated --}}
                <div class="form-group mb-3">
                    <label for="allocated">Allocated Amount</label>
                    <input type="number" step="0.01" name="allocated" id="allocated"
                        class="form-control @error('allocated') is-invalid @enderror" value="{{ old('allocated') }}"
                        required>
                    @error('allocated')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>


                {{-- Notes --}}
                <div class="form-group mb-3">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Attachment --}}
                <div class="mb-3">
                    <label class="form-label">Attachment</label>
                    <div class="upload-box" id="uploadBox">
                        <i class="bi bi-paperclip"></i>
                        <p>Drag & Drop file here or click to upload </br> .jpg, .jpeg, .png, .pdf, .doc, .docx Max: 2 MB</p>
                        <input type="file" name="attachment" id="attachmentInput" hidden>
                    </div>
                    <div id="filePreview" class="mt-2"></div>
                    @error('attachment')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                {{-- Status --}}
                <div class="form-group mb-3" hidden>
                    <label for="status">Status</label>
                    @if (auth()->check() && auth()->user()->role === 'admin')
                        <select name="status" id="status" class="form-control">
                            <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ old('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Rejected" {{ old('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    @else
                        <input type="text" class="form-control" value="Pending" disabled>
                        <input type="hidden" name="status" value="Pending">
                    @endif
                </div>
                {{-- Submit --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="vip-btn btn-submit">
                        <i class="bi bi-check-lg"></i> save
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
        uploadBox.addEventListener('drop', (e) => {
            e.preventDefault();
            if (e.dataTransfer.files.length > 0) {
                attachmentInput.files = e.dataTransfer.files;
                showFileName(attachmentInput.files[0]);
            }
            uploadBox.style.background = '#f8f9fa';
        });
        attachmentInput.addEventListener('change', () => {
            if (attachmentInput.files.length > 0) showFileName(attachmentInput.files[0]);
        });

        function showFileName(file) {
            if (file) filePreview.textContent = "📎 " + file.name + " attached";
        }

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
    </script>
@endsection
