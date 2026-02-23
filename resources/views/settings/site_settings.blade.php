@extends('master')
@section('title', 'Settings')
@section('content')

    <div class="">
        <div class="card-header">
            <h5 class="mb-0">⚙️ General Settings</h5>
        </div>
        <div class="card-body">

            {{-- Success/Error Messages --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <p>Total Amount: {{ $appSettings['currency_symbol'] ?? '$' }} 100</p>
            <p>Total Amount: {{ $appSettings['site_name'] ?? '$' }} 200</p>
            <p>Total Amount: {{ $appSettings['sidebar_label'] ?? '$' }} 300</p>

            <hr>

            <div class="container mt-5">
            <h3>Application Settings</h3>
            <hr>

            <form action="/update-settings" method="POST">
                @csrf

                <ul class="nav nav-tabs" id="settingsTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                            General Settings
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="appearance-tab" data-bs-toggle="tab" data-bs-target="#appearance" type="button" role="tab">
                            Appearance & UI
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab">
                            Contact Info
                        </button>
                    </li>
                </ul>

                <div class="tab-content p-4 border border-top-0" id="settingsTabContent">
                    
                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label">Site Name</label>
                            <input type="text" name="site_name" value="{{ $settings['site_name'] ?? 'My Website' }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Currency Symbol</label>
                            <input type="text" name="currency_symbol" value="{{ $settings['currency_symbol'] ?? '$' }}" class="form-control">
                        </div>
                    </div>

                    <div class="tab-pane fade" id="appearance" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label">Sidebar Menu Label</label>
                            <input type="text" name="sidebar_label" value="{{ $settings['sidebar_label'] ?? 'Dashboard' }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Theme Mode</label>
                            <select name="theme_mode" class="form-control">
                                <option value="light" {{ ($settings['theme_mode'] ?? '') == 'light' ? 'selected' : '' }}>Light</option>
                                <option value="dark" {{ ($settings['theme_mode'] ?? '') == 'dark' ? 'selected' : '' }}>Dark</option>
                            </select>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="contact" role="tabpanel">
                        <div class="mb-3">
                            <label class="form-label">Admin Email</label>
                            <input type="email" name="admin_email" value="{{ $settings['admin_email'] ?? 'admin@example.com' }}" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Support Phone</label>
                            <input type="text" name="support_phone" value="{{ $settings['support_phone'] ?? '+12345678' }}" class="form-control">
                        </div>
                    </div>

                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Save All Settings</button>
                </div>
            </form>
        </div>

            @if(session('success'))
                <div style="margin-top: 10px; color: green; font-weight: bold;">
                    {{ session('success') }}
                </div>
            @endif

        </div>
    </div>

@endsection
