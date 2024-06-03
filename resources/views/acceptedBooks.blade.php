@extends('layouts.app_with_filters_wishlist', ['includeRatings' => false, 'includePages' => false, 'includeName' => true, 'includeAuthor' => true ])

@section('title', 'Accepted Books')

@section('content')
    <h2 class="title-wishlist">My Wishlist</h2>
    <div class="book-container" id="bookContainer">
        @foreach ($acceptedBooks as $book)
            <div class="book-card-library-wishlist" data-title="{{ $book->title }}">
                <form action="{{ route('delete.accepted.book', $book->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this book?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="image-button-wishlist">
                        <img src="{{ asset('images/delete-wishlist.png') }}" alt="Delete from Library" class="action-image-wishlist"/>
                    </button>
                </form>
                @if ($book->cover)
                    <img src="{{ $book->cover }}" alt="Book Cover" class="book-image-wishlist">
                @endif
                <h3 class="title-library-small">{{ $book->title }}</h3>
                <p class="author">{{ $book->author }}</p>
                @if ($book->purchase_link)
                    <a href="{{ $book->purchase_link }}" target="_blank" class="purchase-button">Buy this book</a>
                @endif
            </div>
        @endforeach
    </div>
    <script src="{{ asset('js/sorting.js') }}"></script>
@endsection

