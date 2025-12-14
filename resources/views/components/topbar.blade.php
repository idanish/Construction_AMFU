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
                <form action="{{ route('search.results') }}" method="GET" class="input-group">
                    <input type="text" name="query" placeholder="Search..." id="globalSearch"
                        class="form-control form-control-sm border-0 shadow-none" aria-label="Search...">
                    <button type="submit" class="btn btn-light border-0 shadow-none">
                        <i class="bx bx-search"></i>
                    </button>
                </form>
            </div>

            <!-- User Profile Dropdown -->
            <div class="navbar-nav align-items-center">
                <div class="nav-item navbar-dropdown dropdown">

                    @php
                        use Illuminate\Support\Str;
                        $pic = Auth::user()->profile_picture ?? null;
                        $picUrl = asset('assets/img/avatars/1.png');
                        if ($pic) {
                            if (Str::startsWith($pic, ['http://', 'https://'])) {
                                $picUrl = $pic;
                            } elseif (Str::startsWith($pic, 'storage/')) {
                                $picUrl = asset($pic);
                            } else {
                                $picUrl = asset('storage/' . ltrim($pic, '/'));
                            }
                        }
                    @endphp

                    <a class="nav-link dropdown-toggle hide-arrow d-flex align-items-center" href="#"
                        data-bs-toggle="dropdown">

                        <div class="navbar-profile-img">
                            <img src="{{ $picUrl }}" alt="User" class="rounded-circle">
                        </div>

                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.settings') }}">
                                <i class="bx bx-user me-2"></i> My Profile
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="{{ route('logout') }}">
                                <i class="bx bx-power-off me-2"></i> Logout
                            </a>
                        </li>
                    </ul>

                </div>
            </div>

        </div>

    </div>
</nav>
<!-- / Navbar -->


<!-- FIXED CSS -->
<style>
    /* Navbar Profile Image Fix */
    .navbar-profile-img {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #ddd;
        background: #fff;
        line-height: 0; /* remove inline-gap */
    }

    .navbar-profile-img img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* MOST IMPORTANT */
        object-position: center;
        display: block;
        border-radius: 50%;
    }
</style>
