@extends('master')
@section('title', 'Create New Request')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="mb-0">Create New Request</h2>
    <a href="{{ route('requests.index') }}" class="btn btn-secondary vip-btn">
        <i class="bi bi-arrow-left-circle"></i> Go Back
    </a>
</div>

@if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

<div class="">
    <div class="card-body">

        <form action="{{ route('requests.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="requestor_id" value="{{ auth()->id() }}">

            <div class="form-group mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control" required>
            </div>

            <h3>Request Type Select</h3>
            <div class="form-group mb-3">
                <label class="form-label">Request Type</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="type" id="typeGeneral" value="general"
                            checked>
                        <label class="form-check-label" for="typeGeneral">General Approval</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="type" id="typePrivate" value="private">
                        <label class="form-check-label" for="typePrivate">Direct Approval</label>
                    </div>
                </div>
            </div>

            {{-- Private Assignment Field (JavaScript hide/show hoga) --}}
            <div id="privateAssignmentField" style="display: none;" class="form-group mb-3">
                <label for="assigned_to_user_id" class="form-label">Assign To Specific User:</label>
                <select name="assigned_to_user_id" id="assigned_to_user_id" class="form-control">
                    <option value="">-- Select Approver --</option>

                    @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->username }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-3" id="departmentFieldGroup">
                <label for="department_id" class="form-label">Department <span class="text-danger">*</span></label>
                <select name="department_id" id="department_id" class="form-control">
                    <option value="">-- Select Department --</option>
                    @foreach ($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>



            <div class="form-group mb-3">
                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="amount" class="form-label">Estimated Amount <span class="text-danger">*</span></label>
                <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
            </div>


            <div class="mb-3">
                <label class="form-label">Attachment</label>
                <div class="upload-box" id="uploadBox">
                    <i class="bi bi-paperclip"></i>
                    <p>Drag & Drop file here or click to upload </br> .jpg, .jpeg, .png, .pdf, .doc, .docx Max: 2 MB
                    </p>
                    <input type="file" id="attachmentInput" name="attachments[]" multiple hidden>

                </div>
                <div id="filePreview" class="mt-2"></div>
                @error('attachment')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="vip-btn btn-submit">
                    <i class="bi bi-check-lg"></i> submit
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
</style>


<script>
// ----------------------------------------------------------------------
// Attachments
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
// Fields Show Hide Script
// ----------------------------------------------------------------------
// Updated JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const typeGeneral = document.getElementById('typeGeneral');
    const typePrivate = document.getElementById('typePrivate');
    const privateField = document.getElementById('privateAssignmentField');

    // New variables for Department Select and Assigned User Select
    const departmentSelect = document.getElementById('department_id');
    const assignedUserSelect = document.getElementById('assigned_to_user_id');

    function toggleFields() {
        if (typePrivate.checked) {
            privateField.style.display = 'block';

            // Private: Assigned User required hoga, Department required nahi
            assignedUserSelect.setAttribute('required', 'required');
            departmentSelect.removeAttribute('required');
            departmentSelect.disabled = true;

        } else {
            privateField.style.display = 'none';

            // General: Department required hoga, Assigned User required nahi
            departmentSelect.setAttribute('required', 'required');
            assignedUserSelect.removeAttribute('required');
            departmentSelect.disabled = false;
        }
    }

    typeGeneral.addEventListener('change', toggleFields);
    typePrivate.addEventListener('change', toggleFields);
    toggleFields();
});
</script>

@endsection