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
                <div class="col-lg-3">
                    <div class="card p-3" style="background-color: white !important">
                        <!-- Status Data Cart -->
                        @if ($carts->count() > 0)
                            <div class="alert alert-success text-center">
                                {{ $status }}
                            </div>
                        @endif
                        <div class="alert alert-info text-center">
                            {{ $carts->count() }} Items in Cart
                        </div>
                        <!-- Section Keranjang dan Aksi -->
                        <div class="d-flex align-items-center justify-content-around bg-warning py-4 rounded shadow-sm">
                            <!-- Icon Keranjang -->
                            <span>
                                <i class="fas fa-shopping-cart" style="font-size: 40px"></i>
                            </span>
                            <span>
                                @if ($carts->count() > 0)
                                    <!-- Tombol Delete All -->
                                    <form action="{{ route('testing.delCart') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            Delete All
                                        </button>
                                    </form>
                                @else
                                    <!-- Tombol Add Cart Testing -->
                                    <form action="{{ route('testing.addCart') }}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            Add Cart Testing
                                        </button>
                                    </form>
                                @endif
                            </span>
                        </div>
                        <!-- Tombol See Data -->
                        <center>
                            <button class="btn mt-2" data-toggle="collapse" data-target="#demo">
                                <i class="fas fa-chevron-down"></i>
                                <span>See Data</span>
                            </button>
                        </center>
                        <!-- Data Cart -->
                        <div id="demo" class="collapse mt-3">
                            @if ($carts->count() > 0)
                                @foreach ($books as $book)
                                    <div class="card mb-2 shadow-sm">
                                        <div class="card-body d-flex justify-content-between align-items-center p-2">
                                            <!-- Gambar Buku -->
                                            <img src="{{ asset('storage/' . $book->cover) }}" alt="Book Cover"
                                                class="img-fluid rounded" style="max-width: 50px;">
                                            <!-- Detail Buku -->
                                            <div class="w-100 px-2">
                                                <div class="font-weight-bold small">{{ $book->title }}</div>
                                                <div>
                                                    <span class="badge badge-info">{{ $book->category->name }}</span>
                                                    <span class="badge badge-warning">Stock: {{ $book->stock }}</span>
                                                </div>
                                            </div>
                                            <!-- Harga & Jumlah -->
                                            <div class="text-right">
                                                <div class="font-weight-bold text-success">{{ $book->price }} IDR</div>
                                                {{-- <div>Qty: {{ $cart->qty }}</div> --}}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <!-- Jika Cart Kosong -->
                                <div class="alert alert-warning text-center">
                                    <h5 class="m-0">Data is Empty. Please Click Add Cart Testing</h5>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @if ($carts->count() > 0)
                    <div class="col-lg-3">
                        <div class="card p-3" style="background-color: white !important">
                            @if ($carts->count() > 0)
                                <div class="alert alert-success text-center">
                                    {{ $status }}
                                </div>
                            @endif
                            <div
                                class="d-flex align-items-center justify-content-around bg-warning py-4 rounded shadow-sm mb-3">
                                <!-- Icon Keranjang -->
                                <span>
                                    <i class="fas fa-book" style="font-size: 40px"></i>
                                    <i class="fas fa-plus" style="font-size: 10px"></i>
                                </span>
                                <span>
                                    <form action="{{ route('testing.borrow') }}" method="post">
                                        @csrf
                                        @foreach ($books as $book)
                                            <input type="hidden" name="book_ids[]" value="{{ $book->id }}">
                                        @endforeach
                                        <button type="submit" class="btn btn-primary">Borrow</button>
                                    </form>
                                </span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card p-3">
                        @foreach ($borrowings as $borrowing)
                            <div class="card mb-2 shadow-sm">
                                <div class="card-body d-flex justify-content-between align-items-center p-2">
                                    <!-- Gambar Buku -->
                                    <img src="{{ asset('storage/' . $borrowing->book->cover) }}" alt="Book Cover"
                                        class="img-fluid rounded" style="max-width: 50px;">
                                    <!-- Detail Buku -->
                                    <div class="w-100 px-2">
                                        <div class="font-weight-bold small">{{ $borrowing->book->title }}</div>
                                        <div>
                                            <span class="badge badge-info">{{ $borrowing->book->category->name }}</span>
                                            <span class="badge badge-warning">Stock: {{ $borrowing->book->stock }}</span>
                                        </div>
                                        <!-- Tanggal Peminjaman & Pengembalian -->
                                        <div class="mt-2">
                                            <p class="small mb-1">Borrowed by: {{ $borrowing->user->firstname }}</p>
                                            <p class="small mb-1">Borrow Date: {{ $borrowing->borrow_date }}</p>
                                            <p class="small mb-1">Return Date: {{ $borrowing->return_date }}</p>
                                        </div>
                                    </div>
                                    <!-- Status & Harga Buku -->
                                    <div class="text-right">
                                        <div class="font-weight-bold text-success">{{ $borrowing->book->price }} IDR</div>
                                        <div class="small">
                                            Status:
                                            @if ($borrowing->status == 'returned')
                                                <span class="badge badge-success">Returned</span>
                                            @else
                                                <span class="badge badge-danger">Not Returned</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        @foreach ($invoices as $invoice)
                            <h2>Invoice #{{ $invoice->no_invoice }} - Total: Rp
                                {{ $invoice->total_amount, 0, ',', '.' }}</h2>
                            <p>Status: {{ ucfirst($invoice->status) }}</p>
                            <h3>Buku yang Dipinjam:</h3>
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
    </section>
@endsection