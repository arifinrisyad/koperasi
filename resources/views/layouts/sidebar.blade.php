<aside class="main-sidebar sidebar-light-primary elevation-1">
    <!-- Sidebar -->
    <div class="#">
        <!-- Sidebar user (optional) -->
        <div class="mt-3 pb-3 mb-1">
            <div></div><br>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
   
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pembelian.create') }}" class="nav-link {{ request()->is('pembelian/create') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cart-plus"></i>
                        <p>Pembelian</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pembelian.index') }}" class="nav-link {{ request()->is('pembelian') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-list-alt"></i>
                        <p>Daftar Pembelian</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('barang-dijual.index') }}" class="nav-link {{ request()->is('barang-dijual') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-store"></i>
                        <p>Barang yang Dijual</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('penjualan.index') }}" class="nav-link {{ request()->is('penjualan') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-basket"></i>
                        <p>Penjualan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->is('laporan') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Laporan</p>
                    </a>
                </li>

                @if(auth()->user()->role === 'admin')
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ request()->is('users*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Manajemen Pengguna</p>
                    </a>
                </li>
                @endif
              
            </ul>
        </nav>
    </div>

    <!-- Modal for Enlarged Logo Display -->
    <div class="modal" id="logoModal">
        <span class="close" id="closeModal">&times;</span>
        <img class="modal-content" id="modalImg" src="{{ asset('images/cp.png') }}" alt="Logo Besar">
    </div>

    <style>
        /* Sidebar Styling */
        .brand-link {
            display: flex;
            align-items: center;
            padding: 20px;
            background-color: #1c2b36;
            transition: background-color 0.3s;
        }

        .brand-link:hover {
            background-color: #283845;
        }

        .nav-link {
            padding: 15px 20px;
            color: #cfd8dc;
            font-size: 15px;
            font-weight: 500;
            transition: background-color 0.3s, color 0.3s;
        }

        .nav-link:hover {
            background-color: #34495e;
            color: #ffffff;
        }

        .nav-link.active {
            background-color: #ff8a00;
            color: #ffffff;
        }

        /* Logo Modal Styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.9);
        }

        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        .close {
            position: absolute;
            right: 35px;
            top: 15px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }
    </style>
</aside>
