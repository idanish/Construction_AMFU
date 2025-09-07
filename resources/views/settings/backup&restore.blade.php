@extends('master')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-gradient text-white d-flex justify-content-between align-items-center" 
             style="background: linear-gradient(90deg, #6a11cb 0%, #2575fc 100%);">
            <h4 class="mb-0"><i class="bi bi-gear-fill me-2"></i> Backup & Restore</h4>
            <span class="badge bg-light text-dark">Settings</span>
        </div>
        <div class="card-body">

            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Backup Section --}}
            <div class="mb-5">
                <h5 class="fw-bold text-purple"><i class="bi bi-hdd-stack-fill me-2"></i>Create Backup</h5>
                <p class="text-muted">Generate a full system backup including database and files.</p>
                <form action="{{ route('settings.backup.download') }}" method="Get">
                    @csrf
                    <button type="submit" class="btn text-white px-4 py-2 shadow"
                            style="background: linear-gradient(90deg, #36d1dc 0%, #5b86e5 100%); border: none;">
                        <i class="bi bi-download me-2"></i>  Backup
                    </button>
                </form>
            </div>

            <hr>

            {{-- Restore Section --}}
            <div>
                <h5 class="fw-bold text-success"><i class="bi bi-arrow-repeat me-2"></i>Restore Backup</h5>
                <p class="text-muted">Upload a backup file (.SQL) to restore your system data.</p>
                <form action="{{ route('settings.backup.restore') }}" method="Post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="backup_file" class="form-label">Choose Backup File</label>
                        <input type="file" name="backup_file" id="backup_file" class="form-control">
                    </div>
                    <button type="submit" class="btn text-white px-4 py-2 shadow"
                            style="background: linear-gradient(90deg, #11998e 0%, #38ef7d 100%); border: none;">
                        <i class="bi bi-upload me-2"></i> Restore Backup
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
