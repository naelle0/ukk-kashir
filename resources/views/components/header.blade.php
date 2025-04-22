@auth
<style>
    .navbar-bg {
        background-color: #2f2f2f;
        height: 56px; /* tinggi navbar */
    }

    .main-navbar {
        background-color: #2f2f2f !important;
        padding-top: 4px !important;
        padding-bottom: 4px !important;
        min-height: 56px !important;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 999;
    }

    .navbar-nav .nav-link,
    .navbar-nav .dropdown-toggle {
        color: #ffffff !important;
    }

    .navbar-nav .nav-link:hover,
    .navbar-nav .dropdown-toggle:hover {
        color: #cccccc !important;
    }

    .dropdown-menu {
        background-color: #3f3f3f !important;
        border: none;
    }

    .dropdown-title {
        color: #ffffff;
        background-color: #4f4f4f;
        padding: 10px;
        font-weight: bold;
    }

    .dropdown-item {
        color: #ffffff;
    }

    .dropdown-item:hover {
        background-color: #555555;
        color: #ffffff;
    }

    .dropdown-item.text-danger:hover {
        background-color: #7b1e1e;
    }

    /* Konten utama agar tidak ketutup navbar */
    .main-content {
        margin-top: 70px;
    }
</style>

<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <!-- Sidebar toggle -->
    <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg">
        <i class="fas fa-bars"></i>
    </a>

    <!-- Right Side -->
    <ul class="navbar-nav ml-auto">
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{ asset('img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
                <div class="d-sm-none d-lg-inline-block">
                    Hai
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item has-icon text-danger"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>
    </ul>
</nav>
@endauth
