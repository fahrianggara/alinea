@extends('admin.index')

@section('content')
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6 ml-auto">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Categories</a></li>
                        <li class="breadcrumb-item active">Dashboard Alinea</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="card">
                        <div class="card-header text-center" style="background-color: white;">
                            <img src="{{ asset('storage/' . $user->image) }}" alt="User Profile" class="rounded-circle mb-2"
                                style="width: 100px; height: 100px; object-fit: cover;">
                            <h5>{{ $fullname }}</h5>
                            <small>{{ $user->email }}</small>
                        </div>
                    </div>
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 font-weight-bold">Full Name</div>
                                <div class="col-md-8">{{ $fullname }}</div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4 font-weight-bold">Email</div>
                                <div class="col-md-8">{{ $user->email }}</div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4 font-weight-bold">Role</div>
                                <div class="col-md-8">{{ ucwords(str_replace('_', ' ', $user->admins->role)) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mb-4">Reset Password</h5>
                            <form action="{{ route('users.resetPassword', $admin->id) }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="oldPassword">Old Password</label>
                                    <input type="password" name="oldPassword" id="oldPassword"
                                        placeholder="Enter old password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="newPassword">New Password</label>
                                    <input type="password" name="newPassword" id="newPassword"
                                        placeholder="Enter new password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="confirmPassword">Confirm New Password</label>
                                    <input type="password" name="confirmPassword" id="confirmPassword"
                                        placeholder="Confirm new password" class="form-control" required>
                                </div>

                                <button type="submit" class="btn btn-primary mt-2">Reset Password</button>
                                <button class="btn btn-success ml-auto mt-2" data-toggle="modal" data-target="#updateUser">
                                   Update profile
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Add User Modal -->
    <div class="modal fade" id="updateUser">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit User</h4>
                    <button type="button" class="close"
                        data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('users.update', $user->id) }}"
                        method="post">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="first_name" class="form-label small">First Name</label>
                            <input type="text" class="form-control"
                                name="first_name" id="first_name"
                                value="{{ $user->first_name }}">
                        </div>
                        <div class="form-group">
                            <label for="last_name" class="form-label small">Last Name</label>
                            <input type="text" class="form-control"
                                name="last_name" id="last_name"
                                value="{{ $user->last_name }}">
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label small">Email</label>
                            <input type="email" class="form-control"
                                name="email" id="email"
                                value="{{ $user->email }}">
                        </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">Close</button>
                </div>
                    </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
            });
        @elseif (session('error'))

            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
            });
        @endif
    </script>
@endsection
