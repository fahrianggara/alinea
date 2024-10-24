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
                    <span class="small nav-link">Menu</span>
                </li>
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <span class="small nav-link">Books</span>
                </li>
                <li class="nav-item">
                    <a href="{{ route('categories') }}" class="nav-link">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>
                            Category
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('books') }}" class="nav-link">
                        <i class="nav-icon fas fa-newspaper"></i>
                        <p>
                            Book
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <span class="small nav-link">Main</span>
                </li>

                <li class="nav-item">
                    <a href="{{ route('borrowings') }}" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Borrowing
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('invoices') }}" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Invoice
                        </p>
                    </a>
                </li>

                @if ($admin->role == 'super_admin')
                    <li class="nav-item">
                        <span class="small nav-link">Superadmmin Manage</span>
                    </li>

                    <li class="nav-item">
    <a href="{{ route('users.index') }}" class="nav-link">
        <i class="nav-icon fas fa-users"></i>
        <p>
            Users
        </p>
    </a>
</li>

                    <li class="nav-item">
                        <a href="/penulis" class="nav-link">
                            <i class="nav-icon fas fa-pen"></i>
                            <p>
                                Workers
                            </p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>

    </div>
</aside>
