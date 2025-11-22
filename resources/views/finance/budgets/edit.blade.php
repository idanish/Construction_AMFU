@extends('master')
@section('title', 'Edit Budget')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper d-flex justify-content-between align-items-center">
            <div class="page-title-heading m-0">
                <div class="page-title-icon">
                    <i class="pe-7s-cash icon-gradient bg-tempting-azure"></i>
                </div>

                <style>
                    .upload-box {
                        border: 2px dashed #6c757d;
                        border-radius: 10px;
                        padding: 12px;
                        text-align: center;
                        cursor: pointer;
                        background-color: #f8f9fa;
                        transition: background 0.3s;
                        max-height: 160px;
                        overflow: auto;
                    }
                    .upload-box i { font-size: 20px; color: #0d6efd; }
                    #filePreviewEdit { font-size:13px; color:#198754; font-weight:500; }
                </style>
                <div class="h4 m-0">Edit Budget</div>
            </div>
            <div class="page-title-actions">
                <div class="d-inline-block">
                    <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary mb-3 vip-btn btn-sm">
                        <i class="bi bi-arrow-left-circle"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 w-75 mx-auto">
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
            <div class="mb-3">
                <label for="department_id" class="form-label">Department</label>
                <select name="department_id" id="department_id" class="form-select form-select-sm" required>
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

            {{-- Year --}}
            <div class="mb-3">
                <label for="year" class="form-label">Year</label>
                <input type="number" name="year" id="year" class="form-control form-control-sm"
                    value="{{ old('year', $budget->year) }}" required>
                @error('year')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Allocated --}}
            <div class="mb-3">
                <label for="allocated" class="form-label">Allocated Amount</label>
                <input type="number" step="0.01" name="allocated" id="allocated" class="form-control form-control-sm"
                    value="{{ old('allocated', $budget->allocated) }}" required>
                @error('allocated')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Requested Budget --}}
            <div class="mb-3">
                <label for="requested_budget">Requested Budget</label>
                <input type="number" step="0.01" name="requested_budget" id="requested_budget"
                    class="form-control form-control-sm @error('requested_budget') is-invalid @enderror"
                    value="{{ old('requested_budget', $budget->requested_budget) }}" required>
                @error('requested_budget')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Budget Type --}}
            <div class="mb-3">
                <label for="budget_type">Budget Type</label>
                <select name="budget_type" id="budget_type" class="form-select form-select-sm @error('budget_type') is-invalid @enderror" required>
                    <option value="">Select Type</option>
                    <option value="monthly" {{ old('budget_type', $budget->budget_type) == 'monthly' ? 'selected' : '' }}>Monthly Budget</option>
                    <option value="weekly" {{ old('budget_type', $budget->budget_type) == 'weekly' ? 'selected' : '' }}>Weekly Budget</option>
                </select>
                @error('budget_type')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Spent --}}
            <div class="mb-3">
                <label for="spent" class="form-label">Spent Amount</label>
                <input type="number" step="0.01" name="spent" id="spent" class="form-control form-control-sm"
                    value="{{ old('spent', $budget->spent) }}">
                @error('spent')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Balance --}}
            <div class="mb-3">
                <label for="balance" class="form-label">Balance</label>
                {{-- Visible disabled field --}}
                <input type="number" id="balance" class="form-control form-control-sm" value="{{ $budget->balance }}" disabled>

                {{-- Hidden field jo backend ko bhejega --}}
                <input type="hidden" name="balance" id="hidden_balance" value="{{ $budget->balance }}">
            </div>





            {{-- Notes --}}
            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" id="notes" class="form-control form-control-sm">{{ old('notes', $budget->notes) }}</textarea>
                @error('notes')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Attachment --}}
            <div class="mb-3">
                <label for="attachment" class="form-label">Attachment (Optional)</label>

                <div class="upload-box" id="uploadBoxEdit">
                    <i class="bi bi-paperclip"></i>
                    <p>Drag & Drop file here or click to upload </br> .jpg, .jpeg, .png, .pdf, .doc, .docx Max: 2 MB each</p>
                    <input type="file" name="attachment[]" id="attachment" class="d-none"
                        accept=".pdf,.jpg,.jpeg,.png,.docx,.xlsx" multiple>
                </div>

                <div id="filePreviewEdit" class="mt-2"></div>

                <div id="existingAttachments" class="mt-2">
                    @if($budget->attachment)
                        @php
                            $attachments = is_array($budget->attachment) ? $budget->attachment : (json_decode($budget->attachment, true) ?? []);
                        @endphp
                        @foreach($attachments as $att)
                            @php
                                $attPath = is_array($att) ? ($att['path'] ?? '') : $att;
                                $attName = is_array($att) ? ($att['name'] ?? basename($attPath)) : basename($attPath);
                            @endphp
                            <div>
                                <a href="{{ asset('storage/' . $attPath) }}" target="_blank">{{ $attName }}</a>
                            </div>
                        @endforeach
                    @endif
                </div>

                @error('attachment')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                @if (auth()->check() && auth()->user()->role === 'admin')
                    <select name="status" id="status" class="form-select">
                        <option value="Pending" {{ old('status', $budget->status) == 'Pending' ? 'selected' : '' }}>Pending
                        </option>
                        <option value="Approved" {{ old('status', $budget->status) == 'Approved' ? 'selected' : '' }}>
                            Approved
                        </option>
                        <option value="Rejected" {{ old('status', $budget->status) == 'Rejected' ? 'selected' : '' }}>
                            Rejected
                        </option>
                    </select>
                @else
                    <select class="form-select" disabled>
                        <option>{{ $budget->status }}</option>
                    </select>
                    <input type="hidden" name="status" value="{{ $budget->status }}">
                @endif
            </div>

                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="vip-btn btn-submit btn-sm">
                        <i class="bi bi-arrow-repeat"></i> Update
                    </button>
                    <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary vip-btn btn-sm">
                        <i class="bi bi-arrow-left-circle"></i> Go Back
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const attachmentInputEdit = document.getElementById('attachment');
        const existingAttachmentsDiv = document.getElementById('existingAttachments');
        // Drag & drop + click handler for edit upload box
        (function() {
            const uploadBox = document.getElementById('uploadBoxEdit');
            const attachmentInput = document.getElementById('attachment');
            const filePreview = document.getElementById('filePreviewEdit');

            function showFileNamesEdit(files) {
                if (!files || files.length === 0) { filePreview.innerHTML = ''; return; }
                let html = '<ul class="list-unstyled mb-0">';
                for (let i = 0; i < files.length; i++) {
                    const f = files[i];
                    const sizeKb = Math.round(f.size / 1024);
                    html += `<li>📎 ${f.name} <small class="text-muted">(${sizeKb} KB)</small></li>`;
                    if (i >= 9) { html += '<li class="text-muted">...and more</li>'; break; }
                }
                html += '</ul>';
                filePreview.innerHTML = html;
            }

            if (uploadBox) {
                uploadBox.addEventListener('click', () => { attachmentInput.click(); filePreview.innerHTML = ''; });
                uploadBox.addEventListener('dragover', (e) => { e.preventDefault(); uploadBox.style.background = '#dee2e6'; });
                uploadBox.addEventListener('dragleave', () => { uploadBox.style.background = '#f8f9fa'; });
                uploadBox.addEventListener('drop', (e) => {
                    e.preventDefault();
                    if (e.dataTransfer.files.length > 0) {
                        attachmentInput.files = e.dataTransfer.files;
                        showFileNamesEdit(attachmentInput.files);
                    }
                    uploadBox.style.background = '#f8f9fa';
                });
            }

            if (attachmentInput) {
                attachmentInput.addEventListener('change', () => {
                    filePreview.innerHTML = '';
                    if (attachmentInput.files.length > 0) showFileNamesEdit(attachmentInput.files);
                });
            }
        })();
    </script>

    <script>
        // Disable submit button on submit to prevent duplicate submits
        document.querySelector('form').addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            if (btn) { btn.disabled = true; btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Updating...'; }
        });
    </script>

    {{-- Live Balance Script --}}
    <script>
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
    </script>
@endsection
