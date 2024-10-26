@extends('admin.index')

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 ml-auto">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Books</a></li>
                        <li class="breadcrumb-item active">Dashboard Alinea</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
            <div class="card table-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="title-top-table">Book Manage</span>
                    <button class="btn btn-success ml-auto py-1" data-toggle="modal" data-target="#AddBook">
                        <i class="fa fa-plus mr-1"></i> Add book
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-lg" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Cover</th>
                                    <th>Information</th>
                                    <th>Stock</th>
                                    <th>ISBN</th>
                                    <th>Created</th>
                                    <th>
                                        <center>Actions</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach ($books as $book)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>
                                            <img src="{{ asset('storage/' . $book->cover) }}"
                                                alt="Book Cover"class="img-fluid" style="max-width: 50px;">
                                        </td>
                                        <td>
                                            <span class="small text-capitalize" style="color: #757575; font-size: 14px;">
                                                {{ $book->category->name }} - {{ $book->author }} - {{ $book->published_date }}
                                            </span>

                                            <br>
                                            
                                            <span class="font-weight-bold">{{ $book->title }}</span><br>

                                            <span>
                                                {!! Str::limit($book->description, 100) !!}
                                            </span>

                                        </td>
                                        <td>{{ $book->stock }}</td>
                                        <td>{{ $book->isbn }}</td>
                                        <td>{{ $book->created_at->diffForHumans() }}</td>
                                        <td>
                                            <div class="dropdown d-flex align-items-center justify-content-center">

                                                <button class="btn btn-link text-dark text-center" type="button"
                                                    id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i> <!-- Ikon tiga titik -->
                                                </button>

                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li class="dropdown-item">
                                                        <a class="btn d-flex align-items-center px-0" style=""
                                                            href="{{ route('books.show', $book->id) }}">
                                                            <i class="fas fa-eye text-center mr-2"
                                                                style="widht: 18px; font-size: 16px;"></i>
                                                            <span>Detail book</span>
                                                        </a>
                                                    </li>
                                                    <li class="dropdown-item">
                                                        <form action="{{ route('books.destroy', $book->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Are you sure you want to delete this book?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn d-flex align-items-center px-0">
                                                                <i class="fas fa-trash text-center mr-2"
                                                                    style="width: 18px; font-size: 16px;"></i>
                                                                <span>Delete book</span>
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            {{-- <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Cover</th>
                                    <th>Information</th>
                                    <th>Stock</th>
                                    <th>ISBN</th>
                                    <th>Created</th>
                                    <th>
                                        <center>Actions</center>
                                    </th>
                                </tr>
                            </tfoot> --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tambahkan Link CSS Cropper.js di Head -->

    <div class="modal fade" id="AddBook">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Add Book</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="{{ route('books.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <!-- Column Kiri -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" class="form-label small">Title</label>
                                    <input type="text" class="form-control" name="title" id="title" required>
                                </div>
                                <div class="form-group">
                                    <label for="author" class="form-label small">Author</label>
                                    <input type="text" class="form-control" name="author" id="author" required>
                                </div>
                                <div class="form-group">
                                    <label for="isbn" class="form-label small">ISBN</label>
                                    <input type="text" class="form-control" name="isbn" id="isbn" required>
                                </div>
                                <div class="form-group">
                                    <label for="stock" class="form-label small">Stock</label>
                                    <input type="number" class="form-control" name="stock" id="stock" required>
                                </div>

                                {{-- image preview brow --}}
                                <div>
                                    <label for="cover" class="form-label small">Cover Preview</label>
                                    <img id="cover-preview"style="max-width: 100%; max-height:100%; display: none;">
                                </div>

                                <!-- Tombol "Selesai", "Batal" untuk crop, dan "Reset" setelah selesai -->
                                <div id="crop-actions" class="py-3" style="display: none;">
                                    <button type="button" class="btn btn-primary" id="crop-done">Selesai</button>
                                    <button type="button" class="btn btn-danger" id="crop-cancel">Batal</button>
                                </div>

                                <!-- Tombol Reset hanya muncul setelah selesai cropping -->
                                <div id="reset-action" class="py-3" style="display: none;">
                                    <button type="button" class="btn btn-warning" id="crop-reset">Reset</button>
                                </div>

                                <input type="hidden" name="cropped_image" id="cropped_image">
                            </div>

                            <!-- Column Kanan -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="published_date" class="form-label small">Published Date</label>
                                    <input type="date" class="form-control" name="published_date" id="published_date"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="status" class="form-label small">Status</label>
                                    <select class="form-control" name="status" id="status" required>
                                        <option value="available">Available</option>
                                        <option value="borrowed">Borrowed</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="category_id" class="form-label small">Category</label>
                                    <select class="form-control" name="category_id" id="category_id" required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Gambar Cover dengan Cropper.js -->
                                <div class="form-group">
                                    <label for="cover" class="form-label small">Cover</label>
                                    <input type="file" class="form-control" name="cover" id="cover"
                                        accept="image/*">
                                </div>

                                <!-- Preview gambar hasil crop -->
                                <div class="form-group mt-3">
                                    <label for="cover-cropped" class="form-label small">Cropped Image Preview</label>
                                    <img id="cropped-preview" style="max-width: 100%; display: none;">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label small">Description</label>
                            <textarea name="description" id="add-editor" rows="5" class="form-control"></textarea>
                        </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add Book</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Tambahkan Script JS Cropper.js di Akhir Body -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script src="{{ asset('assets/js/books/modal-books.js') }}"></script>
@endsection
