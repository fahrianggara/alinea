<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #2436356452</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .book-image {
            width: 50px;
            height: 70px;
            object-fit: cover;
        }
        .invoice-header {
            color: #4169E1;
        }
        .status-paid {
            color: #28a745;
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
                        <img src="/placeholder.svg" alt="Book Icon" width="50" height="50" class="me-2">
                        <h1 class="invoice-header h3 mb-0">Invoice</h1>
                    </div>
                    <button class="btn btn-outline-primary">
                        <i class="bi bi-download"></i> Download
                    </button>
                </div>

                <!-- Invoice Number -->
                <h2 class="h4 mb-4">#2436356452</h2>

                <!-- Invoice Details -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <h6>Invoice For</h6>
                        <p>Sultan Jordy Priadi</p>
                    </div>
                    <div class="col-md-4">
                        <h6>Booked Date</h6>
                        <p>Submitted on 27/08/2024</p>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6>Due Date</h6>
                                <p>Submitted on 27/09/2024</p>
                            </div>
                            <div>
                                <h6>Status</h6>
                                <p class="status-paid">Paid</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pay For -->
                <div class="mb-4">
                    <h6>Pay For</h6>
                    <p>Three Books</p>
                </div>

                <!-- Items Table -->
                <div class="table-responsive mb-4">
                    <table class="table">
                        <thead class="bg-light">
                            <tr>
                                <th>Description</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="/placeholder.svg" alt="Book 1" class="book-image me-2">
                                        <span>Book 1</span>
                                    </div>
                                </td>
                                <td>1</td>
                                <td>IDR Price</td>
                                <td>IDR TOTAL PRICE</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="/placeholder.svg" alt="Book 2" class="book-image me-2">
                                        <span>Book 1</span>
                                    </div>
                                </td>
                                <td>1</td>
                                <td>IDR Price</td>
                                <td>IDR TOTAL PRICE</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="/placeholder.svg" alt="Book 3" class="book-image me-2">
                                        <span>Book 1</span>
                                    </div>
                                </td>
                                <td>1</td>
                                <td>IDR Price</td>
                                <td>IDR TOTAL PRICE</td>
                            </tr>
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