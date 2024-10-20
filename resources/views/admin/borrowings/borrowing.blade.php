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
            <div class="card table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="title-top-table">Borrowings</span>
                    <button class="btn btn-success ml-auto py-1" id="triggerModal">
                        <i class="fa fa-plus mr-1"></i> Add Borrowings
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-sm" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Cover</th>
                                    <th>Information</th>
                                    <th>Borrower</th>
                                    <th>Borrow & Return date</th>
                                    <th>Status</th>
                                    <th>
                                        <center>Actions</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($borrowings as $borrowing)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>
                                            <img src="{{ asset('storage/' . $borrowing->book->cover) }}"
                                                alt="Book Cover"class="img-fluid" style="max-width: 50px;">
                                        </td>
                                        <td>
                                            <span class="small text-capitalize" style="color: #757575; font-size: 14px;">
                                                {{ $borrowing->book->category->name }} - {{ $borrowing->book->author }}
                                            </span>

                                            <br>

                                            <span class="font-weight-bold">{{ $borrowing->book->title }}</span><br>
                                            <span class="small">ISBN {{ $borrowing->book->isbn }}</span><br>

                                        </td>
                                        <td>
                                            @if ($borrowing->user->role == 'user')
                                                <span class="font-weight-bold">{{ $borrowing->user->first_name }}
                                                    {{ $borrowing->user->last_name }}</span>
                                                <span class="">( {{ $borrowing->user->nim }} )</span><br>
                                                <span class="small">{{ $borrowing->user->email }}</span>
                                            @elseif ($borrowing->user->role == 'admin')
                                                <span class="font-weight-bold">{{ $borrowing->user->first_name }}
                                                    {{ $borrowing->user->last_name }}</span>
                                                <span class="">( {{ ucfirst($borrowing->user->role) }} )</span><br>
                                                <span class="badge badge-danger">!!! This is a test book for admin
                                                    purposes</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="badge badge-success w-100">
                                                <span class="small">Borrowed at</span><br>
                                                {{ \Carbon\Carbon::parse($borrowing->borrow_date)->translatedFormat('d F Y') }}
                                            </div>
                                            <br>
                                            <div class="badge badge-danger w-100 mt-2">
                                                <span class="small">Returned at</span><br>
                                                {{ \Carbon\Carbon::parse($borrowing->return_date)->translatedFormat('d F Y') }}
                                            </div>
                                        </td>

                                        <td>
                                            <span class="badge w-100 py-3 {{ $borrowing->status->color }} text-capitalize">
                                                {{ $borrowing->status->name }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="dropdown d-flex align-items-center justify-content-center">

                                                <button class="btn btn-link text-dark text-center" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i> <!-- Ikon tiga titik -->
                                                </button>

                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                                    <li class="dropdown-item">
                                                        <button type="submit" class="btn d-flex align-items-center px-0"
                                                            data-toggle="modal"
                                                            data-target="#Updateborrowing{{ $borrowing->id }}">
                                                            <i class="fas fa-user-edit text-center mr-2"
                                                                style="width: 18px; font-size: 16px;"></i>
                                                            <span>Update book</span>
                                                        </button>
                                                    </li>

                                                    <li class="dropdown-item">
                                                        <form action="{{ route('categories.destroy', $borrowing->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this book?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn d-flex align-items-center px-0">
                                                                <i class="fas fa-trash text-center mr-2"
                                                                    style="width: 18px; font-size: 16px;"></i>
                                                                <span>Delete borrowing</span>
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </section>

    <!-- Modal untuk Add Borrowing -->
    <div class="modal fade" id="AddBorrowing" tabindex="-1" role="dialog" aria-labelledby="AddBorrowingLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="AddBorrowingLabel">Add Borrowing</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <form action="{{ route('borrowings.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <div class="form-group">
                            <label for="book_id" class="form-label small">Book</label>
                            <select name="book_id" id="book_id" class="form-control">
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}">
                                        {{ $book->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="borrow_date" class="form-label small">Borrow Date</label>
                            <input type="date" class="form-control" name="borrow_date" id="borrow_date" required>
                        </div>
                        <div class="form-group">
                            <label for="return_date" class="form-label small">Return Date (Optional)</label>
                            <input type="date" class="form-control" name="return_date" id="return_date">
                        </div>
                        <input type="hidden" name="status" value="borrowed">
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add Borrowing</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('triggerModal').addEventListener('click', function() {
            Swal.fire({
                title: 'This is for testing only!',
                icon: 'info',
                showCancelButton: true, // Tambahkan tombol Cancel
                confirmButtonText: 'Continue', // Teks tombol konfirmasi
                cancelButtonText: 'Cancel' // Teks tombol batal
            }).then((result) => {
                if (result.isConfirmed) {
                    // Lakukan aksi jika tombol "Continue" diklik
                    $('#AddBorrowing').modal('show');
                } else if (result.isDismissed) {
                    // Lakukan aksi jika tombol "Cancel" diklik (jika diperlukan)
                    console.log('Action was cancelled');
                }
            });
        });
    </script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endsection
