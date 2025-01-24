<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <h3 class="font-weight-bold">APLIKASI MANAJEMEN KOPERASI</h3>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Messages Dropdown Menu -->
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown" >
            <a class="dropdown-toggle" style="color: black;" data-toggle="dropdown" href="#">
                <i class="fas fa-user mr-2"></i> &nbsp;<span>{{ auth()->user()->name }}</span> &nbsp;<i
                    class="icon-submenu lnr lnr-chevron-down"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">Profil</span>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" data-toggle="modal" data-target="#lihatprofile">
                    <i class="fas fa-user mr-2"></i> Lihat Profil
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>

<style>
    /* Navbar Styling */
    .navbar {
        padding: 15px 25px;
        background-color: white; /* Dark grey background */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow effect */
        border-radius: 8px 8px 0 0; /* Rounded top corners */
    }

    .navbar .nav-link {
        font-size: 1rem;
        color: rgb(255, 255, 255);
        padding: 10px 15px;
        transition: all 0.3s ease;
    }

    .navbar .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1); /* Hover effect */
        border-radius: 5px; /* Rounded hover effect */
    }

    .navbar .nav-link i {
        margin-right: 8px; /* Space between icon and text */
    }

    .navbar .btn-link {
        padding: 0;
        color: #fff;
        font-size: 1rem;
        transition: color 0.3s ease;
    }

    .navbar .btn-link:hover {
        color: #ff8a00; /* Change color on hover */
    }

    .navbar .navbar-nav.ml-auto {
        display: flex;
        align-items: center;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .navbar {
            padding: 10px 15px; /* Reduced padding for mobile */
        }

        .navbar .nav-link {
            padding: 8px 12px; /* Adjusted padding for smaller screens */
        }
    }
</style>
<div class="modal fade" id="lihatprofile" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="exampleModalLabel"><i class="nav-icon fas fa-user my-1 btn-sm-1"></i>
                &nbsp;Profil Pengguna</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-3">
                    <h5><label for="nama">Nama </label></h5>
                </div>
                <div class="col-9">
                    <h5><label for="nama"> : {{ auth()->user()->name }}</label></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <h5><label for="nama">Email </label></h5>
                </div>
                <div class="col-9">
                    <h5><label for="nama"> : {{ auth()->user()->email }}</label></h5>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <h5><label for="nama">Level User </label></h5>
                </div>
                <div class="col-9">
                    <h5><label for="nama"> : {{ auth()->user()->role }}</label></h5>
                </div>
            </div>
        </div>
    </div>
</div>
</div>