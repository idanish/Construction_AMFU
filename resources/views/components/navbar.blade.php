<!-- Layout container -->
<div class="layout-page">

    <!-- Navbar -->
    <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
        id="layout-navbar">
        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
            </a>
        </div>

        <div class="d-flex align-items-center w-100 justify-content-between">

            <!-- Left Side: Welcome -->
            <div class="d-flex flex-column">
                <h6 class="mb-0">
                    Welcome, <span class="fw-bold">{{ Auth::user()->name ?? 'Guest' }}</span>
                </h6>
            </div>

            <!-- Right Side: Search + Profile -->
            <div class="d-flex align-items-center">
                <!-- Search bar -->
                <div class="me-3">
                    <div class="d-flex align-items-center">
                        <form action="{{ route('search.results') }}" method="GET" class="input-group">
                            <input type="text" name="query" placeholder="Search..."
                                class="form-control form-control-sm border-0 shadow-none" aria-label="Search...">
                            <button type="submit" class="btn btn-light border-0 shadow-none">
                                <i class="bx bx-search"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Notification Dropdown -->
                <div class="nav-item dropdown me-3">
                    @php

                    $user = auth()->user();
                    $role = $user->roles->pluck('name')->first();

                    $notifications = \App\Models\Notification::where(function ($q) use ($user, $role)
                    {$q->where('user_id', $user->id)->orWhere(function ($q2) use ($role) {
                    $q2->whereNotNull('role')->where('role', $role);});})->latest()->take(5)->get();

                    $unreadCount = \App\Models\Notification::where(function ($q) use ($user, $role) {
                    $q->where('user_id', $user->id)->orWhere(function ($q2) use ($role)
                    {$q2->whereNotNull('role')->where('role', $role);});})->where('is_read', false)->count();

                    @endphp

                    <button class="btn btn-sm position-relative" type="button" data-bs-toggle="dropdown">
                        <i class="bx bx-bell fs-4"></i>
                        @if ($unreadCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $unreadCount }}
                        </span>
                        @endif
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end" style="width: 320px;">
                        @forelse($notifications as $notification)
                        <li class="px-3 py-2 border-bottom @if (!$notification->is_read) bg-light @endif">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div>{{ $notification->message }}</div>
                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <form method="POST" action="{{ route('notifications.read', $notification->id) }}">
                                    @csrf
                                    <button class="btn btn-link btn-sm p-0">Read</button>
                                </form>
                            </div>
                        </li>
                        @empty
                        <li class="px-3 py-2">No notifications</li>
                        @endforelse
                        <li><a class="dropdown-item text-center" href="{{ route('notifications.index') }}">Show All</a>
                        </li>
                    </ul>
                </div>

                <!-- User Dropdown -->
                <div class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-online">
                                <img src="{{ Auth::user()->profile_picture
                                    ? asset('storage/' . Auth::user()->profile_picture)
                                    : asset('assets/img/avatars/1.png') }}" alt="User Avatar"
                                    class="w-px-40 h-auto rounded-circle" />
                            </div>
                        </div>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <!-- Name & Role -->
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.settings') }}">
                                <div class="d-flex">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar avatar-online">
                                            <img src="{{ Auth::user()->profile_picture
                                    ? asset('storage/' . Auth::user()->profile_picture)
                                    : asset('assets/img/avatars/1.png') }}" alt
                                                class="w-px-40 h-auto rounded-circle" />
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="fw-semibold d-block">{{ Auth::user()->name ?? 'Guest' }}</span>
                                        <small class="text-muted">{{ Auth::user()->username ?? 'Guest' }}</small>
                                    </div>
                                </div>
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>


                        <!-- Profile Settings -->
                        <li>
                            <a class="dropdown-item" href="{{ route('profile.settings') }}">
                                <i class="bx bx-cog me-2"></i>
                                <span class="align-middle">Profile Settings</span>
                            </a>
                        </li>

                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <!-- Logout -->
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bx bx-power-off me-2"></i>
                                    <span class="align-middle">Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    <!-- / Navbar -->