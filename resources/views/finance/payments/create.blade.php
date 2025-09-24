@extends('master')
@section('title', 'Add new Payment')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper d-flex justify-content-between align-items-center">
            <div class="page-title-heading m-0">
                <div class="page-title-icon">
                    <i class="pe-7s-cash icon-gradient bg-tempting-azure"></i>
                </div>
                <div class="h4 m-0">Add New Payment</div>
            </div>
            <div class="page-title-actions">
                <div class="d-inline-block">
                    <a href="{{ route('finance.payments.index') }}" class="btn btn-primary mb-3 vip-btn">
                        <i class="bi bi-arrow-left-circle"></i> Go Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body">
            <form action="{{ route('finance.payments.store') }}" method="POST" enctype="multipart/form-data"
                id="paymentForm">
                @csrf

                <div class="mb-3">
                    <label for="payment_ref" class="form-label">Payment Reference</label>
                    <input type="text" name="payment_ref" id="payment_ref"
                        class="form-control @error('payment_ref') is-invalid @enderror"
                        value="{{ old('payment_ref', $payment_ref) }}" readonly>
                    @error('payment_ref')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="invoice_id" class="form-label">Invoice</label>
                    <select name="invoice_id" id="invoice_id" class="form-select @error('invoice_id') is-invalid @enderror"
                        required>
                        <option value="">Select Invoice</option>
                        @foreach ($invoices as $invoice)
                            @php
                                $remaining = $invoice->amount - $invoice->payments()->sum('amount');
                            @endphp
                            <option value="{{ $invoice->id }}" data-amount="{{ $remaining }}"
                                {{ old('invoice_id') == $invoice->id ? 'selected' : '' }}>
                                {{ $invoice->invoice_no }} (Remaining: {{ $remaining }})
                            </option>
                        @endforeach
                    </select>
                    @error('invoice_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="payment_date" class="form-label">Payment Date</label>
                    <input type="date" name="payment_date" id="payment_date"
                        class="form-control @error('payment_date') is-invalid @enderror" value="{{ old('payment_date') }}"
                        required>
                    @error('payment_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" name="amount" id="amount"
                        class="form-control @error('amount') is-invalid @enderror" step="0.01"
                        value="{{ old('amount') }}" required>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="method" class="form-label">Payment Method</label>
                    <select name="method" id="method" class="form-select @error('method') is-invalid @enderror"
                        required>
                        <option value="">Select Method</option>
                        <option value="Cash" {{ old('method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        <option value="Bank" {{ old('method') == 'Bank' ? 'selected' : '' }}>Bank</option>
                        <option value="Online" {{ old('method') == 'Online' ? 'selected' : '' }}>Online</option>
                    </select>
                    @error('method')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3" hidden>
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select" required>
                        <option value="partial">Partial Payment</option>
                        <option value="completed">Full Payment</option>
                    </select>
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
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="vip-btn btn-submit">
                        <i class="bi bi-check-lg"></i> Submit
                    </button>

                    <a href="{{ route('finance.payments.index') }}" class="btn btn-secondary vip-btn">
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
        const invoiceSelect = document.getElementById('invoice_id');
        const amountInput = document.getElementById('amount');
        const statusSelect = document.getElementById('status');

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
                filePreview.textContent = "📎 " + attachmentInput.files[0].name + " attached";
            }
            uploadBox.style.background = '#f8f9fa';
        });
        attachmentInput.addEventListener('change', () => {
            if (attachmentInput.files.length > 0) filePreview.textContent = "📎 " + attachmentInput.files[0].name +
                " attached";
        });

        invoiceSelect.addEventListener('change', () => {
            let option = invoiceSelect.options[invoiceSelect.selectedIndex];
            let remaining = parseFloat(option.dataset.amount);
            amountInput.value = remaining;
            // auto status
            if (remaining < parseFloat(option.dataset.amount)) {
                statusSelect.value = 'partial';
            }
        });

        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            let selectedOption = invoiceSelect.options[invoiceSelect.selectedIndex];
            let remaining = parseFloat(selectedOption.dataset.amount);
            let enteredAmount = parseFloat(amountInput.value);

            if (enteredAmount > remaining) {
                e.preventDefault();
                alert('Error: Amount cannot be greater than invoice remaining balance (' + remaining + ')');
            } else if (enteredAmount < remaining) {
                statusSelect.value = 'partial';
            } else {
                statusSelect.value = 'completed';
            }
        });
    </script>
@endsection
