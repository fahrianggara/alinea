@extends('alinea.index')

@section('content')
    <div class="container">
        <div class="col-12 col-lg-12">
            <div class="col-lg-12" style="border-radius: 10px">
                {{-- <div class="d-flex justify-content-between align-items-center pt-3">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('storage/logo/logo.png') }}" alt="" width="60" class="mr-3">
                        <span class="h6 m-0">{{ $invoice->no_invoice }}</span>
                    </div>
                    <div class="d-flex">

                        <div class="d-flex align-items-center">
                            <form action="{{ route('pickups.pickup', $invoice->no_invoice) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary ml-2">Pickups</button>
                            </form>
                        </div>

                        <button class="btn btn-outline-danger ml-2" type="submit">
                            Cancel
                        </button>
                    </div>
                </div> --}}
                <hr>
                <div class="d-flex justify-content-between align-items-center">

                    <div class="my-3">
                        <div class="">
                            <img src="data:image/png;base64,{{ $invoice->qr_code }}" alt="QR Code" width="100">
                        </div>
                    </div>

                    <div class="my-3">
                        <div class="">
                            <h6 class="m-0">Invoice For</h6>
                            <small>{{ $fullname }}</small>
                        </div>
                    </div>

                    <div class="my-3">
                        <div class="">
                            <h6 class="m-0">Borrow At</h6>
                            <small>{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('Y/m/d') }}</small>
                        </div>
                    </div>

                    <div class="my-3">
                        <div class="">
                            <h6 class="m-0">Return At</h6>
                            <small>{{ \Carbon\Carbon::parse($borrowing->return_date)->format('Y/m/d') }}</small>
                        </div>
                    </div>

                    <div class="my-3">
                        <div class="">
                            <h6 class="m-0">Status</h6>
                            <small>{{ $invoice->status }}</small>
                        </div>
                    </div>

                    <div class="my-3">
                        <div class="">
                            <h6 class="m-0">Scanned By</h6>
                            <small> {{ $fullnameAdmin }} </small>
                        </div>
                    </div>
                </div>
                <hr>

            </div>
            <div class="col-lg-12">
                <table width="100%" class="book-description">
                    <tr>
                        <th>
                            No
                        </th>
                        <th>
                            cover
                        </th>
                        <th>
                            Title
                        </th>
                        <th>
                            ISBN
                        </th>
                        <th>
                            QTY
                        </th>
                        <th>
                            Status
                        </th>
                    </tr>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach ($invoice->borrowings as $borrowing)
                            <tr>
                                <td> {{ $no++ }}</td>
                                <td>
                                    <center><img src="{{ asset('storage/' . $borrowing->book->cover) }}" alt="Book Cover"
                                            style="max-width: 50px;">
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
                                    <h6>{{ $borrowing->book->title }} </h6>

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
            </div>
            <hr>
            <div class="col-lg-12 ml-auto text-right">

                @if ($borrowing->status_id == 1)
                    <div class="d-flex justify-content-end align-items-center">
                        <form action="{{ route('pickups.pickup', $invoice->id) }}" method="POST" class="mb-0">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary ml-2">Proceed with Pickup</button>
                        </form>
                        <a href="{{ route('pickups') }}">
                            <button class="btn btn-outline-danger ml-2" type="button">
                                Cancel
                            </button>
                        </a>
                    </div>
                @elseif($borrowing->status_id == 2)
                    <div class="d-flex justify-content-end align-items-center">
                        <h6 class="m-0">Your book has been successfully borrowed. <a href="{{ route('pickups') }}">Back
                                to Home</a></h6>
                    </div>
                @elseif($borrowing->status_id == 3)
                    <div class="d-flex justify-content-end align-items-center">
                        <h6 class="m-0">Your book has been successfully returned. <a href="{{ route('pickups') }}">Back
                                to Home</a></h6>
                    </div>
                @elseif(in_array($borrowing->status_id, [4, 5, 6]))
                    <div class="d-flex justify-content-end align-items-center">
                        <h6 class="m-0">There are issues with your book. Please contact support for assistance. <a
                                href="{{ route('pickups') }}">Back to Home</a></h6>
                    </div>
                @else
                    <div class="d-flex justify-content-end align-items-center">
                        <h6 class="m-0">Status is unclear, please contact support. <a href="{{ route('pickups') }}">Back
                                to Home</a></h6>
                    </div>
                @endif


            </div>
        </div>
    @endsection
