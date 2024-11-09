{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <div class="col-lg-6 mx-auto">
            <h2 class="text-center mb-4">Invoice</h2>
            <img src="data:image/png;base64,{{ $invoice->qr_code }}" alt="QR Code" class="img-fluid"
                    width="100">
            @foreach ($invoice->borrowings as $borrowing)
                
            @endforeach

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html> --}}


<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Aloha!</title>

    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }

        table {
            font-size: x-small;
        }

        tfoot tr td {
            font-weight: bold;
            font-size: x-small;
        }

        .gray {
            background-color: lightgray
        }
    </style>

</head>

<body>

    <table width="100%">
        <tr>
            <td valign="top" style="padding: 30px 0 50px 0"><img src="data:image/png;base64,{{ $invoice->qr_code }}"
                    alt="QR Code" width="150"></td>
            <td align="right">
                <h3>Alinea Library</h3>
                <pre>
                Company representative name
                Company address
                Tax ID
                phone
                fax
            </pre>
            </td>
        </tr>

    </table>

    <table width="100%">
        <tr>
            <td><strong>From:</strong> Agus - Alinea Admin</td>
            <td><strong>To:</strong> Agus Sedih - Borrower Book Alinea</td>
        </tr>

    </table>

    <br />

    <table width="100%">
        <thead style="background-color: lightgray;">
            <tr>
                <th>#</th>
                <th>Information</th>
                <th>Quantity</th>
                <th>Unit Price $</th>
                <th>Total $</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($invoice->borrowings as $borrowing)

                <tr>
                    <th scope="row">{{ $no++ }}</th>
                    <td style="display: inline">
                        <img src="{{ public_path('storage/' . $borrowing->book->cover) }}" style="max-width: 80px;">
                    </td>
                    <td align="right">1</td>
                    <td align="right">1400.00</td>
                    <td align="right">1400.00</td>
                </tr>

            @endforeach
        </tbody>

        {{-- <tfoot>
            <tr>
                <td colspan="3"></td>
                <td align="right">Subtotal $</td>
                <td align="right">1635.00</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td align="right">Tax $</td>
                <td align="right">294.3</td>
            </tr>
            <tr>
                <td colspan="3"></td>
                <td align="right">Total $</td>
                <td align="right" class="gray">$ 1929.3</td>
            </tr>
        </tfoot> --}}
    </table>

</body>

</html>
