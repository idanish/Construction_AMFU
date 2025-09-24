@extends('master')
@section('title', 'Edit Budget')
@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Edit Budget</h2>

        <form action="{{ route('finance.budgets.update', $budget->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Department --}}
            <div class="mb-3">
                <label for="department_id" class="form-label">Department</label>
                <select name="department_id" id="department_id" class="form-select" required>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}"
                            {{ old('department_id', $budget->department_id) == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
                @error('department_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Year --}}
            <div class="mb-3">
                <label for="year" class="form-label">Year</label>
                <input type="number" name="year" id="year" class="form-control"
                    value="{{ old('year', $budget->year) }}" required>
                @error('year')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Allocated --}}
            <div class="mb-3">
                <label for="allocated" class="form-label">Allocated Amount</label>
                <input type="number" step="0.01" name="allocated" id="allocated" class="form-control"
                    value="{{ old('allocated', $budget->allocated) }}" required>
                @error('allocated')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Spent --}}
            <div class="mb-3">
                <label for="spent" class="form-label">Spent Amount</label>
                <input type="number" step="0.01" name="spent" id="spent" class="form-control"
                    value="{{ old('spent', $budget->spent) }}">
                @error('spent')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Balance --}}
            <div class="mb-3">
                <label for="balance" class="form-label">Balance</label>
                {{-- Visible disabled field --}}
                <input type="number" id="balance" class="form-control" value="{{ $budget->balance }}" disabled>

                {{-- Hidden field jo backend ko bhejega --}}
                <input type="hidden" name="balance" id="hidden_balance" value="{{ $budget->balance }}">
            </div>





            {{-- Notes --}}
            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea name="notes" id="notes" class="form-control">{{ old('notes', $budget->notes) }}</textarea>
                @error('notes')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Attachment --}}
            <div class="mb-3">
                <label for="attachment" class="form-label">Attachment (Optional)</label>
                <input type="file" name="attachment" id="attachment" class="form-control"
                    accept=".pdf,.jpg,.jpeg,.png,.docx,.xlsx">
                @if ($budget->attachment)
                    <p class="mt-2">
                        Current File: <a href="{{ asset('storage/' . $budget->attachment) }}" target="_blank">View /
                            Download</a>
                    </p>
                @endif
                @error('attachment')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                @if (auth()->check() && auth()->user()->role === 'admin')
                    <select name="status" id="status" class="form-select">
                        <option value="Pending" {{ old('status', $budget->status) == 'Pending' ? 'selected' : '' }}>Pending
                        </option>
                        <option value="Approved" {{ old('status', $budget->status) == 'Approved' ? 'selected' : '' }}>
                            Approved
                        </option>
                        <option value="Rejected" {{ old('status', $budget->status) == 'Rejected' ? 'selected' : '' }}>
                            Rejected
                        </option>
                    </select>
                @else
                    <select class="form-select" disabled>
                        <option>{{ $budget->status }}</option>
                    </select>
                    <input type="hidden" name="status" value="{{ $budget->status }}">
                @endif
            </div>

            <button type="submit" class="btn btn-info text-dark vip-btn">
                <i class="bi bi-arrow-repeat"></i> Update
            </button>
            <a href="{{ route('finance.budgets.index') }}" class="btn btn-secondary vip-btn">
                <i class="bi bi-arrow-left-circle"></i> Go Back
            </a>
        </form>
    </div>

    {{-- Live Balance Script --}}
    <script>
        const allocatedInput = document.getElementById('allocated');
        const spentInput = document.getElementById('spent');
        const balanceInput = document.getElementById('balance');
        const hiddenBalanceInput = document.getElementById('hidden_balance');

        function updateBalance() {
            const allocated = parseFloat(allocatedInput.value) || 0;
            const spent = parseFloat(spentInput.value) || 0;
            const balance = (allocated - spent).toFixed(2);

            balanceInput.value = balance; // visible field update
            hiddenBalanceInput.value = balance; // hidden field backend ko bheje
        }

        allocatedInput.addEventListener('input', updateBalance);
        spentInput.addEventListener('input', updateBalance);
    </script>
@endsection
