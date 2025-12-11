@extends('master')
@section('title', 'Edit Budget')
@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Edit Budget</h2>

        <form action="{{ route('finance.budgets.update', $budget->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Department --}}
            <div class="mb-3">
                <label for="department_id" class="form-label">Department</label>
                <select name="department_id" id="department_id" class="form-select @error('department_id') is-invalid @enderror" required>
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
            
            <div class="mb-3">
                <label for="month" class="form-label">Month</label>
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
                        <option value="{{ $key }}" 
                            {{ old('month', $budget->month) == $key ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
                @error('month')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Year --}}
            <div class="mb-3">
                <label for="year" class="form-label">Year</label>
                <input type="number" name="year" id="year" class="form-control @error('year') is-invalid @enderror"
                    value="{{ old('year', $budget->year) }}" required>
                @error('year')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Allocated --}}
            <div class="mb-3">
                <label for="allocated" class="form-label">Allocated Amount</label>
                <input type="number" step="0.01" name="allocated" id="allocated" class="form-control @error('allocated') is-invalid @enderror"
                    value="{{ old('allocated', $budget->allocated) }}" required>
                @error('allocated')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Spent --}}
            <div class="mb-3">
                <label for="spent" class="form-label">Spent Amount</label>
                <input type="number" step="0.01" name="spent" id="spent" class="form-control @error('spent') is-invalid @enderror"
                    value="{{ old('spent', $budget->spent) }}">
                @error('spent')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Balance (Read-only/Disabled) --}}
            <div class="mb-3">
                <label for="balance" class="form-label">Balance</label>
                {{-- Visible disabled field, jo sirf display ke liye hai --}}
                <input type="number" id="balance" class="form-control" 
                    value="{{ old('balance', $budget->balance) }}" disabled>

                {{-- Hidden field jo backend ko bhejega --}}
                <input type="hidden" name="balance" id="hidden_balance" value="{{ old('balance', $budget->balance) }}">
            </div>

            {{-- Notes --}}
            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $budget->notes) }}</textarea>
                @error('notes')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- 🔑 CHANGE 2: Attachments (Multiple Files / Spatie Media Library) --}}
            <div class="mb-3">
                <label class="form-label">Attachments</label>
                
                {{-- Existing Attachments Display --}}
                @php
                    $mediaItems = $budget->getMedia('attachments');
                @endphp
                @if ($mediaItems->count() > 0)
                    <p class="mt-2 fw-bold">Existing Files:</p>
                    <ul class="list-group mb-3">
                        @foreach($mediaItems as $media)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                <span><i class="bi bi-paperclip me-2"></i> {{ $media->file_name }}</span>
                                <div>
                                    <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-info me-2">View</a>
                                    {{-- Agar aapko Media Delete ka option chahiye to yahan shamil kar sakte hain --}}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="mt-2 text-muted">No existing attachments.</p>
                @endif
                
                {{-- Upload New Attachments --}}
                <div class="upload-box" id="uploadBox">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <p>Drag & Drop new files here or click to upload (max 2MB each)</p>
                    <input type="file" id="attachmentInput" name="attachments[]" multiple hidden> 
                </div>
                <div id="filePreview" class="mt-2 text-success"></div>
                
                @error('attachments.*')
                    <small class="text-danger">File upload error: {{ $message }}</small>
                @enderror
            </div>
            
            {{-- Status --}}
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                @if (auth()->check() && auth()->user()->role === 'admin')
                    <select name="status" id="status" class="form-select">
                        <option value="pending" {{ old('status', $budget->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('status', $budget->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ old('status', $budget->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                @else
                    <select class="form-select" disabled>
                        <option>{{ ucfirst($budget->status) }}</option>
                    </select>
                    <input type="hidden" name="status" value="{{ $budget->status }}">
                @endif
            </div>

            <button type="submit" class="btn btn-info text-dark vip-btn">
                <i class="bi bi-arrow-repeat"></i> Update
            </button>
            <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary vip-btn">
                <i class="bi bi-arrow-left-circle"></i> Go Back
            </a>
        </form>
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
                for(let i = 0; i < files.length; i++) {
                    previewHtml += `<div>📎 ${files[i].name} (${(files[i].size / 1024 / 1024).toFixed(2)} MB)</div>`;
                }
                filePreview.innerHTML = previewHtml;
            } else {
                filePreview.textContent = '';
            }
        }
    </script>
@endsection