@extends('layouts.app_with_filters_borrow', ['includeRatings' => false, 'includePages' => false, 'includeName' => false, 'includeAuthor' => false, 'includeDate' => true  ])

@section('title', 'Borrowed Books')

@section('content')
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <div class="container">
        <h2 class="title-borrow">Who borrowed my book?</h2>
            @foreach ($borrowings as $borrowing)
                <div class="book-card-borrow">
                    <div class="book-details-container">
                        <div class="book-image-container">
                            <img src="{{ $borrowing->book->cover }}" alt="Cover" class="book-image-borrow">
                            <div class="button-container-borrow">
                                <form method="POST" action="{{ route('borrowings.return', $borrowing->id) }}" class="image-form" onsubmit="return confirm('Are you sure this book has returned?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="image-button">
                                        <img src="{{ asset('images/delete.png') }}" alt="Delete from Library" class="action-image"/>
                                    </button>
                                </form>
                                <form method="get" action="{{ route('borrowings.edit', $borrowing) }}" class="image-form">
                                    <button type="submit" class="image-button">
                                        <img src="{{ asset('images/edit.png') }}" alt="Edit Borrowing" class="action-image"/>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="book-details">
                            <div class="book-header-add">
                                <h3>{{ $borrowing->book->title }}</h3>
                                <p class="author-borrow">{{ $borrowing->book->author }}</p>
                                <p><span class="person-borrow">Person:</span> {{ $borrowing->borrower_name }}</p>
                                <p class="borrowed-since"><span class="date-borrow">Date:</span> {{ $borrowing->borrowed_since->format('Y-m-d') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
    </div>
    <script src="{{ asset('js/sorting.js') }}"></script>
@endsection