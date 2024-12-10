<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Inter, sans-serif;
            -webkit-font-smoothing: antialiased;
            font-size: 16px;
            line-height: 1.3;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        .container {
            width: 700px !important;
            padding: 50px;
            margin: 0 auto !important;
            position: relative;
        }

        table td {
            vertical-align: middle !important;
        }

        /* tr:nth-child(even) {
            background-color: #D6EEEE;
        } */

        table:nth-child(1),
        tr {
            border-bottom: 1px solid #bbbbbb;
            padding-bottom: 10px;
        }

        .user-information small {
            font-size: 10px !important;
        }

        .book-description {
            font-size: 12px;
            border-top: 1px solid #bbbbbb;
            border-bottom: 1px solid #bbbbbb;
            padding: 10px 0;
        }

        .book-description th {
            font-size: 14px;
            padding-bottom: 10px;
            text-align: left !important;
        }

        .book-description table tbody tr {
            background-color: red
        }
    </style>
</head>

<body>
    @php
        $no = 1;
        $isPdf = request()->route()->getName() === 'invoices.download'; // Sesuaikan dengan nama route
    @endphp
    <div class="container">

        <table width="100%">
            <tr>
                <td align="left" style="width: 70px;">
                    <img src="{{ $isPdf ? public_path('storage/logo/logo.png') : asset('storage/logo/logo.png') }}"
                        alt="Logo" width="60">
                </td>
                <td align="left">
                    <h4 style="color: #4169E1;">
                        Invoice
                    </h4>
                </td>
                <td align="right" style="width: 50%;">

                    <span class="" style="color: #333333"> # {{ $invoice->no_invoice }}</span>
                </td>
            </tr>
        </table>
        <br>
        <table class="user-information" width="100%" style="margin: 20px 0">
            <tr>
                <td>
                    <img src="data:image/png;base64,{{ $invoice->qr_code }}" alt="QR Code" width="100">
                </td>
                <td>
                    <h5>Invoice For</h5>
                    <small style="text-transform: capitalize;">{{ $fullname }}</small>
                </td>
                <td>
                    <h5>Booked Date</h5>
                    <small>Borrow Date on 
                    {{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('Y/m/d') }}</small> 
                </td>
                <td>
                    <h5>Return Date</h5>
                     <small>Return Date on  
                        {{ \Carbon\Carbon::parse($borrowing->return_date)->format('Y/m/d') }}</small> 
                </td>
                <td>
                    <h5>Status</h5>
                    <small style="text-transform: capitalize;">{{ $invoice->status}}</small>
                </td>
            </tr>
        </table>
        <br>
        <table width="100%" class="book-description">
            <tr>
                <th>
                    <h5>No</h5>
                </th>
                <th>
                    <h5 style="text-align: center">Cover</h5>
                </th>
                <th>
                    <h5>Title</h5>
                </th>
                <th>
                    <h5>ISBN</h5>
                </th>
                <th>
                    <h5>QTY</h5>
                </th>
                <th>
                    <h5>Status</h5>
                </th>
            </tr>
            <tbody>
                @foreach ($invoice->borrowings as $borrowing)
                    <tr>
                        <td> {{ $no++ }}</td>
                        <td>
                            <center><img
                                    src="{{ $isPdf ? public_path('storage/' . $borrowing->book->cover) : asset('storage/' . $borrowing->book->cover) }}"
                                    alt="Book Cover" style="max-width: 50px;">
                            </center>
                        </td>
                        <td>
                            <div style="margin-bottom: 5px; color: #33333">
                                <span>{{ $borrowing->book->author }}</span>
                                -
                                <span>
                                    {{ $borrowing->book->published_date }}
                                </span>
                            </div>
                            <h4>{{ $borrowing->book->title }} </h4>

                        </td>
                        <td>
                            {{ $borrowing->book->isbn }}
                        </td>
                        <td>
                            1
                        </td>
                        <td style="text-transform: capitalize;">
                            {{ $borrowing->status->name }}
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

        <table style="width: 100%; font-size: 12px; font-weight:bold; margin-top: 10px;">
            <tr>
                <td style="width: 40%;">
                    <h6>*Please show this invoice when collecting and returning the book !</h6>
                </td>
                <td style="width: 30%; text-align: center;"></td>
                <td style="width: 15%; text-align: center;">
                    Fined : 
                </td>
                <td style="width: 15%; text-align: right;">
                    Rp. {{ number_format($invoice->total_amount, 0, ',', '.') }}
                </td>
            </tr>
        </table>

        
    </div>
</body>

</html>
