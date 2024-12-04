<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->no_invoice }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        .book-image {
            width: 100px;
            height: 70px;
            object-fit: cover;
        }

        .invoice-header {
            color: #4169E1;
        }

        .status-paid {
            color: #28a745;
        }
        h6{
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container my-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('storage/logo/logo.png') }}" alt="Book Icon" width="60" height="50"
                            class="me-2">
                        <h1 class="invoice-header h3 mb-0">Invoice</h1>
                        <h6 class="mt-2 mb-0 mx-3"> # {{ $invoice->no_invoice }}</h6>
                    </div>
                    <button class="btn btn-outline-primary">
                        <i class="bi bi-download"></i> Download
                    </button>
                </div>

                <hr>

                <!-- Invoice Details -->
                <div class="row">
                    <div class="col-3 m-auto">
                        <center><img src="data:image/png;base64,{{ $invoice->qr_code }}" alt="QR Code"
                                class="img-fluid" width="140"></center>
                    </div>
                    <div class="col-md-3 my-auto">
                        <h6>Invoice For</h6>
                        <p>{{ $fullname }}</p>
                    </div>
                    <div class="col-md-3 my-auto">
                        <h6>Booked Date</h6>
                        <p>Borrow Date on {{ \Carbon\Carbon::parse($borrowing->borrowing_date)->format('Y/m/d') }}</p>
                    </div>
                    <div class="col-md-3 my-auto">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Due Date</h6>
                                <p>Return Date on {{ \Carbon\Carbon::parse($borrowing->return_date)->format('Y/m/d') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>
                <br>
                <br>

                <!-- Pay For -->

                <!-- Items Table -->
                <div class="table-responsive mb-4">
                    <table class="table">
                        <thead class="bg-light">
                            <tr>
                                <th>No</th>
                                <th>Items</th>
                                <th>Qty</th>
                                <th>Author</th>
                                <th>ISBN</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no=1; @endphp
                            @foreach ($invoice->borrowings as $borrowing)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $borrowing->book->cover) }}"
                                                alt="Book Cover" class="book-image me-2" style="max-width: 50px;">
                                            {{ $borrowing->book->title }} <br>
                                        </div>
                                    </td>
                                    <td>
                                        1
                                    </td>
                                    <td>
                                        {{ $borrowing->book->author }}
                                    </td>
                                    <td>
                                        {{ $borrowing->book->isbn }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            {{ $borrowing->status->name }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Totals -->
                <div class="row justify-content-end">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Sub Total :</span>
                            <span></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Admin / fined</span>
                            <span>Rp.4.000</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount</span>
                            <span>Rp.0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total :</strong>
                            <strong>Rp.4.000</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
