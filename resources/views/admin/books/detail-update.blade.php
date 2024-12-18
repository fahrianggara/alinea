@extends('admin.index')

@section('title', $title)

@section('content')
    <div class="content-header">
        <div class="container">
            <div class="row mb-2">
                <div class="col-sm-12 ml-auto">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">{{ $book->title }}</li>
                        <li class="breadcrumb-item"><a href="{{ route('books') }}">Books</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container">
            <div class="col-lg-4 mx-auto">
                <div class="card pt-3" style="border-radius: 10px; background-color: #8598FF;">

                    <div class="action py-2 px-3 d-flex justify-content-between align-items-center">
                        <a href="{{ route('books') }}" class="btn btn-light text-primary"
                            style="border-radius: 10px; font-weight: bold;">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>

                        <button class="btn btn-light text-primary" data-toggle="modal" data-target="#UpdateBook"
                            style="border-radius: 10px; font-weight: bold;">
                            <i class="fas fa-pen"></i>
                        </button>

                    </div>

                    <div class="cover mx-auto py-5">
                        <img src="{{ asset('storage/' . $book->cover) }}" alt="" class="img-fluid"
                            style="width: 171px; height:264px; box-shadow: 0px 10px 12.1px 0px rgba(0, 0, 0, 0.4); border-radius: 10px;">
                    </div>

                    <div class="information bg-light pb-4 pt-3 px-4" style="border-radius: 30px 30px 10px 10px">
                        <div class="category d-flex justify-content-between align-items-center pb-2 pt-3"
                            style="font-size: 14px;">
                            <div class="d-flex">
                                <span class="badge badge-warning p-2 text-white" style="background-color: #5878D9;">
                                    {{ $book->category->name }}
                                </span>
                                <span class="badge badge-warning p-2 text-white ml-2" style="background-color: #5878D9;">
                                    {{ $book->published_date }}
                                </span>
                                <span class="badge badge-warning p-2 text-white ml-2" style="background-color: #5878D9;">
                                    {{ $book->stock }}
                                </span>
                            </div>
                            <span class="badge badge-primary p-2 text-white" style="background-color: #5878D9;">
                                +<i class="fas fa-shopping-cart"></i>
                            </span>

                        </div>
                        <div class="title mt-3">
                            <h5 style="font-weight: bold; font-size: 16px;">{{ $book->title }}</h5>
                            <div class="d-flex justify-content-between align-items-center"
                                style="font-size: 12px; color: #757575;">
                                <span>Oleh {{ $book->author }}</span>
                            </div>
                        </div>
                        <div class="description mt-4 text-justify" style="font-size: 12px">
                            <h5 class="" style="font-size: 16px; font-weight:bold;">Sinopsis</h5>
                            {!! $book->description !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="UpdateBook">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Update Book</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="{{ route('books.update', $book->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Column Kiri -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" class="form-label small">Title</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        name="title" id="title" value="{{ old('title', $book->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="author" class="form-label small">Author</label>
                                    <input type="text" class="form-control @error('author') is-invalid @enderror"
                                        name="author" id="author" value="{{ old('author', $book->author) }}" required>
                                    @error('author')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="isbn" class="form-label small">ISBN</label>
                                    <input type="text" class="form-control @error('isbn') is-invalid @enderror"
                                        name="isbn" id="isbn" value="{{ old('isbn', $book->isbn) }}" required>
                                    @error('isbn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="stock" class="form-label small">Stock</label>
                                    <input type="number" class="form-control @error('stock') is-invalid @enderror"
                                        name="stock" id="stock" value="{{ old('stock', $book->stock) }}" required>
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Image Preview --}}
                                <div>
                                    <label for="cover" class="form-label small">Cover Preview</label>
                                    <img id="cover-preview" style="max-width: 100%; max-height:100%;">
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
                                    <input type="date"
                                        class="form-control @error('published_date') is-invalid @enderror"
                                        name="published_date" id="published_date"
                                        value="{{ old('published_date', $book->published_date) }}" required>
                                    @error('published_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="status" class="form-label small">Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" name="status"
                                        id="status" required>
                                        <option value="available"
                                            {{ old('status', $book->status) == 'available' ? 'selected' : '' }}>Available
                                        </option>
                                        <option value="borrowed"
                                            {{ old('status', $book->status) == 'borrowed' ? 'selected' : '' }}>Borrowed
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="category_id" class="form-label small">Category</label>
                                    <select class="form-control @error('category_id') is-invalid @enderror"
                                        name="category_id" id="category_id" required>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Gambar Cover dengan Cropper.js -->
                                <div class="form-group">
                                    <label for="cover" class="form-label small">Cover</label>
                                    <input type="file" class="form-control @error('cover') is-invalid @enderror"
                                        name="cover" id="cover" accept="image/*">
                                    @error('cover')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Preview gambar hasil crop -->
                                <div class="form-group mt-3">
                                    <label for="cover-cropped" class="form-label small">Cropped Image Preview</label>
                                    <img id="cropped-preview" style="max-width: 100%; display: none;">
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-2">
                            <div class="form-group py-3">
                                <label for="old-img" class="form-label small">Old Cover</label> <br>
                                <img src="{{ asset('storage/' . $book->cover) }}" alt="" id="old-img"
                                    class="img-fluid">
                            </div>
                        </div>
                        
                        <div class="col-12 col-lg-12">
                            <div class="form-group py-3">
                                <label for="description" class="form-label small">Description</label>
                                <textarea name="description" id="add-editor" rows="5"
                                    class="form-control @error('description') is-invalid @enderror">{{ old('description', $book->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Update Book</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cek apakah ada elemen yang mengandung class 'is-invalid' (tanda ada error)
            if (document.querySelector('.is-invalid')) {
                // Jika ada error, tampilkan modal
                $('#UpdateBook').modal('show');
            }
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script src="{{ asset('assets/js/books/modal-books.js') }}"></script>
@endsection
