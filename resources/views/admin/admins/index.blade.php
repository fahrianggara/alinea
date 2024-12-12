@extends('admin.index')

@section('content')
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6 ml-auto">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">admins</a></li>
                        <li class="breadcrumb-item active">User Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container">
            <div class="card table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="title-top-table">Admin Management</span>
                    <button class="btn btn-success ml-auto py-1" data-toggle="modal" data-target="#AddUser">
                        <i class="fa fa-plus mr-1"></i> Add Admin
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>
                                        <center>Actions</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($admins as $admin)
                                    @if ($admin->admins->role == 'admin')
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $admin->first_name }} {{ $admin->last_name }}</td>
                                            <td>{{ $admin->email }}</td>
                                            <td>{{ ucfirst($admin->admins->role) }}</td>
                                            <td>
                                                <div class="dropdown d-flex align-items-center justify-content-center">
                                                    <button class="btn btn-link text-dark text-center" type="button"
                                                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>

                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <li class="dropdown-item">
                                                            <button type="button"
                                                                class="btn d-flex align-items-center px-0"
                                                                data-toggle="modal"
                                                                data-target="#UpdateUser{{ $admin->id }}">
                                                                <i class="fas fa-user-edit text-center mr-2"
                                                                    style="width: 18px; font-size: 16px;"></i>
                                                                <span>Edit Admin</span>
                                                            </button>
                                                        </li>

                                                        <li class="dropdown-item">
                                                            <form action="{{ route('admins.destroy', $admin->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn d-flex align-items-center px-0">
                                                                    <i class="fas fa-trash text-center mr-2"
                                                                        style="width: 18px; font-size: 16px;"></i>
                                                                    <span>Delete Admin</span>
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>

                                                    <!-- Update User Modal -->
                                                    <div class="modal fade" id="UpdateUser{{ $admin->id }}">
                                                        <div class="modal-dialog modal-md">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Edit Admin</h4>
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

                                                                    <form action="{{ route('admins.update', $admin->id) }}"
                                                                        method="POST">
                                                                        @csrf
                                                                        @method('PUT')

                                                                        <div class="form-group">
                                                                            <label for="first_name"
                                                                                class="form-label small">First Name</label>
                                                                            <input type="text" class="form-control"
                                                                                name="first_name" id="first_name"
                                                                                value="{{ old('first_name', $admin->first_name) }}">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="last_name"
                                                                                class="form-label small">Last Name</label>
                                                                            <input type="text" class="form-control"
                                                                                name="last_name" id="last_name"
                                                                                value="{{ old('last_name', $admin->last_name) }}">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="email"
                                                                                class="form-label small">Email</label>
                                                                            <input type="email" class="form-control"
                                                                                name="email" id="email"
                                                                                value="{{ old('email', $admin->email) }}">
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="role"
                                                                                class="form-label small">Role</label>
                                                                            <select name="role" id="role"
                                                                                class="form-control">
                                                                                <option value="super_admin"
                                                                                    {{ $admin->admins->role === 'super_admin' ? 'selected' : '' }}>
                                                                                    Super Admin
                                                                                </option>
                                                                                <option value="admin"
                                                                                    {{ $admin->admins->role === 'admin' ? 'selected' : '' }}>
                                                                                    Admin
                                                                                </option>
                                                                            </select>
                                                                        </div>

                                                                </div>

                                                                <div class="modal-footer">
                                                                    <button type="submit"
                                                                        class="btn btn-success">Update</button>
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close</button>
                                                                </div>
                                                            </form>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </section>

    <!-- Add User Modal -->
    <div class="modal fade" id="AddUser">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Admin</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
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
                    <form action="{{ route('admins.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="first_name" class="form-label small">First Name</label>
                            <input type="text" class="form-control" name="first_name" id="first_name">
                        </div>
                        <div class="form-group">
                            <label for="last_name" class="form-label small">Last Name</label>
                            <input type="text" class="form-control" name="last_name" id="last_name">
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label small">Email</label>
                            <input type="email" class="form-control" name="email" id="email">
                        </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </form>

            </div>
        </div>
    </div>
@endsection
