@extends('master')
@section('title', 'Edit Request')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Edit Request</h2>
    <a href="{{ route('requests.index') }}" class="btn btn-secondary vip-btn">
        <i class="bi bi-arrow-left-circle"></i> Go Back
    </a>
</div>

@if (session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="">
    <div class="card-body">
        <form action="{{ route('requests.update', $requestModel->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control"
                    value="{{ old('title', $requestModel->title) }}" required>
                @error('title')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <h3 class="d-none">Request Type Select</h3>
            <div class="form-group mb-3 d-none">
                <label>Request Type</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="type" id="typeGeneral" value="general"
                            {{ old('type', $requestModel->type) === 'general' ? 'checked' : '' }}  disabled>
                        <label class="form-check-label" for="typeGeneral">General Approval</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="type" id="typePrivate" value="private"
                            {{ old('type', $requestModel->type) === 'private' ? 'checked' : '' }}  disabled>
                        <label class="form-check-label" for="typePrivate">Direct Approval</label>
                    </div>
                </div>
                @error('type')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Private Assignment Field --}}
            <div id="privateAssignmentField" style="display: none;" class="form-group mb-3 d-none">
                <label for="assigned_to_user_id">Assign To Specific User:</label>
                <select name="assigned_to_user_id" id="assigned_to_user_id" class="form-control"  disabled>
                    <option value="">-- Select Approver --</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                        {{ old('assigned_to_user_id', $requestModel->assigned_to_user_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->username }})
                    </option>
                    @endforeach
                </select>
                @error('assigned_to_user_id')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Department Select Field --}}
            <div class="form-group mb-3 d-none" id="departmentFieldGroup">
                <label for="department_id">Department</label>
                <select name="department_id" id="department_id" class="form-control disabled">
                    <option value="">-- Select Department --</option>
                    @foreach ($departments as $dept)
                    <option value="{{ $dept->id }}"
                        {{ old('department_id', $requestModel->department_id) == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                    @endforeach
                </select>
                @error('department_id')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                <textarea name="description" id="description" class="form-control" rows="5"
                    required>{{ old('description', $requestModel->description) }}</textarea>
                @error('description')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="amount" class="form-label">Estimated Amount <span class="text-danger">*</span></label>
                <input type="number" name="amount" id="amount" class="form-control" step="0.01"
                    value="{{ old('amount', $requestModel->amount) }}" required>
                @error('amount')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Existing Attachments Display --}}
            @if ($requestModel->getMedia('attachments')->count() > 0)
            <div class="mb-3">
                <label class="form-label">Current Attachments</label>
                <div class="existing-attachments" id="existingAttachments">
                    @foreach ($requestModel->getMedia('attachments') as $index => $media)
                    <div class="attachment-item" data-media-id="{{ $media->id }}">
                        <i class="bi bi-file-earmark"></i>
                        <a href="{{ $media->getUrl() }}" target="_blank">{{ $media->file_name }}</a>
                        <span class="text-muted">({{ number_format($media->size / 1024, 2) }} KB)</span>
                        <button type="button" class="btn-remove-attachment"
                            onclick="removeExistingAttachment({{ $media->id }})">
                            ✖
                        </button>
                    </div>
                    @endforeach
                </div>
                <input type="hidden" name="remove_attachments" id="removeAttachmentsInput" value="">
            </div>
            @endif

            {{-- New Attachments Upload --}}
            <div class="mb-3">
                <label class="form-label">Add New Attachments</label>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="replace_attachments" id="replaceAttachments"
                        value="1">
                    <label class="form-check-label" for="replaceAttachments">
                        Replace all existing attachments with new ones
                    </label>
                </div>

                <div class="upload-box" id="uploadBox">
                    <i class="bi bi-paperclip"></i>
                    <p>Drag & Drop file here or click to upload </br> .jpg, .jpeg, .png, .pdf, .doc, .docx Max: 2 MB
                        <input type="file" id="attachmentInput" name="attachments[]" multiple hidden>
                </div>
                <div id="filePreview" class="mt-2"></div>
                @error('attachments')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="vip-btn btn-submit">
                    <i class="bi bi-check-lg"></i> Update
                </button>
                <a href="{{ route('requests.index') }}" class="btn btn-light vip-btn">
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

