@extends('master')
@section('title', 'Create New Request')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1>Create New Request</h1>
            <form action="{{ route('requests.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="requestor_id" value="{{ auth()->id() }}">

                <div class="form-group mb-3">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" class="form-control" required>
                </div>

                <select name="department_id" id="department_id" class="form-control" required>
                    <option value="">-- Select Department --</option>
                    @foreach ($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>

                <div class="form-group mb-3">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="5" required></textarea>
                </div>

                <div class="form-group mb-3">
                    <label for="amount">Estimated Amount</label>
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


                <button type="submit" class="vip-btn btn-submit">
                    <i class="bi bi-check-lg"></i> Submit
                </button>
            </form>


        </div>
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
</script>




@endsection