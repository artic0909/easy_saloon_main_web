<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Easy Saloon</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        :root {
            --admin-primary: #1a1a1a;
            --admin-accent: #c6a664;
            --admin-accent-glow: rgba(198, 166, 100, 0.3);
            --admin-bg: #fdfbf7;
            --sidebar-width: 280px;
            --sidebar-bg: #111111;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--admin-bg);
            color: #2d3436;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Outfit', sans-serif;
            letter-spacing: -0.02em;
        }

        /* Premium Sidebar */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--sidebar-bg);
            z-index: 1001;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 20px 0 80px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.1) transparent;
        }

        #sidebar::-webkit-scrollbar {
            width: 5px;
        }

        #sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        #sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        #sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--admin-accent);
        }

        .sidebar-header {
            padding: 2.5rem 2rem;
        }

        .sidebar-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: white;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-logo span {
            color: var(--admin-accent);
        }

        .nav-section {
            padding: 0 1.25rem;
            margin-bottom: 2rem;
        }

        .nav-label {
            color: rgba(255, 255, 255, 0.3);
            text-transform: uppercase;
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.15em;
            padding-left: 1rem;
            margin-bottom: 1rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.5);
            padding: 0.9rem 1.25rem;
            border-radius: 1rem;
            margin-bottom: 0.4rem;
            display: flex;
            align-items: center;
            gap: 15px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-link i {
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.05);
            transform: translateX(5px);
        }

        .nav-link.active {
            color: white;
            background: linear-gradient(135deg, var(--admin-accent), #a88d52);
            box-shadow: 0 10px 30px var(--admin-accent-glow);
        }

        .nav-link.active i {
            color: white;
        }

        /* Main Content Area */
        #content {
            margin-left: var(--sidebar-width);
            padding: 0;
            min-height: 100vh;
            transition: all 0.4s ease;
        }

        /* Top Navbar */
        .admin-navbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.25rem 2.5rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .page-header {
            padding: 2.5rem 2.5rem 0 2.5rem;
        }

        .content-body {
            padding: 2rem 2.5rem;
        }

        /* Premium Cards */
        .card {
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.03);
            border-radius: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.75rem 2rem;
        }

        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, var(--admin-primary), #333);
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 1rem;
            font-weight: 600;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
            background: linear-gradient(135deg, #333, #000);
        }

        /* Stat Cards */
        .stat-card {
            overflow: hidden;
            position: relative;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            background: var(--admin-bg);
            color: var(--admin-accent);
            margin-bottom: 1.5rem;
        }

        /* Mobile View */
        @media (max-width: 992px) {
            #sidebar {
                left: calc(var(--sidebar-width) * -1);
            }
            #sidebar.active {
                left: 0;
            }
            #content {
                margin-left: 0 !important;
            }
            .admin-navbar {
                padding: 1rem 1.5rem;
            }
            .page-header, .content-body {
                padding: 1.5rem;
            }
        }

        /* Re-refined Tables */
        .table {
            border-collapse: separate;
            border-spacing: 0 0.75rem;
            margin-top: -0.75rem;
        }

        .table thead th {
            background: transparent;
            border: none;
            color: #b2bec3;
            text-transform: uppercase;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            padding: 1.5rem 1.5rem;
        }

        .table tbody tr {
            background: white;
            border-radius: 1.25rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.02);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .table tbody tr:hover {
            transform: scale(1.01) translateY(-2px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            z-index: 10;
        }

        .table tbody td {
            padding: 1.5rem 1.5rem;
            border: none;
            vertical-align: middle;
        }

        .table tbody td:first-child {
            border-radius: 1.25rem 0 0 1.25rem;
        }

        .table tbody td:last-child {
            border-radius: 0 1.25rem 1.25rem 0;
        }

        /* Premium Buttons Re-redesign */
        .btn {
            border-radius: 0.9rem;
            padding: 0.75rem 1.75rem;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1a1a1a 0%, #333333 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #333333 0%, #000000 100%);
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
            color: white;
        }

        .btn-accent {
            background: linear-gradient(135deg, var(--admin-accent) 0%, #a88d52 100%);
            color: white;
            box-shadow: 0 8px 20px var(--admin-accent-glow);
        }

        .btn-accent:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px var(--admin-accent-glow);
            color: white;
        }

        .btn-light {
            background: #ffffff;
            color: #2d3436;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .btn-light:hover {
            background: #f8f9fa;
            border-color: rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        /* Status Badges */
        .badge {
            padding: 0.6em 1.2em;
            font-weight: 700;
            font-size: 0.7rem;
            letter-spacing: 0.05em;
            border-radius: 2rem;
            text-transform: uppercase;
        }

        .badge.bg-success-subtle { background: #e8f8f0; color: #20c997; }
        .badge.bg-primary-subtle { background: #eef2ff; color: #4f46e5; }
        .badge.bg-warning-subtle { background: #fffcf0; color: #f59e0b; }
        .badge.bg-danger-subtle { background: #fff5f5; color: #ff4d4d; }

        /* Avatars */
        .avatar-sm { width: 40px; height: 40px; border-radius: 0.8rem; }
        .avatar-md { width: 50px; height: 50px; border-radius: 1rem; }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar -->
    <div id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="bi bi-stars"></i>
                <div>EASY<span>SALOON</span></div>
            </div>
        </div>

        <div class="nav-section mt-4">
            <p class="nav-label">Core Dashboard</p>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Analytics</span>
            </a>

            <!-- Category wise -->
            <a href="{{ route('admin.categories.index') }}" class="nav-link {{ Route::is('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-layers-fill"></i>
                <span>Categories</span>
            </a>

            <!-- Sub Category wise -->
            <a href="{{ route('admin.subcategories.index') }}" class="nav-link {{ Route::is('admin.subcategories.*') ? 'active' : '' }}">
                <i class="bi bi-layers-fill"></i>
                <span>Sub Categories</span>
            </a>

            <!-- Equipment Use -->
            <a href="{{ route('admin.equipment_uses.index') }}" class="nav-link {{ Route::is('admin.equipment_uses.*') ? 'active' : '' }}">
                <i class="bi bi-layers-fill"></i>
                <span>Equipment Use</span>
            </a>
        </div>

        <div class="nav-section">
            <p class="nav-label">Operations</p>
            <a href="{{ route('admin.bookings.index') }}" class="nav-link {{ Route::is('admin.bookings.*') ? 'active' : '' }}">
                <i class="bi bi-calendar2-check-fill"></i>
                <span>Bookings</span>
            </a>
            <a href="{{ route('admin.tracking.index') }}" class="nav-link {{ Route::is('admin.tracking.*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt-fill"></i>
                <span>Live Map</span>
            </a>
        </div>

        <div class="nav-section">
            <p class="nav-label">Management</p>

            <a href="{{ route('admin.staff.index') }}" class="nav-link {{ Route::is('admin.staff.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge-fill"></i>
                <span>Staffs</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="nav-link {{ Route::is('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span>Customers</span>
            </a>

            <a href="{{ route('admin.services.index') }}" class="nav-link {{ Route::is('admin.services.*') ? 'active' : '' }}">
                <i class="bi bi-scissors"></i>
                <span>Services</span>
            </a>
            <a href="{{ route('admin.packages.index') }}" class="nav-link {{ Route::is('admin.packages.*') ? 'active' : '' }}">
                <i class="bi bi-box-fill"></i>
                <span>Packages</span>
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ Route::is('admin.coupons.*') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated-fill"></i>
                <span>Discounts</span>
            </a>
        </div>

        <div class="nav-section">
            <p class="nav-label">Finance & Content</p>
            <a href="{{ route('admin.reports.index') }}" class="nav-link {{ Route::is('admin.reports.*') ? 'active' : '' }}">
                <i class="bi bi-pie-chart-fill"></i>
                <span>Reports</span>
            </a>
            <a href="{{ route('admin.cms.banners.index') }}" class="nav-link {{ Route::is('admin.cms.*') ? 'active' : '' }}">
                <i class="bi bi-layout-sidebar-inset"></i>
                <span>CMS Content</span>
            </a>
        </div>

        <div class="mt-auto p-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-outline-danger w-100 rounded-pill border-0 py-3" style="background: rgba(220, 53, 69, 0.05);">
                    <i class="bi bi-box-arrow-left me-2"></i> Sign Out
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div id="content">
        <!-- Top Navbar -->
        <nav class="admin-navbar d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-4">
                <button class="btn border-0 p-0 d-lg-none" id="sidebarToggle">
                    <i class="bi bi-list fs-2"></i>
                </button>
                <div class="search-box d-none d-md-flex align-items-center gap-3 px-4 py-2 bg-light rounded-pill" style="width: 300px;">
                    <i class="bi bi-search text-muted"></i>
                    <input type="text" class="form-control border-0 bg-transparent p-0 small" placeholder="Search data...">
                </div>
            </div>
            
            <div class="d-flex align-items-center gap-4">
                <div class="position-relative cursor-pointer">
                    <i class="bi bi-bell fs-5 text-muted"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 8px;">3+</span>
                </div>
                
                <div class="dropdown">
                    <div class="d-flex align-items-center gap-3 cursor-pointer" data-bs-toggle="dropdown">
                        <div class="text-end d-none d-sm-block">
                            <h6 class="mb-0 fw-bold">{{ auth()->user()->name }}</h6>
                            <small class="text-muted text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 1px;">Administrator</small>
                        </div>
                        <div class="avatar-md bg-white border rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 45px; height: 45px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-3 p-2 rounded-4">
                        <li><a class="dropdown-item py-2 rounded-3" href="{{ route('dashboard') }}"><i class="bi bi-person me-2"></i> My Profile</a></li>
                        <li><hr class="dropdown-divider opacity-50"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item py-2 rounded-3 text-danger"><i class="bi bi-box-arrow-left me-2"></i> Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="page-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div>
                <h2 class="fw-black mb-1">@yield('page_title', 'Dashboard Overview')</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted small">Admin</a></li>
                        <li class="breadcrumb-item active small" aria-current="page">@yield('page_title', 'Dashboard')</li>
                    </ol>
                </nav>
            </div>
            @yield('page_actions')
        </div>

        <div class="content-body">
            @yield('content')
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select options",
                allowClear: true,
                width: '100%'
            });
        });
    </script>

    <style>
        .select2-container--default .select2-selection--multiple {
            background-color: #f8f9fa;
            border: none;
            border-radius: 1rem;
            padding: 0.5rem 1rem;
            min-height: 55px;
        }
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border: 1px solid var(--admin-accent);
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: var(--admin-accent);
            border: none;
            color: white;
            border-radius: 0.5rem;
            padding: 2px 10px;
            margin-top: 7px;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white;
            margin-right: 8px;
        }
        .select2-dropdown {
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            border-radius: 1rem;
            overflow: hidden;
            padding: 10px;
        }
    </style>
    
    <script>
        // Sidebar Toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Toast Helper
        const toast = (message, icon = 'success') => {
            Swal.fire({
                text: message,
                icon: icon,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        };

        @if(session('success'))
            toast("{{ session('success') }}");
        @endif

        @if(session('error'))
            toast("{{ session('error') }}", 'error');
        @endif
    </script>
    @yield('scripts')
</body>
</html>
