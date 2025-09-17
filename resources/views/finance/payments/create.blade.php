    @extends('master')

    @section('content')
    <div class="container py-4">
        <h4 class="mb-4">Add Payment</h4>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('finance.payments.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Payment Reference (Auto-generated, readonly) --}}
<div class="mb-3">
    <label for="payment_ref" class="form-label">Payment Reference</label>
    <input type="text" name="payment_ref" id="payment_ref" class="form-control" 
        value="{{ old('payment_ref', $payment_ref) }}" readonly>
    @error('payment_ref')
        <small class="text-danger">{{ $message }}</small>
    @enderror
</div>


            {{-- Invoice Dropdown (Invoice No show kare) --}}
            <div class="mb-3">
                <label for="invoice_id" class="form-label">Invoice</label>
                <select name="invoice_id" id="invoice_id" class="form-control" required>
                    <option value="">Select Invoice</option>
                    @foreach($invoices as $invoice)
                        <option value="{{ $invoice->id }}" {{ old('invoice_id') == $invoice->id ? 'selected' : '' }}>
                            {{ $invoice->invoice_no }} {{-- yaha invoice_no show hoga --}}
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
        <input type="date" name="payment_date" id="payment_date" class="form-control" value="{{ old('payment_date') }}" required>
        @error('payment_date')
            <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>


            {{-- Amount --}}
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" name="amount" id="amount" class="form-control" step="0.01" value="{{ old('amount') }}" required>
                @error('amount')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Payment Method --}}
            <div class="mb-3">
                <label for="method" class="form-label">Payment Method</label>
                <select name="method" id="method" class="form-control" required>
                    <option value="">Select Method</option>
                    <option value="Cash" {{ old('method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                    <option value="Bank" {{ old('method') == 'Bank' ? 'selected' : '' }}>Bank</option>
                    <option value="Online" {{ old('method') == 'Online' ? 'selected' : '' }}>Online</option>
                </select>
                @error('method')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Status (hidden & always pending) --}}
            <div class="mb-3">
                <label class="form-label">Status</label>
                <input type="text" value="pending" class="form-control" disabled>
                <input type="hidden" name="status" value="pending">
            </div>

            {{-- Attachment --}}
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

            {{-- Buttons --}}
            <div class="mb-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Save Payment</button>
                <a href="{{ route('finance.payments.index') }}" class="btn btn-secondary">Back</a>
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
    uploadBox.addEventListener('dragover', (e) => { e.preventDefault(); uploadBox.style.background = '#dee2e6'; });
    uploadBox.addEventListener('dragleave', () => { uploadBox.style.background = '#f8f9fa'; });
    uploadBox.addEventListener('drop', (e) => {
        e.preventDefault();
        if (e.dataTransfer.files.length > 0) { attachmentInput.files = e.dataTransfer.files; showFileName(attachmentInput.files[0]); }
        uploadBox.style.background = '#f8f9fa';
    });
    attachmentInput.addEventListener('change', () => { if (attachmentInput.files.length > 0) showFileName(attachmentInput.files[0]); });

    function showFileName(file) {
        if (file) { filePreview.textContent = "📎 " + file.name + " attached"; }
    }
    </script>
    @endsection
