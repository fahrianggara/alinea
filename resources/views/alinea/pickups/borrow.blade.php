@extends('alinea.index')

@section('content')
    <div class="container">
        <div class="row">
            @foreach ($borrowings as $borrowing)
                <div class="col-lg-4">

                    <img src="{{ asset('storage/' . $borrowing->book->cover) }}" alt="Book Cover"class="img-fluid"
                        style="max-width: 50px;">
                    <h1>{{ $borrowing->book->title }}</h1><br>
                    <h1>{{ $borrowing->status->name }}</h1><br>

                </div>
            @endforeach
        </div>
    </div>
@endsection
