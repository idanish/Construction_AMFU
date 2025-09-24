@extends('master')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Edit Invoice</h2>

        <form action="{{ route('finance.invoices.update', $invoice->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Procurement --}}
            <div class="mb-3">
                <label for="procurement_id" class="form-label">Select Procurement</label>
                <select name="procurement_id" class="form-select">
                    <option value="">Select Procurement</option>
                    @foreach ($procurements as $proc)
                        <option value="{{ $proc->id }}"
                            {{ old('procurement_id', $invoice->procurement_id) == $proc->id ? 'selected' : '' }}>
                            {{ $proc->item_name ?? 'Procurement #' . $proc->id }}
                            ({{ $proc->department->name ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
                @error('procurement_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Invoice No (Readonly) --}}
            <div class="mb-3">
                <label for="invoice_no" class="form-label">Invoice No</label>
                <input type="text" name="invoice_no" class="form-control"
                    value="{{ old('invoice_no', $invoice->invoice_no) }}" readonly>
            </div>

            {{-- Vendor Name --}}
            <div class="mb-3">
                <label for="vendor_name" class="form-label">Vendor Name</label>
                <input type="text" name="vendor_name" class="form-control"
                    value="{{ old('vendor_name', $invoice->vendor_name) }}" required>
                @error('vendor_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Due Date --}}
            <div class="mb-3">
                <label for="due_date" class="form-label">Due Date</label>
                <input type="date" name="due_date" class="form-control"
                    value="{{ old('due_date', $invoice->due_date ? \Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d') : '') }}"
                    required>
                @error('due_date')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Amount --}}
            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" name="amount" class="form-control" value="{{ old('amount', $invoice->amount) }}"
                    required>
                @error('amount')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Invoice Date --}}
            <div class="mb-3">
                <label for="invoice_date" class="form-label">Invoice Date</label>
                <input type="date" name="invoice_date" class="form-control"
                    value="{{ old('invoice_date', $invoice->invoice_date ? \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') : '') }}"
                    required>
                @error('invoice_date')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" class="form-select" @if (auth()->user()->role != 'admin') disabled @endif>
                    <option value="Unpaid" {{ old('status', $invoice->status) == 'Unpaid' ? 'selected' : '' }}>Unpaid
                    </option>
                    <option value="Paid" {{ old('status', $invoice->status) == 'Paid' ? 'selected' : '' }}>Paid</option>
                    <option value="Cancelled" {{ old('status', $invoice->status) == 'Cancelled' ? 'selected' : '' }}>
                        Cancelled</option>
                </select>
                @if (auth()->user()->role != 'admin')
                    <input type="hidden" name="status" value="{{ $invoice->status }}">
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

                @if ($invoice->attachment)
                    <div class="mt-2">
                        📎 Current:
                        <a href="{{ asset('storage/' . $invoice->attachment) }}" target="_blank">View Attachment</a>
                    </div>
                @endif

                <div id="filePreview" class="mt-2"></div>
                @error('attachment')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Notes --}}
            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="3">{{ old('notes', $invoice->notes) }}</textarea>
                @error('notes')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="btn btn-info text-dark vip-btn">
                <i class="bi bi-arrow-repeat"></i> Update
            </button>
            <a href="{{ route('finance.invoices.index') }}" class="btn btn-secondary vip-btn">
                <i class="bi bi-arrow-left-circle"></i> Go Back
            </a>
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
            attachmentInput.files = e.dataTransfer.files;
            showFileName(attachmentInput.files[0]);
            uploadBox.style.background = '#f8f9fa';
        });
        attachmentInput.addEventListener('change', () => {
            if (attachmentInput.files.length > 0) showFileName(attachmentInput.files[0]);
        });

        function showFileName(file) {
            filePreview.textContent = "📎 " + file.name + " attached";
        }
    </script>
@endsection
