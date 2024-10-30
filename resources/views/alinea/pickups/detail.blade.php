@extends('alinea.index')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    @foreach ($invoices as $invoice)
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <img src="data:image/png;base64,{{ $invoice->qr_code }}" class="img-fluid" style="width: 30px;">
                            <span>{{ $invoice->no_invoice }}</span>
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="">
                            Nim : 10220048 <br>
                            Nama : {{ $invoice->user->first_name }} {{ $invoice->user->last_name }}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        List Buku yang di pinjam
                    </div>
                    <div class="card-body">
                        @foreach ($invoices as $invoice)
                            <ul>
                                @foreach ($invoice->borrowings as $borrowing)
                                    <li>{{ $borrowing->book->title }} (Borrowed on:
                                        {{ $borrowing->borrow_date }}, Return by:
                                        {{ $borrowing->return_date }})</li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
