@extends('master')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Edit Payment</h2>

    <form action="{{ route('finance.payments.update', $payment->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Payment Reference (Readonly) --}}
<div class="mb-3">
    <label for="payment_ref" class="form-label">Payment Reference</label>
    <input type="text" name="payment_ref" id="payment_ref" class="form-control" 
        value="{{ old('payment_ref', $payment->payment_ref) }}" readonly>
    @error('payment_ref')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>


        {{-- Invoice --}}
        <div class="mb-3">
            <label for="invoice_id" class="form-label">Invoice</label>
            <select name="invoice_id" id="invoice_id" class="form-select" required>
                @foreach($invoices as $invoice)
                    <option value="{{ $invoice->id }}" {{ (old('invoice_id', $payment->invoice_id) == $invoice->id) ? 'selected' : '' }}>
                        #{{ $invoice->id }} - {{ $invoice->invoice_no }}
                    </option>
                @endforeach
            </select>
            @error('invoice_id')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Payment Date --}}
        <div class="mb-3">
            <label for="payment_date" class="form-label">Payment Date</label>
            <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ old('payment_date', $payment->payment_date) }}" required>
            @error('payment_date')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Amount --}}
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" name="amount" id="amount" class="form-control" step="0.01" value="{{ old('amount', $payment->amount) }}" required>
            @error('amount')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        {{-- Status --}}
        <div class="mb-3">
            <label class="form-label">Status</label>
            @if(auth()->user()->role === 'admin')
                <select name="status" class="form-select">
                    <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ $payment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            @else
                <input type="text" class="form-control" value="{{ ucfirst($payment->status) }}" disabled>
                <input type="hidden" name="status" value="{{ $payment->status }}">
            @endif
            @error('status')
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
                @if($payment->attachment)
                    📎 Current File: <a href="{{ asset('storage/payments/' . $payment->attachment) }}" target="_blank">{{ basename($payment->attachment) }}</a>
                @endif
            </div>
            @error('attachment')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Update Payment</button>
        <a href="{{ route('finance.payments.index') }}" class="btn btn-secondary">Back</a>
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
