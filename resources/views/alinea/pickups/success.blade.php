@extends('alinea.index')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <!-- Header Profil -->
                    <div class="card-header text-center text-white" style="background-color: #0e0e0e;">
                        <img src="{{ asset('storage/' . $invoice->user->image) }}" alt="User Profile"
                            class="rounded-circle mb-2" style="width: 100px; height: 100px; object-fit: cover;">
                        <h5>{{ $fullname }}</h5>
                        <small>{{ $invoice->user->email }}</small>
                    </div>
                    <!-- Body Invoice -->
                    <div class="card-body">
                        <h6 class="card-title text-muted">Invoice Detail</h6>
                        <p class="mb-1"><strong>Invoice:</strong> #{{ $invoice->no_invoice }}</p>
                        <p class="mb-1"><strong>Tanggal:</strong> {{ $invoice->created_at->format('d M Y') }}</p>
                        <p class="mb-3"><strong>Status:</strong>
                            <span class="badge {{ $invoice->status == 'clear' ? 'badge-success' : 'badge-warning' }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </p>
                        <div class="alert alert-success">
                            Book borrowed successfully!
                        </div>
                    </div>
                    <!-- Footer Info -->
                    <div class="card-footer text-center">
                        {{-- <small>Dipinjam pada: {{ $invoice->created_at->format('d M Y H:i') }}</small> --}}
                        <a href="{{ route('alinea') }}">
                            <button class="btn btn-outline-primary">
                                Back to home
                            </button>
                        </a>
                    </div>
                </div>
            </div>


            <!-- Kolom Kanan -->
            <div class="col-lg-8">
                <div class="row">
                    @php $no = 1; @endphp
                    @foreach ($invoice->borrowings as $borrowing)
                        <div class="col-md-4 mb-4 px-1">
                            <div class="card shadow-sm position-relative">
                                <!-- Gambar Full -->
                                <img src="{{ asset('storage/' . $borrowing->book->cover) }}" class="card-img"
                                    alt="Book Cover" style="height: 300px; object-fit: cover;">

                                <!-- Overlay Teks -->
                                <div class="card-img-overlay d-flex flex-column justify-content-end text-white"
                                    style="background: rgba(0, 0, 0, 0.5);">
                                    <h6 class="card-title mb-1">{{ $borrowing->book->title }}</h6>
                                    <small>Penulis: {{ $borrowing->book->author }}</small>
                                    <small>Tanggal Terbit: {{ $borrowing->book->published_date }}</small>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <script>
        window.history.pushState(null, "", window.location.href);
        window.addEventListener('popstate', function() {
            window.location.reload();
        });
    </script>
@endsection
