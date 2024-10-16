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

            <div class="card table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="title-top-table">Book Category</span>
                    <button class="btn btn-success ml-auto py-1" data-toggle="modal" data-target="#myModal"><i
                            class="fa fa-plus mr-1"></i> Tambah</button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-md" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th><center>Actions</center></th>
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
                                                
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fas fa-user-edit"></i> Edit Peserta
                                                    </a>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fas fa-info-circle"></i> Detail Peserta
                                                    </a>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fas fa-box-open"></i> Racepack
                                                    </a>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fas fa-ambulance"></i> Kontak Darurat & Medis
                                                    </a>
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

    <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-md">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Category Book</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="{{ route('category.store') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="form-label small">Category</label>
                            <input type="text" class="form-control" name="name" id="name">
                        </div>
                        <div class="form-group">
                            <label for="description" class="form-label small">Description</label>
                            <textarea name="description" id="editor" rows="10" class="form-control"></textarea>
                        </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Tambah</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </form>

            </div>
        </div>
    </div>
@endsection
