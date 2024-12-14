@extends('admin.index')
@section('title', $title)
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

            <div class="card table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="title-top-table">Book Category</span>
                    <button class="btn btn-success ml-auto py-1" data-toggle="modal" data-target="#AddCategory">
                        <i class="fa fa-plus mr-1"></i> Add Category
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th>
                                        <center>Actions</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($categories as $category)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>{!! $category->description !!}</td>

                                        <td>
                                            <div class="dropdown d-flex align-items-center justify-content-center">

                                                <button class="btn btn-link text-dark text-center" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i> <!-- Ikon tiga titik -->
                                                </button>

                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                                    <li class="dropdown-item">
                                                        <button type="submit" class="btn d-flex align-items-center px-0"
                                                            data-toggle="modal"
                                                            data-target="#UpdateCategory{{ $category->id }}">
                                                            <i class="fas fa-user-edit text-center mr-2"
                                                                style="width: 18px; font-size: 16px;"></i>
                                                            <span>Update category</span>
                                                        </button>
                                                    </li>

                                                    <li class="dropdown-item">
                                                        <form action="{{ route('categories.destroy', $category->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this book?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn d-flex align-items-center px-0">
                                                                <i class="fas fa-trash text-center mr-2"
                                                                    style="width: 18px; font-size: 16px;"></i>
                                                                <span>Delete Category</span>
                                                            </button>
                                                        </form>
                                                    </li>
                                            </div>

                                            <div class="modal fade" id="UpdateCategory{{ $category->id }}">
                                                <div class="modal-dialog modal-md">
                                                    <div class="modal-content">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Category Book</h4>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>

                                                        <!-- Modal body -->
                                                        <div class="modal-body">
                                                            <form action="{{ route('categories.update', $category->id) }}"
                                                                method="post">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="form-group">
                                                                    <label for="name" class="form-label small">Category</label>
                                                                    <input type="text" class="form-control" name="name" id="name" value="{{ $category->name }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="description" class="form-label small">Description</label>
                                                                    <textarea name="description" id="add-editor" rows="10" class="form-control">
                                                                        {{ $category->description }}
                                                                    </textarea>
                                                                </div>
                                                        </div>

                                                        <!-- Modal footer -->
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success">Update</button>
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </section>

    <div class="modal fade" id="AddCategory">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Category Book</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="{{ route('categories.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="form-label small">Category</label>
                            <input type="text" class="form-control" name="name" id="name">
                        </div>
                        <div class="form-group">
                            <label for="description" class="form-label small">Description</label>
                            <textarea name="description" id="add-editor" rows="10" class="form-control"></textarea>
                        </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </form>

            </div>
        </div>
    </div>
@endsection
