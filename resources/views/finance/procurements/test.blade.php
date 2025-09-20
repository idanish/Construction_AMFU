<div class="main-card mb-3 card shadow-lg">
        <div class="card-body">
            <form action="{{ route('finance.procurements.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Item Name --}}
                <div class="mb-4">
                    <label for="item_name" class="form-label fw-bold">Item Name</label>
                    <input type="text" name="item_name" class="form-control @error('item_name') is-invalid @enderror"
                        value="{{ old('item_name') }}" placeholder="Enter item name">
                    @error('item_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Quantity --}}
                <div class="mb-4">
                    <label for="quantity" class="form-label fw-bold">Quantity</label>
                    <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                        value="{{ old('quantity') }}" placeholder="Enter quantity">
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Cost Estimate --}}
                <div class="mb-4">
                    <label for="cost_estimate" class="form-label fw-bold">Cost Estimate (PKR)</label>
                    <input type="number" step="0.01" name="cost_estimate"
                        class="form-control @error('cost_estimate') is-invalid @enderror" value="{{ old('cost_estimate') }}"
                        placeholder="Enter estimated cost">
                    @error('cost_estimate')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </form>
        </div>
    </div>
@endsection