@extends('admin.index')

@section('content')
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6 ml-auto">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Users</a></li>
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
                    <h4 class="modal-title">Add User</h4>
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
