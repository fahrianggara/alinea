@extends('alinea.index')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <!-- Profile Header -->
                    <div class="card-header text-center text-white" style="background-color: #0e0e0e;">
                        <img src="{{ asset('storage/' . $invoice->user->image) }}" alt="User Profile"
                            class="rounded-circle mb-2" style="width: 100px; height: 100px; object-fit: cover;">
                        <h5>{{ $fullname }}</h5>
                        <small>{{ $invoice->user->email }}</small>
                    </div>
                    <!-- Invoice Body -->
                    <div class="card-body">
                        <h6 class="card-title text-muted">Invoice Detail</h6>
                        <p class="mb-1"><strong>Invoice:</strong> #{{ $invoice->no_invoice }}</p>
                        <p class="mb-1"><strong>Date:</strong> {{ $invoice->created_at->format('d M Y') }}</p>
                        <p class="mb-1"><strong>Total Amount:</strong> Rp. {{ number_format($invoice->total_amount, 2) }}</p>
                        <p class="mb-3"><strong>Status:</strong>
                            <span class="badge {{ $invoice->status == 'clear' ? 'badge-success' : 'badge-warning' }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </p>
                        <div class="alert alert-success">
                            Transaction success
                        </div>
                    </div>
                    <!-- Footer Info -->
                    <div class="card-footer text-center">
                        <a href="{{ route('alinea') }}">
                            <button class="btn btn-outline-primary">
                                Back to home
                            </button>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-8">
                <div class="row">
                    @php $no = 1; @endphp
                    @foreach ($invoice->borrowings as $borrowing)
                        <div class="col-md-4 mb-4 px-1">
                            <div class="card shadow-sm position-relative">
                                <!-- Full Image -->
                                <img src="{{ asset('storage/' . $borrowing->book->cover) }}" class="card-img"
                                    alt="Book Cover" style="height: 300px; object-fit: cover;">

                                <!-- Overlay Text -->
                                <div class="card-img-overlay d-flex flex-column justify-content-end text-white"
                                    style="background: rgba(0, 0, 0, 0.5);">
                                    <h6 class="card-title mb-1">{{ $borrowing->book->title }}</h6>
                                    <small>Author: {{ $borrowing->book->author }}</small>
                                    <small>Published Date: {{ $borrowing->book->published_date }}</small>
                                    <small>{{ $borrowing->status->name }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
