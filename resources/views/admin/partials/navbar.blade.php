<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <div class="container">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Navbar Search -->
            {{-- <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li> --}}

            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link text-danger p-0" role="button"
                        style="text-decoration: none;">
                        <i class="fas fa-power-off"></i>
                        <span class="ml-1">Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>
<!-- /.navbar -->
