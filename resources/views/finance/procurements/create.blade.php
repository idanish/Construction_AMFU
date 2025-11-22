@extends('master')
@section('title', 'Add New Procurement')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper d-flex justify-content-between align-items-center">
            <div class="page-title-heading m-0">
                <div class="page-title-icon">
                    <i class="pe-7s-cash icon-gradient bg-tempting-azure"></i>
                </div>
                <div class="h4 m-0">
                    Add New Procurement
                </div>
            </div>
            <div class="page-title-actions">
                <div class="d-inline-block">
                    <a href="{{ route('finance.procurements.index') }}" class="btn btn-secondary mb-3 vip-btn">
                        <i class="bi bi-arrow-left-circle"></i> Go Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-3">
        <div class="card-body">
            <form action="{{ route('finance.procurements.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Item Name --}}
                <div class="mb-4">
                    <label for="item_name" class="form-label fw-bold">Item Name</label>
                    <input type="text" name="item_name" class="form-control @error('item_name') is-invalid @enderror"
                        value="{{ old('item_name') }}" placeholder="Enter item name">
                    @error('item_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Quantity --}}
                <div class="mb-4">
                    <label for="quantity" class="form-label fw-bold">Quantity</label>
                    <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                        value="{{ old('quantity') }}" placeholder="Enter quantity">
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Cost Estimate --}}
                <div class="mb-4">
                    <label for="cost_estimate" class="form-label fw-bold">Cost Estimate (PKR)</label>
                    <input type="number" step="0.01" name="cost_estimate"
                        class="form-control @error('cost_estimate') is-invalid @enderror" value="{{ old('cost_estimate') }}"
                        placeholder="Enter estimated cost">
                    @error('cost_estimate')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Department --}}
                <div class="mb-4">
                    <label for="department_id" class="form-label fw-bold">Department</label>
                    <select name="department_id" class="form-select @error('department_id') is-invalid @enderror">
                        <option value="">-- Select Department --</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Justification --}}
                <div class="mb-4">
                    <label for="justification" class="form-label fw-bold">List</label>
                    <textarea name="justification" rows="3" class="form-control @error('justification') is-invalid @enderror"
                        placeholder="Provide Items List for procurement(s) (If Any)">{{ old('justification') }}</textarea>
                    @error('justification')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Attachment --}}
                <div class="mb-3">
                    <label class="form-label">Attachment</label>
                    <div class="upload-box" id="uploadBox">
                        <i class="bi bi-paperclip"></i>
                        <p>Drag & Drop file here or click to upload </br> .jpg, .jpeg, .png, .pdf, .doc, .docx Max: 2 MB each</p>
                        <input type="file" name="attachment[]" id="attachmentInput" hidden multiple>
                    </div>
                    <div id="filePreview" class="mt-2"></div>
                    @error('attachment')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="mb-4" hidden>
                    <label for="status" class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="vip-btn btn-submit">
                        <i class="bi bi-check-lg"></i> Create
                    </button>
                    <a href="{{ route('finance.procurements.index') }}" class="btn btn-secondary vip-btn">
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

        uploadBox.addEventListener('click', () => { attachmentInput.click(); filePreview.innerHTML = ''; });
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

        function showFileName(file) {
            if (file) filePreview.textContent = "📎 " + file.name + " attached";
        }
    </script>
@endsection
