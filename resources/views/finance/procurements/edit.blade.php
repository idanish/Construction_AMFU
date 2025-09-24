@extends('master')

@section('content')
    <div class="container py-4">
        <h4 class="mb-4">Edit Procurement</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('finance.procurements.update', $procurement->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Item Name / Title --}}
            <div class="mb-3">
                <label for="item_name" class="form-label">Item Name</label>
                <input type="text" name="item_name" id="item_name" class="form-control"
                    value="{{ old('item_name', $procurement->item_name) }}" required>
                @error('item_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Quantity --}}
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="form-control"
                    value="{{ old('quantity', $procurement->quantity) }}" required>
                @error('quantity')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Cost Estimate --}}
            <div class="mb-3">
                <label for="cost_estimate" class="form-label">Cost Estimate (PKR)</label>
                <input type="number" name="cost_estimate" id="cost_estimate" class="form-control" step="0.01"
                    value="{{ old('cost_estimate', $procurement->cost_estimate) }}" required>
                @error('cost_estimate')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Department --}}
            <div class="mb-3">
                <label for="department_id" class="form-label">Department</label>
                <select name="department_id" id="department_id" class="form-select" required>
                    <option value="">-- Select Department --</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}"
                            {{ old('department_id', $procurement->department_id) == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
                @error('department_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Justification / Description --}}
            <div class="mb-3">
                <label for="justification" class="form-label">Remarks</label>
                <textarea name="justification" id="justification" class="form-control" rows="3">{{ old('justification', $procurement->justification) }}</textarea>
                @error('justification')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Attachment --}}
            <div class="mb-3">
                <label class="form-label">Attachment</label>
                <div class="upload-box" id="uploadBox">
                    <i class="bi bi-paperclip"></i>
                    <p>Drag & Drop file here or click to upload</p>
                    <input type="file" name="attachment" id="attachmentInput" hidden>
                </div>
                <div id="filePreview" class="mt-2">
                    @if ($procurement->attachment)
                        📎 Current File: <a href="{{ asset('storage/' . $procurement->attachment) }}"
                            target="_blank">{{ basename($procurement->attachment) }}</a>
                    @endif
                </div>
                @error('attachment')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Status --}}
            <div class="mb-3" hidden>
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="pending" {{ old('status', $procurement->status) == 'pending' ? 'selected' : '' }}>
                        Pending</option>
                    <option value="approved" {{ old('status', $procurement->status) == 'approved' ? 'selected' : '' }}>
                        Approved</option>
                    <option value="rejected" {{ old('status', $procurement->status) == 'rejected' ? 'selected' : '' }}>
                        Rejected</option>
                </select>
                @error('status')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="mb-3 d-flex gap-2">
                <button type= "submit" class="btn btn-info text-dark vip-btn">
                    <i class="bi bi-arrow-repeat"></i> Update
                </button>

                <a href="{{ route('finance.procurements.index') }}" class="btn btn-secondary vip-btn">
                    <i class="bi bi-arrow-left-circle"></i> Go Back
                </a>
            </div>
        </form>
    </div>

    {{-- Styles --}}
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

    {{-- Script --}}
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
