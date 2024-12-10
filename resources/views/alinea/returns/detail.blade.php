@extends('alinea.index')

@section('content')
    <div class="container" style="margin-top: 100px">
        <div class="col-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Invoice #{{ $invoice->no_invoice }}</h5>
                    <div>
                        <form action="{{ route('returns.success', $invoice->id)}}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Selesai</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 text-center">
                            <img src="data:image/png;base64,{{ $invoice->qr_code }}" alt="QR Code" width="100">
                            <p class="mt-2"><strong>Scanned By:</strong> {{ $fullnameAdmin }}</p>
                        </div>
                        <div class="col-md-8">
                            <p><strong>Borrower:</strong> {{ $fullname }}</p>
                            <p><strong>Borrowed At:</strong>
                                {{ \Carbon\Carbon::parse($invoice->borrow_date)->format('Y/m/d') }}</p>
                            <p><strong>Return By:</strong>
                                {{ \Carbon\Carbon::parse($invoice->return_date)->format('Y/m/d') }}</p>
                            <p><strong>Status:</strong> {{ $invoice->status }}</p>
                            <p><strong>Total Fine:</strong> Rp. {{ number_format($invoice->total_amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header">
                    <h6>Borrowed Books</h6>
                </div>
                <div class="card-body">
                    @foreach ($invoice->borrowings as $borrowing)
                        <div class="row align-items-center mb-3 border-bottom pb-3">
                            <div class="col-md-2 text-center">
                                <img src="{{ asset('storage/' . $borrowing->book->cover) }}" alt="Book Cover"
                                    class="img-fluid" style="height: 100px; object-fit: cover;">
                            </div>
                            <div class="col-md-4">
                                <h6>{{ $borrowing->book->title }}</h6>
                                <p><strong>Author:</strong> {{ $borrowing->book->author }}</p>
                                <p><strong>ISBN:</strong> {{ $borrowing->book->isbn }}</p>
                            </div>
                            <div class="col-md-3">
                                <p><strong>Status:</strong> {{ $borrowing->status->name }}</p>
                                <form action="{{ route('returns.updateStatus', $borrowing->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <div class="form-group">
                                        <select name="status_id" class="form-control">
                                            <option value="5" {{ $borrowing->status_id == 5 ? 'selected' : '' }}>Book Missing</option>
                                            <option value="6" {{ $borrowing->status_id == 6 ? 'selected' : '' }}>Book Damaged</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Change Status</button>
                                </form>
                            </div>
                            <div class="col-md-3 text-right">
                                <p><strong>Fine:</strong> Rp. 
                                    @if($borrowing->status_id == 4)
                                        20,000
                                    @elseif($borrowing->status_id == 5 || $borrowing->status_id == 6)
                                        100,000
                                    @else
                                        0
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
