@extends('admin.index')
@section('title', $title)
@section('content')
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-6 ml-auto">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">invoice</a></li>
                        <li class="breadcrumb-item"><a href="#">borrowings</a></li>
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
                    <span class="title-top-table">Book invoice</span>
                    <form class="ml-auto" action="{{ route('invoices.destroyAll') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all invoices?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete All</button>
                    </form>

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Id</th>
                                    <th>Invoice</th>
                                    <th>Borrower</th>
                                    <th>Total Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>
                                        <center>Actions</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($invoices as $invoice)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $invoice->id }}</td>
                                        <td>{{ $invoice->no_invoice }}</td>
                                        <td>{{ $invoice->user->first_name }} {{ $invoice->user->last_name }}</td>
                                        <td>{{ 'Rp' . number_format($invoice->total_amount, 0, ',', '.') }}</td>
                                        <td>{{ $invoice->created_at }}</td>
                                        <td>{{ $invoice->status }}</td>

                                        <td>
                                            <div class="dropdown d-flex align-items-center justify-content-center">

                                                <button class="btn btn-link text-dark text-center" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i> <!-- Ikon tiga titik -->
                                                </button>

                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li class="dropdown-item">
                                                        <a class="btn d-flex align-items-center px-0" style=""
                                                            href="{{ route('invoices.show', $invoice->no_invoice) }}">
                                                            <i class="fas fa-eye text-center mr-2"
                                                                style="widht: 18px; font-size: 16px;"></i>
                                                            <span>Detail Invoice</span>
                                                        </a>
                                                    </li>
                                                    <li class="dropdown-item">
                                                        <a class="btn d-flex align-items-center px-0"
                                                            href="{{ route('invoices.download', $invoice->id) }}">
                                                            <i class="fas fa-download text-center mr-2"
                                                                style="width: 18px; font-size: 16px;"></i>
                                                            <span>Download Invoice</span>
                                                        </a>
                                                    </li>                                                    
                                                    <li class="dropdown-item">
                                                        <form action="{{ route('invoices.destroy', $invoice->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn d-flex align-items-center px-0">
                                                                <i class="fas fa-trash text-center mr-2"
                                                                    style="width: 18px; font-size: 16px;"></i>
                                                                <span>Delete invoice</span>
                                                            </button>
                                                        </form>
                                                    </li>
                                            </div>

                                            <div class="modal fade" id="Updateinvoice{{ $invoice->id }}">
                                                <div class="modal-dialog modal-md">
                                                    <div class="modal-content">

                                                        <!-- Modal Header -->
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">invoice Book</h4>
                                                            <button type="button" class="close"
                                                                data-dismiss="modal">&times;</button>
                                                        </div>

                                                        <!-- Modal body -->
                                                        <div class="modal-body">
                                                            <form action="{{ route('categories.store') }}" method="post">
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label for="name"
                                                                        class="form-label small">invoice</label>
                                                                    <input type="text" class="form-control"
                                                                        name="name" id="name"
                                                                        value="{{ $invoice->name }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="description"
                                                                        class="form-label small">Description</label>
                                                                    <textarea name="description" id="add-editor" rows="10" class="form-control">
                                                                        {{ $invoice->description }}
                                                                    </textarea>
                                                                </div>
                                                        </div>

                                                        <!-- Modal footer -->
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success">Add</button>
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
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
@endsection
