<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Koperasi') }}</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
        }

        body {
            background-color: #f8f9fc;
        }

        /* Sidebar Styles */
        #sidebar-wrapper {
            min-height: 100vh;
            width: 250px;
            margin-left: -250px;
            background: var(--primary-color);
            transition: margin .25s ease-out;
            position: fixed;
            top: 0;
            bottom: 0;
            overflow-y: auto;
        }

        #sidebar-wrapper .sidebar-heading {
            padding: 0.875rem 1.25rem;
            font-size: 1.2rem;
            color: white;
            background: rgba(0,0,0,0.1);
        }

        .list-group-item {
            background: transparent;
            color: rgba(255,255,255,.8);
            border: none;
            padding: 1rem 1.5rem;
        }

        .list-group-item:hover {
            background: rgba(255,255,255,.1);
            color: white;
        }

        .list-group-item.active {
            background: rgba(255,255,255,.2);
            color: white;
            border: none;
        }

        .list-group-item i {
            margin-right: 0.5rem;
            width: 20px;
            text-align: center;
        }

        #content-wrapper {
            width: 100%;
            margin-left: 250px;
        }

        @media (max-width: 768px) {
            #content-wrapper {
                margin-left: 0;
            }
        }

        /* Updated Navbar Styles */
        #navbar {
            background: white;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 0.8rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .navbar-nav {
            align-items: center;
        }

        .nav-item {
            position: relative;
            margin-left: 10px;
        }

        .nav-link {
            color: #2c3e50 !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
            background: rgba(78, 115, 223, 0.1);
        }

        .nav-link i {
            margin-right: 8px;
            font-size: 1.1rem;
        }

        .user-profile-section {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 5px 15px;
            border-radius: 30px;
            background: #f8f9fc;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .user-profile-section:hover {
            background: #eaecf4;
        }

        .user-info {
            text-align: right;
            line-height: 1.2;
        }

        .user-name {
            color: #2c3e50;
            font-weight: 600;
            font-size: 0.95rem;
            margin: 0;
        }

        .user-role {
            color: #7f8c8d;
            font-size: 0.8rem;
            margin: 0;
        }

        .img-profile {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .img-profile:hover {
            transform: scale(1.05);
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.2);
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            padding: 0.8rem 0;
            min-width: 200px;
            margin-top: 10px;
        }

        .dropdown-item {
            padding: 0.7rem 1.5rem;
            color: #2c3e50;
            font-weight: 500;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .dropdown-item i {
            margin-right: 12px;
            font-size: 1rem;
            width: 20px;
            text-align: center;
            color: #7f8c8d;
        }

        .dropdown-item:hover {
            background: #f8f9fc;
            color: var(--primary-color);
        }

        .dropdown-item:hover i {
            color: var(--primary-color);
        }

        .dropdown-divider {
            margin: 0.5rem 0;
            border-top: 1px solid #eaecf4;
        }

        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        /* Menu Toggle Button */
        #menu-toggle {
            color: var(--primary-color);
            background: #f8f9fc;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        #menu-toggle:hover {
            background: #eaecf4;
            transform: scale(1.05);
        }

        #menu-toggle i {
            font-size: 1.2rem;
        }

        /* Loading Spinner */
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        /* Card Styles */
        .card {
            box-shadow: 0 .15rem 1.75rem 0 rgba(58,59,69,.15);
            border: none;
            border-radius: 0.35rem;
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }

        /* Table Styles */
        .table th {
            background-color: #f8f9fc;
            border-top: none;
        }

        /* Button Styles */
        .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.9rem;
            border-radius: 0.35rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }

        .btn-info {
            background-color: var(--info-color);
            border-color: var(--info-color);
        }

        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
        }

        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }

        /* Wrapper */
        #wrapper {
            display: flex;
        }

        #wrapper.toggled #sidebar-wrapper {
            margin-left: 0;
        }

        @media (min-width: 768px) {
            #sidebar-wrapper {
                margin-left: 0;
            }

            #content-wrapper {
                min-width: 0;
                width: 100%;
            }

            #wrapper.toggled #sidebar-wrapper {
                margin-left: -250px;
            }
        }

        /* Submenu Styles */
        .list-group-item .collapse .list-group-item {
            padding-left: 2.5rem;
            background: rgba(255,255,255,.05);
            border-left: 3px solid transparent;
        }

        .list-group-item .collapse .list-group-item:hover {
            background: rgba(255,255,255,.1);
            border-left: 3px solid rgba(255,255,255,.5);
        }

        .list-group-item .collapse .list-group-item.active {
            background: rgba(255,255,255,.15);
            border-left: 3px solid white;
        }

        .list-group-item > a {
            text-decoration: none;
            color: rgba(255,255,255,.8);
        }

        .list-group-item > a:hover {
            color: white;
        }

        .fa-chevron-down {
            transition: transform .3s ease;
        }

        [aria-expanded="true"] .fa-chevron-down {
            transform: rotate(180deg);
        }
    </style>

    @stack('styles')
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <div class="sidebar-heading">
               
            </div>
            <div class="list-group list-group-flush">
                <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action {{ request()->is('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>

                <!-- Financial Reports Menu -->
        
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('pembelian.index') }}" class="list-group-item list-group-item-action {{ request()->is('pembelian') ? 'active' : '' }}">
                    <i class="fas fa-cart-plus"></i> Pembelian
                </a>
                @endif
                <a href="{{ route('barang-dijual.index') }}" class="list-group-item list-group-item-action {{ request()->is('barang-dijual*') ? 'active' : '' }}">
                    <i class="fas fa-store"></i> Barang yang Dijual
                </a>
                <a href="{{ route('penjualan.index') }}" class="list-group-item list-group-item-action {{ request()->is('penjualan*') ? 'active' : '' }}">
                    <i class="fas fa-shopping-basket"></i> Penjualan
                </a>
                <a href="{{ route('laporan-penjualan.index') }}" class="list-group-item list-group-item-action {{ request()->is('laporan-penjualan*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> Laporan Penjualan
                </a>
                <a href="{{ route('laporan-pembelian.index') }}" class="list-group-item list-group-item-action {{ request()->is('laporan-pembelian*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> Laporan Pembelian
                </a>
                <a href="{{ route('keuangan.index') }}" class="list-group-item list-group-item-action {{ request()->is('keuangan*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill"></i> Keuangan
                </a>        
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action {{ request()->is('users*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Users
                </a>
                <a href="{{ route('activity-logs.index') }}" class="list-group-item list-group-item-action {{ request()->is('activity-logs*') ? 'active' : '' }}">
                    <i class="fas fa-history"></i> Log Aktivitas
                </a>
                 @endif
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="list-group-item list-group-item-action">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Content Wrapper -->
        <div id="content-wrapper">
            <!-- Updated Navbar -->
            <nav id="navbar" class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <button class="btn" id="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="ms-auto d-flex align-items-center">
                        <ul class="navbar-nav">
                            <!-- Notifications -->
                            {{-- <li class="nav-item dropdown">
                                <a class="nav-link" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-bell"></i>
                                    <span class="notification-badge">3</span>
                                </a>
                                <!-- Notifications Dropdown Menu -->
                                <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in" aria-labelledby="notificationsDropdown">
                                    <h6 class="dropdown-header">
                                        Notifikasi
                                    </h6>
                                    <div class="notifications-container" style="max-height: 300px; overflow-y: auto;">
                                        <a class="dropdown-item d-flex align-items-center" href="#">
                                            <div class="me-3">
                                                <div class="icon-circle bg-primary">
                                                    <i class="fas fa-shopping-cart text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="small text-gray-500">12 Desember 2023</div>
                                                <span>Pembelian baru telah ditambahkan</span>
                                            </div>
                                        </a>
                                        <a class="dropdown-item d-flex align-items-center" href="#">
                                            <div class="me-3">
                                                <div class="icon-circle bg-success">
                                                    <i class="fas fa-cash-register text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="small text-gray-500">11 Desember 2023</div>
                                                <span>Penjualan baru telah selesai</span>
                                            </div>
                                        </a>
                                        <a class="dropdown-item d-flex align-items-center" href="#">
                                            <div class="me-3">
                                                <div class="icon-circle bg-warning">
                                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="small text-gray-500">10 Desember 2023</div>
                                                <span>Stok barang hampir habis</span>
                                            </div>
                                        </a>
                                    </div>
                                    <a class="dropdown-item text-center small text-gray-500" href="{{ route('notifications.index') }}">
                                        Tampilkan Semua Notifikasi
                                    </a>
                                </div>
                            </li> --}}

                            <!-- User Profile Dropdown -->
                            <li class="nav-item dropdown">
                                <div class="user-profile-section" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="user-info d-none d-lg-block">
                                        <p class="user-name">{{ Auth::user()->name }}</p>
                                        <p class="user-role">{{ ucfirst(Auth::user()->role) }}</p>
                                    </div>
                                    <img class="img-profile" 
                                         src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('images/default-profile.png') }}"
                                         alt="Profile">
                                </div>

                                <!-- Dropdown Menu -->
                                <div class="dropdown-menu dropdown-menu-end shadow animated--grow-in">
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user"></i>
                                        Profil Saya
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt"></i>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="container-fluid p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#menu-toggle").click(function(e) {
                e.preventDefault();
                $("#wrapper").toggleClass("toggled");
            });

            // Auto-expand submenu if current page is in that submenu
            if (window.location.href.includes('keuangan')) {
                $('#financialSubmenu').addClass('show');
            }

            // Add active class to current submenu item
            $('.list-group-item').each(function() {
                if ($(this).attr('href') === window.location.href) {
                    $(this).addClass('active');
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
