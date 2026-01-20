<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Sistem Presensi Siswa</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --admin-color: #dc3545;
            --guru-color: #198754;
            --ortu-color: #0d6efd;
        }
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .sidebar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            height: 100vh;
            position: fixed;
            width: 250px;
            transition: all 0.3s;
            box-shadow: 3px 0 10px rgba(0,0,0,0.1);
            z-index: 1000;
            overflow-y: auto;
            /* Untuk mobile: pastikan bisa di-scroll */
            -webkit-overflow-scrolling: touch;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-header {
            padding: 20px 15px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            flex-shrink: 0;
        }
        
        .sidebar-menu {
            padding: 15px 0;
            flex: 1;
            overflow-y: auto;
        }
        
        /* Sidebar scroll khusus untuk mobile */
        @media (max-width: 768px) {
            .sidebar {
                overflow-y: auto;
                height: 100vh;
            }
            .sidebar-menu {
                max-height: none;
                overflow-y: auto;
            }
        }
        .sidebar-menu a {
            color: rgba(255,255,255,0.8);
            padding: 10px 15px;
            margin: 2px 10px;
            display: block;
            text-decoration: none;
            transition: all 0.3s;
            border-left: 3px solid transparent;
            border-radius: 8px;
            font-size: 0.9rem;
        }
        .sidebar-menu a:hover {
            color: white;
            background: rgba(255,255,255,0.1);
            border-left: 3px solid white;
        }
        .sidebar-menu a.active {
            color: white;
            background: rgba(255,255,255,0.15);
            border-left: 3px solid white;
        }
        .sidebar-menu a i {
            width: 22px;
            text-align: center;
            margin-right: 8px;
            font-size: 1rem;
        }
        .main-content {
            margin-left: 250px;
            padding: 15px;
            transition: all 0.3s;
            min-height: 100vh;
            width: calc(100% - 250px);
        }
        .navbar-top {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 20px;
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
            border: none;
        }
        .navbar-top h5 {
            color: white;
            font-weight: 600;
            margin: 0;
            font-size: 1.1rem;
        }
        .navbar-top .text-muted {
            color: rgba(255, 255, 255, 0.9) !important;
        }
        .navbar-top .btn-mobile-menu {
            color: white;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        .navbar-top .btn-mobile-menu:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        .time-display {
            background: rgba(255, 255, 255, 0.15);
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
        }
        .time-display i {
            font-size: 0.85rem;
        }
        .role-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-admin { background: var(--admin-color); color: white; }
        .badge-guru { background: var(--guru-color); color: white; }
        .badge-ortu { background: var(--ortu-color); color: white; }
        .card-dashboard {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: transform 0.3s;
            margin-bottom: 15px;
            overflow: hidden;
        }
        .card-dashboard:hover {
            transform: translateY(-3px);
        }
        .card-icon {
            font-size: 2rem;
            opacity: 0.8;
        }
        .btn-mobile-menu {
            display: none;
            background: none;
            border: none;
            color: var(--primary-color);
            font-size: 1.3rem;
            padding: 5px;
        }
        
        /* Container fixes */
        .container-fluid {
            padding-left: 0;
            padding-right: 0;
        }
        .container-fluid > .row {
            margin-left: -8px;
            margin-right: -8px;
        }
        .container-fluid > .row > [class*="col-"] {
            padding-left: 8px;
            padding-right: 8px;
        }
        
        /* Card fixes */
        .card {
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 15px;
        }
        .card-body {
            padding: 15px;
        }
        .card-header {
            padding: 12px 15px;
        }
        
        /* Table fixes */
        .table-responsive {
            margin: 0 -8px;
            padding: 0 8px;
        }
        .table {
            margin-bottom: 0;
        }
        .table th, .table td {
            padding: 10px 12px;
            font-size: 0.9rem;
        }
        
        /* Form fixes */
        .form-control, .form-select {
            padding: 8px 12px;
            font-size: 0.9rem;
            border-radius: 8px;
        }
        
        /* Button fixes */
        .btn {
            padding: 8px 16px;
            font-size: 0.9rem;
            border-radius: 8px;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.85rem;
        }
        
        /* Pagination fixes */
        .pagination {
            margin-bottom: 0;
        }
        .pagination .page-link {
            padding: 6px 12px;
            font-size: 0.85rem;
            border-radius: 6px;
            margin: 0 2px;
        }
        .pagination .bi {
            font-size: 0.85rem;
            vertical-align: middle;
        }
        
        /* Alert fixes */
        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        
        @media (max-width: 992px) {
            .main-content {
                padding: 12px;
            }
            .navbar-top {
                padding: 8px 15px;
            }
            .navbar-top h5 {
                font-size: 1rem;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
                width: 250px;
            }
            .sidebar.active {
                margin-left: 0;
            }
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 10px;
            }
            .btn-mobile-menu {
                display: block;
            }
            .navbar-top {
                padding: 6px 10px;
                margin-bottom: 15px;
                border-radius: 10px;
            }
            .navbar-top h5 {
                font-size: 0.95rem;
            }
            
            /* Mobile table fixes */
            .table-responsive {
                margin: 0 -5px;
                padding: 0 5px;
            }
            .table th, .table td {
                padding: 8px 10px;
                font-size: 0.85rem;
            }
            
            /* Mobile card fixes */
            .card-body {
                padding: 12px;
            }
            .card-header {
                padding: 10px 12px;
            }
        }
        
        @media (max-width: 576px) {
            .main-content {
                padding: 8px;
            }
            .container-fluid > .row {
                margin-left: -5px;
                margin-right: -5px;
            }
            .container-fluid > .row > [class*="col-"] {
                padding-left: 5px;
                padding-right: 5px;
            }
            
            /* Extra small screens */
            .btn {
                padding: 6px 12px;
                font-size: 0.85rem;
            }
            .form-control, .form-select {
                padding: 6px 10px;
                font-size: 0.85rem;
            }
        }
        
        .footer {
            text-align: center;
            padding: 15px;
            color: #6c757d;
            font-size: 0.85rem;
            border-top: 1px solid #dee2e6;
            margin-top: 30px;
        }
        
        /* Utility classes for overflow prevention */
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .no-overflow {
            overflow: hidden;
        }
        
        .scrollable-content {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4 class="mb-0">SISTEM PRESENSI</h4>
            <small class="opacity-75">Siswa Berbasis Web</small>
            <div class="mt-3 time-display-container">
                <div class="time-display">
                    <i class="bi bi-calendar3"></i>
                    <span>{{ date('d F Y') }}</span>
                </div>
                <div class="time-display">
                    <i class="bi bi-clock"></i>
                    <span id="currentTime">{{ date('H:i:s') }}</span>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            @if(Auth::check())
                <div class="px-3 mb-3">
                    <div class="text-center">
                        <div class="mb-2">
                            <i class="bi bi-person-circle" style="font-size: 3rem;"></i>
                        </div>
                        <h6 class="mb-1">{{ Auth::user()->name }}</h6>
                        <span class="role-badge badge-{{ Auth::user()->role }}">
                            {{ strtoupper(Auth::user()->role) }}
                        </span>
                    </div>
                </div>
                
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.students.index') }}" class="{{ request()->is('admin/students*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Data Siswa
                    </a>
                    <a href="{{ route('admin.gurus.index') }}" class="{{ request()->is('admin/gurus*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge"></i> Data Guru
                    </a>
                    <a href="{{ route('admin.ortus.index') }}" class="{{ request()->is('admin/ortus*') ? 'active' : '' }}">
                        <i class="bi bi-house-door"></i> Data Orang Tua
                    </a>
                    <a href="{{ route('admin.schedules.index') }}" class="{{ request()->is('admin/schedules*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-week"></i> Jadwal Kelas
                    </a>
                    <a href="{{ route('admin.holidays.index') }}" class="{{ request()->is('admin/holidays*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-x"></i> Hari Libur
                    </a>
                    <a href="{{ route('admin.students.index') }}" class="{{ request()->is('admin/barcode*') ? 'active' : '' }}">
                        <i class="bi bi-qr-code-scan"></i> Barcode
                    </a>
                    <a href="{{ route('admin.permissions.index') }}" class="{{ request()->is('admin/permissions*') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-check"></i> Izin Siswa
                    </a>
                    <a href="{{ route('admin.flags.index') }}" class="{{ request()->is('admin/flags*') ? 'active' : '' }}">
                        <i class="bi bi-flag"></i> Flagging
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="{{ request()->is('admin/reports*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-pdf"></i> Laporan PDF
                    </a>
                @endif

                @if(Auth::user()->isGuru())
                    <a href="{{ route('guru.dashboard') }}" class="{{ request()->is('guru/dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="{{ route('guru.attendance.scanner') }}" class="{{ request()->is('guru/attendance/scanner') ? 'active' : '' }}">
                        <i class="bi bi-qr-code-scan"></i> Scan Barcode
                    </a>
                    <a href="{{ route('guru.attendance.today') }}" class="{{ request()->is('guru/attendance/today') ? 'active' : '' }}">
                        <i class="bi bi-list-check"></i> Absensi Hari Ini
                    </a>
                    <a href="{{ route('guru.attendance.belum-pulang') }}" class="{{ request()->is('guru/attendance/belum-pulang') ? 'active' : '' }}">
                        <i class="bi bi-exclamation-triangle"></i> Belum Pulang
                    </a>
                    <a href="{{ route('guru.attendance.manual') }}" class="{{ request()->is('guru/attendance/manual') ? 'active' : '' }}">
                        <i class="bi bi-keyboard"></i> Absensi Manual
                    </a>
                    <a href="{{ route('guru.permissions.index') }}" class="{{ request()->is('guru/permissions*') ? 'active' : '' }}">
                        <i class="bi bi-check-circle"></i> Verifikasi Izin
                    </a>
                    <a href="{{ route('guru.flags.index') }}" class="{{ request()->is('guru/flags*') ? 'active' : '' }}">
                        <i class="bi bi-flag"></i> Flagging
                    </a>
                @endif

                @if(Auth::user()->isOrtu())
                    <a href="{{ route('ortu.dashboard') }}" class="{{ request()->is('ortu/dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="{{ route('ortu.attendance.index') }}" class="{{ request()->is('ortu/attendance*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i> Absensi Anak
                    </a>
                    <a href="{{ route('ortu.charts.index') }}" class="{{ request()->is('ortu/charts*') ? 'active' : '' }}">
                        <i class="bi bi-graph-up"></i> Grafik Kehadiran
                    </a>
                    <a href="{{ route('ortu.permissions.create') }}" class="{{ request()->is('ortu/permissions*') ? 'active' : '' }}">
                        <i class="bi bi-envelope-paper"></i> Ajukan Izin
                    </a>
                    <a href="{{ route('ortu.flags.create') }}" class="{{ request()->is('ortu/flags*') ? 'active' : '' }}">
                        <i class="bi bi-flag"></i> Flag Anak
                    </a>
                @endif

                <div class="mt-4 px-3">
                    <form method="POST" action="{{ route('logout') }}" id="logout-form" class="w-100">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light w-100 d-flex align-items-center justify-content-center" 
                                style="padding: 8px 15px; border-color: rgba(255,255,255,0.3);">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Navbar - Kembali ke Nama/Title -->
        <nav class="navbar-top">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="btn-mobile-menu me-3" id="mobileMenuToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <div>
                        <h5 class="mb-0">@yield('title', 'Dashboard')</h5>
                        <small class="opacity-75">Sistem Presensi Siswa</small>
                    </div>
                </div>
                <!-- Logout button kecil di topbar -->
                <div class="d-flex align-items-center">
                    <form method="POST" action="{{ route('logout') }}" class="mb-0">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-light d-flex align-items-center" 
                                style="padding: 4px 10px; font-size: 0.8rem;">
                            <i class="bi bi-box-arrow-right me-1"></i>
                            <span class="d-none d-md-inline">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="mb-0">
                Sistem Presensi Siswa &copy; {{ date('Y') }} | 
                <span class="text-primary">Mobile-Friendly Design</span>
            </p>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Update current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('currentTime').textContent = timeString;
        }
        setInterval(updateTime, 1000);

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !mobileMenuToggle.contains(event.target) &&
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
            }
        });
        // Handle CSRF token expired and auto logout
        document.addEventListener('DOMContentLoaded', function() {
            // Setup CSRF token for all AJAX requests
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Intercept fetch requests
            const originalFetch = window.fetch;
            window.fetch = function(...args) {
                // Add CSRF token to headers if not already present
                if (args[1] && args[1].headers) {
                    if (!args[1].headers['X-CSRF-TOKEN'] && !args[1].headers['X-CSRF-Token']) {
                        args[1].headers['X-CSRF-TOKEN'] = csrfToken;
                    }
                } else if (args[1]) {
                    args[1].headers = {
                        'X-CSRF-TOKEN': csrfToken,
                        ...args[1].headers
                    };
                }
                
                return originalFetch.apply(this, args).then(response => {
                    // Handle 419 Page Expired
                    if (response.status === 419) {
                        console.warn('CSRF token expired, redirecting to login');
                        // Show notification (persist - tidak auto hide)
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-warning alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
                        alertDiv.style.zIndex = '9999';
                        alertDiv.innerHTML = `
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Session telah berakhir. Silakan login kembali.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        document.body.appendChild(alertDiv);
                        
                        return Promise.reject(new Error('CSRF token expired'));
                    }
                    return response;
                });
            };
            
            // Auto logout after 30 minutes of inactivity (sesuai session lifetime)
            let inactivityTimer;
            function resetInactivityTimer() {
                clearTimeout(inactivityTimer);
                // 30 minutes = 30 * 60 * 1000 = 1,800,000 ms
                inactivityTimer = setTimeout(() => {
                    // Check if user is still logged in
                    fetch('/debug/user', {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message === 'Not authenticated') {
                            // User sudah logout, redirect ke login
                            window.location.href = '{{ route("login") }}';
                        }
                    })
                    .catch(() => {
                        // Jika error, assume session expired
                        document.getElementById('logout-form')?.submit();
                    });
                }, 25 * 60 * 1000); // 25 menit (5 menit sebelum session benar-benar expired)
            }
            
            // Reset timer on user activity
            ['click', 'mousemove', 'keypress', 'scroll', 'touchstart'].forEach(event => {
                document.addEventListener(event, resetInactivityTimer);
            });
            
            // Start the timer
            resetInactivityTimer();
            
            // Check session status periodically
            setInterval(() => {
                fetch('/debug/user', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message === 'Not authenticated') {
                        window.location.href = '{{ route("login") }}';
                    }
                })
                .catch(() => {
                    // Ignore errors
                });
            }, 5 * 60 * 1000); // Check every 5 minutes
        });
    </script>
    @stack('scripts')
</body>
</html>
