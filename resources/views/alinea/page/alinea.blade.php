@extends('alinea.index')

@section('content')
    <div class="homapage">
        <div class="container">
            <div class="row">
                <div class="col-9 mx-auto text-center">
                    <center><img src="{{ asset('storage/logo/logo.png') }}" alt="" class="navbar-brand"
                            style="width: 140px;"></center>
                    <h1 class="m-0 py-4">Welcome to Alinea</h1>
                </div>
                <div class="col-lg-6">
                    <a href="{{ route('pickups') }}">
                        <div class="card">
                            <div class="card-body">
                                Pickup Book
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-6">
                    <a href="{{ route('returns') }}">
                        <div class="card">
                            <div class="card-body">
                                Return Book
                            </div>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection
