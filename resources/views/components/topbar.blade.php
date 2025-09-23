<!-- / Sidebar Menu -->


<!-- Layout container -->
<div class="layout-page">


    <!-- Navbar -->
    <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
        id="layout-navbar">
        <div class="d-flex align-items-center w-100 justify-content-between">
            <!-- Left Side: Welcome -->
            <div class="d-flex flex-column">
                <h6 class="mb-0">
                    Welcome, <span class="fw-bold">{{ Auth::user()->name ?? 'Guest' }}</span>
                </h6>
                <small class="text-muted">
                    {{ Auth::user()->roles->pluck('name')->implode(', ') ?? 'No Role' }}
                </small>
            </div>

            <!-- Right Side: Search + Profile -->
            <div class="d-flex align-items-center">
                <!-- Search bar -->
                <div class="me-3">
                    <div class="d-flex align-items-center">
                        <form action="{{ route('search.results') }}" method="GET" class="input-group">
                            <input type="text" name="query" placeholder="Search..." id="globalSearch"
                                class="form-control form-control-sm border-0 shadow-none" aria-label="Search...">
                            <button type="submit" class="btn btn-light border-0 shadow-none">
                                <i class="bx bx-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
               