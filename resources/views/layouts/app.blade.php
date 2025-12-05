<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sewa Motor')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            flex-direction: row;
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);
            color: white;
            padding: 20px;
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        /* Sidebar Brand */
        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 30px;
            padding: 10px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .sidebar-brand:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .sidebar-brand i {
            font-size: 28px;
        }

        /* Sidebar Menu */
        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu-label {
            font-size: 12px;
            font-weight: bold;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 25px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 10px;
        }

        .sidebar-menu li {
            margin-bottom: 8px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding-left: 20px;
        }

        .sidebar-menu a.active {
            background: rgba(255, 255, 255, 0.25);
            border-left: 4px solid white;
            padding-left: 16px;
            font-weight: 600;
        }

        .sidebar-menu i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        /* Dropdown Menu */
        .sidebar-dropdown {
            margin-top: 8px;
        }

        .sidebar-dropdown-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 15px;
            color: rgba(255, 255, 255, 0.9);
            background: none;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .sidebar-dropdown-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .sidebar-dropdown-btn i:last-child {
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .sidebar-dropdown-btn.active i:last-child {
            transform: rotate(180deg);
        }

        .sidebar-dropdown-menu {
            display: none;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 6px;
            margin-top: 5px;
            overflow: hidden;
        }

        .sidebar-dropdown-menu.show {
            display: block;
        }

        .sidebar-dropdown-menu a {
            padding: 10px 15px 10px 35px;
            font-size: 14px;
            border-left: 3px solid transparent;
        }

        .sidebar-dropdown-menu a:hover {
            border-left-color: rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.15);
        }

        /* Main Content */
        .main-content {
            margin-left: 280px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Top Bar */
        .topbar {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .topbar-user i {
            font-size: 18px;
            color: #0d47a1;
        }

        /* Content Area */
        .content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        /* Alert Styling */
        .alert {
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                padding: 15px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .sidebar-brand {
                margin-bottom: 15px;
                font-size: 18px;
            }

            .sidebar-menu {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }

            .sidebar-menu li {
                flex: 0 1 calc(50% - 5px);
                margin-bottom: 0;
            }

            .sidebar-menu a {
                padding: 10px 12px;
                font-size: 13px;
            }

            .sidebar-menu-label {
                display: none;
            }

            .main-content {
                margin-left: 0;
            }

            .topbar {
                flex-direction: column;
                gap: 15px;
                padding: 15px 20px;
            }

            .topbar-left,
            .topbar-right {
                width: 100%;
                justify-content: space-between;
            }

            .content {
                padding: 20px;
            }
        }

        /* Scrollbar Styling */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        /* Logout Button Styling */
        .sidebar-logout-btn {
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            color: rgba(255, 255, 255, 0.9);
            cursor: pointer;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .sidebar-logout-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding-left: 20px;
        }
    </style>

    @stack('styles')
</head>
<body>
    @auth
    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="{{ url('/') }}" class="sidebar-brand">
            <i class="fas fa-motorcycle"></i>
            <span>Sewa Motor</span>
        </a>

        <ul class="sidebar-menu">
            @if(auth()->user()->role === 'admin')
                <!-- Admin Menu -->
                <div class="sidebar-menu-label">Dashboard</div>
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="@if(request()->routeIs('admin.dashboard')) active @endif">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <div class="sidebar-menu-label">Management</div>
                <li>
                    <a href="{{ route('admin.motors') }}" class="@if(request()->routeIs('admin.motors*')) active @endif">
                        <i class="fas fa-motorcycle"></i>
                        <span>Motor</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users') }}" class="@if(request()->routeIs('admin.users*')) active @endif">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.penyewaans') }}" class="@if(request()->routeIs('admin.penyewaans*')) active @endif">
                        <i class="fas fa-list"></i>
                        <span>Penyewaan</span>
                    </a>
                </li>

                <div class="sidebar-menu-label">Transactions</div>
                <li>
                    <a href="{{ route('admin.reports') }}" class="@if(request()->routeIs('admin.reports*')) active @endif">
                        <i class="fas fa-chart-bar"></i>
                        <span>Laporan</span>
                    </a>
                </li>

                <div class="sidebar-menu-label">Data</div>
                <li class="sidebar-dropdown">
                    <button class="sidebar-dropdown-btn" type="button" data-bs-toggle="collapse" data-bs-target="#kelolaDataMenu">
                        <span><i class="fas fa-database"></i> Kelola Data</span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="sidebar-dropdown-menu collapse" id="kelolaDataMenu">
                        <a href="{{ route('crud-users.index') }}">
                            <i class="fas fa-user"></i> Data User
                        </a>
                        <a href="{{ route('crud-motors.index') }}">
                            <i class="fas fa-bike"></i> Data Motor
                        </a>
                        <a href="{{ route('crud-tarif-rentals.index') }}">
                            <i class="fas fa-money-bill"></i> Tarif Rental
                        </a>
                        <a href="{{ route('crud-penyewaans.index') }}">
                            <i class="fas fa-file-contract"></i> Penyewaan
                        </a>
                        <a href="{{ route('crud-transaksis.index') }}">
                            <i class="fas fa-exchange-alt"></i> Transaksi
                        </a>
                    </div>
                </li>

            @elseif(auth()->user()->role === 'pemilik')
                <!-- Owner Menu -->
                <div class="sidebar-menu-label">Dashboard</div>
                <li>
                    <a href="{{ route('owner.dashboard') }}" class="@if(request()->routeIs('owner.dashboard')) active @endif">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <div class="sidebar-menu-label">Management</div>
                <li>
                    <a href="{{ route('owner.motors') }}" class="@if(request()->routeIs('owner.motors*')) active @endif">
                        <i class="fas fa-motorcycle"></i>
                        <span>Motor Saya</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('owner.revenue') }}" class="@if(request()->routeIs('owner.revenue*')) active @endif">
                        <i class="fas fa-wallet"></i>
                        <span>Pendapatan</span>
                    </a>
                </li>

            @else
                <!-- Customer Menu -->
                <div class="sidebar-menu-label">Dashboard</div>
                <li>
                    <a href="{{ route('customer.dashboard') }}" class="@if(request()->routeIs('customer.dashboard')) active @endif">
                        <i class="fas fa-chart-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <div class="sidebar-menu-label">Rental</div>
                <li>
                    <a href="{{ route('customer.motors') }}" class="@if(request()->routeIs('customer.motors*')) active @endif">
                        <i class="fas fa-motorcycle"></i>
                        <span>Sewa Motor</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('customer.bookings.history') }}" class="@if(request()->routeIs('customer.bookings*')) active @endif">
                        <i class="fas fa-history"></i>
                        <span>History</span>
                    </a>
                </li>
            @endif

            <div class="sidebar-menu-label">Account</div>
            <li>
                <form method="POST" action="{{ route('logout') }}" class="d-inline w-100">
                    @csrf
                    <button type="submit" class="sidebar-logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="topbar">
            <div class="topbar-left">
                <h5 class="mb-0">@yield('title', 'Sewa Motor')</h5>
            </div>
            <div class="topbar-right">
                <div class="topbar-user">
                    <i class="fas fa-circle-user"></i>
                    <span>{{ auth()->user()->nama }}</span>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
    @else
    <!-- Guest Layout (No Sidebar) -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-motorcycle"></i> Sewa Motor
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @yield('content')
        </div>
    </main>
    @endauth

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        // Dropdown menu functionality
        document.querySelectorAll('.sidebar-dropdown-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.classList.toggle('active');
            });
        });

        // Set active menu based on current route
        document.querySelectorAll('.sidebar-menu a').forEach(link => {
            if (link.href === window.location.href) {
                link.classList.add('active');
            }
        });
    </script>

    @stack('scripts')
</body>
</html>