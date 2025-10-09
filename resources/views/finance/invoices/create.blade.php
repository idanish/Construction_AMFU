@extends('master')
@section('title', 'Create Invoice')
@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper d-flex justify-content-between align-items-center">
            <div class="page-title-heading m-0">
                <div class="page-title-icon">
                    <i class="pe-7s-cash icon-gradient bg-tempting-azure"></i>
                </div>
                <div class="h4 m-0">Create Invoice</div>
            </div>
            <div class="page-title-actions">
                <div class="d-inline-block">
                    <a href="{{ route('finance.invoices.index') }}" class="btn btn-primary mb-3 vip-btn">
                       <i class="bi bi-arrow-left-circle"></i> Go Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="main-card mb-3 card">
        <div class="card-body">
            <form action="{{ route('finance.invoices.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Procurement Selection --}}
                <div class="mb-3">
                    <label class="form-label">Select Procurement</label>
                    <select name="procurement_id" id="procurement_id"
                        class="form-select @error('procurement_id') is-invalid @enderror">
                        <option value="">Select Procurement</option>
                        @foreach ($procurements as $proc)
                            <option value="{{ $proc->id }}" data-amount="{{ $proc->amount }}">
                                {{ $proc->item_name }} ({{ $proc->department->name ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    @error('procurement_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Invoice Number (Readonly) --}}
                <div class="mb-3">
                    <label class="form-label">Invoice No</label>
                    <input type="text" name="invoice_no" class="form-control" value="{{ $invoice_no }}" readonly>
                </div>

                {{-- Vendor Name --}}
                <div class="mb-3">
                    <label class="form-label">Vendor Name</label>
                    <input type="text" name="vendor_name" class="form-control @error('vendor_name') is-invalid @enderror"
                        value="{{ old('vendor_name') }}" required>
                    @error('vendor_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Due Date --}}
                <div class="mb-3">
                    <label class="form-label">Due Date</label>
                    <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror"
                        value="{{ old('due_date') }}" required>
                    @error('due_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Amount --}}
                <div class="mb-3">
                    <label class="form-label">Amount</label>
                    <input type="number" name="amount" id="amount"
                        class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Invoice Date --}}
                <div class="mb-3">
                    <label class="form-label">Invoice Date</label>
                    <input type="date" name="invoice_date"
                        class="form-control @error('invoice_date') is-invalid @enderror" value="{{ old('invoice_date') }}"
                        required>
                    @error('invoice_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="mb-3" hidden>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" @if (auth()->user()->role != 'admin') disabled @endif>
                        <option value="Unpaid" {{ old('status', 'Unpaid') == 'Unpaid' ? 'selected' : '' }}>Unpaid</option>
                        <option value="Paid" {{ old('status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                    @if (auth()->user()->role != 'admin')
                        <input type="hidden" name="status" value="Unpaid">
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
                        <p>Drag & Drop file here or click to upload </br> .jpg, .jpeg, .png, .pdf, .doc, .docx Max: 2 MB</p>
                        <input type="file" name="attachment" id="attachmentInput" hidden>
                    </div>
                    <div id="filePreview" class="mt-2"></div>
                    @error('attachment')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Notes --}}
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                </div>

                {{-- Submit --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success vip-btn">
                        <i class="bi bi-check-lg"></i> Save Invoice
                    </button>
                    <a href="{{ route('finance.invoices.index') }}" class="btn btn-secondary vip-btn">
                        <i class="bi bi-x-octagon"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
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
        const procurementSelect = document.getElementById('procurement_id');
        const amountInput = document.getElementById('amount');

        procurementSelect.addEventListener('change', function() {
            let selected = this.options[this.selectedIndex];
            let amount = selected.getAttribute('data-amount');
            if (amount) {
                amountInput.value = amount; // Default procurement amount set ho jaye
            } else {
                amountInput.value = '';
            }
        });
   
        // ----------------------------------------------------------------------
        // Attachment click issue
        // ----------------------------------------------------------------------

        const uploadBox = document.getElementById('uploadBox');
        const attachmentInput = document.getElementById('attachmentInput');
        const filePreview = document.getElementById('filePreview'); // Previews file name

        // 1. Click Event Handler
        uploadBox.addEventListener('click', function() {
            attachmentInput.click(); // Hidden file input ko click karega
        });

        // 2. Display file name when selected (optional but helpful)
        attachmentInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                filePreview.textContent = 'Selected File: ' + this.files[0].name;
            } else {
                filePreview.textContent = '';
            }
        });
    </script>


@endsection
