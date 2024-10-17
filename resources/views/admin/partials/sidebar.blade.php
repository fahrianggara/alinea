<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        {{-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8"> --}}
        <span class="brand-text font-weight-light">ALINEA</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            {{-- <div class="image">
                <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div> --}}
            <div class="info">
                <a href="#" class="d-block">
                    @auth
                        {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                    @endauth
                </a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/category" class="nav-link">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>
                            Kategori Buku
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/berita" class="nav-link">
                        <i class="nav-icon fas fa-newspaper"></i>
                        <p>
                            Buku
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/profile" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/penulis" class="nav-link">
                        <i class="nav-icon fas fa-pen"></i>
                        <p>
                            Penulis
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/pengguna" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Pengguna
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/langganan" class="nav-link">
                        <i class="nav-icon fas fa-credit-card"></i>
                        <p>
                            Langganan
                        </p>
                    </a>
                </li>
            </ul>
        </nav>

    </div>
</aside>
