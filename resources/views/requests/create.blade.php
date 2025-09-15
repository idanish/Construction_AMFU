@extends('master')

@section('content')
<div class="container">
    <h2>Create New Request</h2>

    <!-- Normal form -->
    <form id="requestForm" action="{{ route('requests.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Department -->
        <div class="mb-3">
            <label for="department_id" class="form-label">Department</label>
            <select name="department_id" id="department_id" class="form-control" required>
                <option value="">-- Select Department --</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" rows="3" class="form-control" required></textarea>
        </div>

        <!-- Amount -->
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" name="amount" id="amount" class="form-control" required>
        </div>

        <!-- Comments -->
        <div class="mb-3">
            <label for="comments" class="form-label">Comments</label>
            <textarea name="comments" id="comments" rows="2" class="form-control"></textarea>
        </div>

       <div class="mb-3">
            <label class="form-label">Attachment</label>
            <div class="upload-box" id="uploadBox">
                <i class="bi bi-paperclip"></i>
                <p>Drag & Drop file here or click to upload</p>
                <input type="file" name="attachment" id="attachmentInput" hidden>
            </div>
            <div id="filePreview" class="mt-2"></div>
            @error('attachment')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>


        <button type="submit" id="submitBtn" class="btn btn-success mt-3">Create</button>
        <a href="{{ route('requests.index') }}" class="btn btn-secondary mt-3">Cancel</a>
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

{{-- Script --}}
<script>
    const uploadBox = document.getElementById('uploadBox');
    const attachmentInput = document.getElementById('attachmentInput');
    const filePreview = document.getElementById('filePreview');

    // click event
    uploadBox.addEventListener('click', () => attachmentInput.click());

    // drag events
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

    // change event
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