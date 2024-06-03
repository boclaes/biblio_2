@extends('layouts.app_with_filters_borrow', ['includeRatings' => false, 'includePages' => false, 'includeName' => false, 'includeAuthor' => false, 'includeDate' => true  ])

@section('title', 'Borrowed Books')

@section('content')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <div class="container">
        <h2 class="title-borrow">Who borrowed my book?</h2>
        <div class="book-container" id="bookContainer">
            @foreach ($borrowings as $borrowing)
                <div class="book-card">
                    <img src="{{ $borrowing->book->cover }}" alt="Cover" class="book-image">
                    <h3>{{ $borrowing->book->title }}</h3>
                    <p class="author">By {{ $borrowing->book->author }}</p>
                    <p>Borrowed by {{ $borrowing->borrower_name }}</p>
                    <p class="borrowed-since">Borrowed on {{ $borrowing->borrowed_since->format('Y-m-d') }}</p>
                    <form action="{{ route('borrowings.return', $borrowing->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Gave it back</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
    <script src="{{ asset('js/sorting.js') }}"></script>
@endsection