@extends('master')

@section('content')
    <div class="app-page-title">
        <div class="page-title-wrapper d-flex justify-content-between align-items-center">
            <div class="page-title-heading m-0">
                <div class="page-title-icon">
                    <i class="pe-7s-cash icon-gradient bg-tempting-azure"></i>
                </div>
                <div class="h4 m-0">
                    Create Invoice
                </div>
            </div>
            <div class="page-title-actions">
                <div class="d-inline-block">
                    <a href="{{ route('finance.invoices.index') }}" class="btn btn-primary mb-3">Go Back</a>
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
                    <select name="procurement_id" class="form-select @error('procurement_id') is-invalid @enderror">
                        <option value="">Select Procurement</option>
                        @foreach ($procurements as $proc)
                            <option value="{{ $proc->id }}" {{ old('procurement_id') == $proc->id ? 'selected' : '' }}>
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

                {{-- Amount --}}
                <div class="mb-3">
                    <label class="form-label">Amount</label>
                    <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror"
                        value="{{ old('amount') }}">
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Invoice Date --}}
                <div class="mb-3">
                    <label class="form-label">Invoice Date</label>
                    <input type="date" name="invoice_date"
                        class="form-control @error('invoice_date') is-invalid @enderror" value="{{ old('invoice_date') }}">
                    @error('invoice_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="mb-3">
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

                {{-- Attachment (Drag & Drop) --}}
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

                {{-- Notes --}}
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                </div>
                {{-- Submit --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success">Save Invoice</button>
                    <a href="{{ route('finance.invoices.index') }}" class="btn btn-secondary">Cancel</a>
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
            if (attachmentInput.files.length > 0) showFileName(attachmentInput.files[0]);
        });

        function showFileName(file) {
            if (file) filePreview.textContent = "📎 " + file.name + " attached";
        }
    </script>
@endsection