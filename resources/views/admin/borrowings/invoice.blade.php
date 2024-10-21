@extends('admin.index')

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
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <div class="bg-dark" style="width: 50px; height: 50px;">
                            
                        </div>
                        <div class="ml-3">
                            {{ $borrowing->no_invoice }}
                        </div>

                        
                    </div>
                    <div class="card-body">

                    </div>
                    <div class="card-footer">

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection