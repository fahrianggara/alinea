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
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Alinea</p>
                    </a>
                </li>

                <li class="nav-item menu-open">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-book-reader"></i>
                        <p>
                            Borrowings
                            <i class="fas fa-angle-left right"></i>
                            {{-- <span class="badge badge-info right">6</span> --}}
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <hr>
                            
                        <li class="nav-item">
                            <a href="{{ route('categories') }}" class="nav-link text-sm">
                                <i class="nav-icon fas fa-tags fa-sm"></i>
                                <p>Returns Page</p>
                            </a>
                        </li>

                        <hr>
                    </ul>
                </li>


                <li class="nav-item menu-open">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Books
                            <i class="fas fa-angle-left right"></i>
                            {{-- <span class="badge badge-info right">6</span> --}}
                        </p>
                    </a>
                    <ul class="nav nav-treeview mt-2">
                        <hr>
                        <li class="nav-item">
                            <a href="{{ route('categories') }}" class="nav-link text-sm">
                                <i class="nav-icon fas fa-tags fa-sm"></i>
                                <p>Book Category</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('books') }}" class="nav-link text-sm">
                                <i class="nav-icon fas fa-book-open fa-sm"></i>
                                <p>Book</p>
                            </a>
                        </li>
                        <hr>
                    </ul>
                </li>

                {{-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-book"></i>
                        <p>
                            Report
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-info right">6</span> 
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-header">
                            <span>on proggress</span>
                        </li>
                    </ul>
                </li> --}}

                <li class="nav-item">
                    <a href="" class="nav-link">
                        <i class="nav-icon fas fa-user-circle"></i>
                        <p>Profile</p>
                    </a>
                </li>

                @if ($admin->role == 'super_admin')
                    <li class="nav-item menu-open">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p>
                                Super Admin
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview mt-2">
                            <li class="nav-header">
                                <span>Borrowing Testing</span>
                            </li>
                            <li class="nav-item active">
                                <a href="{{ route('borrowings') }}" class="nav-link text-sm">
                                    <i class="nav-icon fas fa-book-reader fa-sm"></i>
                                    <p>Borrowing</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('invoices') }}" class="nav-link text-sm">
                                    <i class="nav-icon fas fa-file-invoice fa-sm"></i>
                                    <p>Invoice</p>
                                </a>
                            </li>
                            <li class="nav-header">
                                <span>Manage & Testing</span>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}" class="nav-link text-sm">
                                    <i class="nav-icon fas fa-users-cog fa-sm"></i>
                                    <p>Users</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/penulis" class="nav-link text-sm">
                                    <i class="nav-icon fas fa-user-edit fa-sm"></i>
                                    <p>Admins</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('testing') }}" class="nav-link text-sm">
                                    <i class="nav-icon fas fa-vial fa-sm"></i>
                                    <p>Testing</p>
                                </a>
                            </li>
                            <li class="nav-header">
                                <span>Api Testing</span>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('testing') }}" class="nav-link text-sm">
                                    <i class="nav-icon fas fa-vial fa-sm"></i>
                                    <p>Api Mobile</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
            </ul>


        </nav>

    </div>
</aside>
