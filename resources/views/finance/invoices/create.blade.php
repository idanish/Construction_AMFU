@extends('master')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Add New Invoice</h2>

    <form action="{{ route('finance.invoices.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Service Request --}}
        <div class="mb-3">
            <label for="request_id" class="form-label">Select Request</label>
            <select name="request_id" class="form-select">
                <option value="">Select Service Request</option>
                @foreach($requests as $request)
                    <option value="{{ $request->id }}" {{ old('request_id') == $request->id ? 'selected' : '' }}>
                        {{ $request->title ?? 'Request #'.$request->id }}
                    </option>
                @endforeach
            </select>
            @error('request_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Invoice No (Auto Generated - Readonly) --}}
        <div class="mb-3">
            <label for="invoice_no" class="form-label">Invoice No</label>
            <input type="text" name="invoice_no" class="form-control" id="invoice_no" 
                   value="{{ $invoice_no }}" readonly>
        </div>

        {{-- Amount --}}
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" name="amount" class="form-control" id="amount" value="{{ old('amount') }}">
            @error('amount')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Invoice Date --}}
<div class="mb-3">
    <label for="invoice_date" class="form-label">Invoice Date</label>
    <input type="date" name="invoice_date" class="form-control"
        value="{{ old('invoice_date') }}">
    @error('invoice_date')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>


        {{-- Status (Admin Editable, Default Unpaid) --}}
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select" 
                @if(auth()->user()->role != 'admin') disabled @endif>
                <option value="Unpaid" {{ old('status', 'Unpaid') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                <option value="Paid" {{ old('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
            </select>

            {{-- agar disabled h to backend k liye hidden input bhej do --}}
            @if(auth()->user()->role != 'admin')
                <input type="hidden" name="status" value="Unpaid">
            @endif

            @error('status')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Attachment (WhatsApp Style Upload) --}}
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

        {{-- Notes --}}
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
            @error('notes')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Save Invoice</button>
        <a href="{{ route('finance.invoices.index') }}" class="btn btn-secondary">Back</a>
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