.existing-attachments {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
}

.attachment-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px;
    margin-bottom: 8px;
    background: white;
    border-radius: 5px;
    border: 1px solid #e0e0e0;
}

.attachment-item i {
    color: #0d6efd;
    font-size: 18px;
}

.attachment-item a {
    flex: 1;
    text-decoration: none;
    color: #212529;
}

.attachment-item a:hover {
    text-decoration: underline;
}

.btn-remove-attachment {
    color: red;
    border: none;
    background: none;
    cursor: pointer;
    font-size: 16px;
    padding: 0 8px;
}

.btn-remove-attachment:hover {
    color: darkred;
}

.attachment-item.removed {
    opacity: 0.5;
    text-decoration: line-through;
}
</style>

<script>
// ----------------------------------------------------------------------
// Existing Attachments Removal
// ----------------------------------------------------------------------
let removedAttachments = [];

function removeExistingAttachment(mediaId) {
    const item = document.querySelector(`.attachment-item[data-media-id="${mediaId}"]`);
    if (item) {
        item.classList.add('removed');
        removedAttachments.push(mediaId);
        document.getElementById('removeAttachmentsInput').value = removedAttachments.join(',');
    }
}

// ----------------------------------------------------------------------
// New Attachments Upload
// ----------------------------------------------------------------------
const uploadBox = document.getElementById('uploadBox');
const attachmentInput = document.getElementById('attachmentInput');
const filePreview = document.getElementById('filePreview');

let selectedFiles = [];

uploadBox.addEventListener('click', function() {
    attachmentInput.click();
});

attachmentInput.addEventListener('change', function() {
    selectedFiles = Array.from(this.files);
    renderFileList();
});

function renderFileList() {
    filePreview.innerHTML = '';

    selectedFiles.forEach((file, index) => {
        const div = document.createElement('div');
        div.style.marginBottom = '5px';

        div.innerHTML = `
        ${index + 1}. ${file.name} (${Math.round(file.size/1024)} KB)
        <button type="button" style="margin-left:10px;color:red;border:none;background:none;cursor:pointer;" onclick="removeFile(${index})">
            ✖
        </button>
        `;

        filePreview.appendChild(div);
    });

    updateInputFiles();
}

function removeFile(index) {
    selectedFiles.splice(index, 1);
    renderFileList();
}

function updateInputFiles() {
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => {
        dataTransfer.items.add(file);
    });
    attachmentInput.files = dataTransfer.files;
}

// ----------------------------------------------------------------------
// Fields Show/Hide Script (Type Selection)
// ----------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', function() {
    const typeGeneral = document.getElementById('typeGeneral');
    const typePrivate = document.getElementById('typePrivate');
    const privateField = document.getElementById('privateAssignmentField');
    const departmentSelect = document.getElementById('department_id');
    const assignedUserSelect = document.getElementById('assigned_to_user_id');

    function toggleFields() {
        if (typePrivate.checked) {
            privateField.style.display = 'block';
            assignedUserSelect.setAttribute('required', 'required');
            departmentSelect.removeAttribute('required');
            departmentSelect.disabled = true;
        } else {
            privateField.style.display = 'none';
            departmentSelect.setAttribute('required', 'required');
            assignedUserSelect.removeAttribute('required');
            departmentSelect.disabled = false;
        }
    }

    typeGeneral.addEventListener('change', toggleFields);
    typePrivate.addEventListener('change', toggleFields);

    // Initial toggle based on current value
    toggleFields();
});
</script>

@endsection