@extends('layouts.app_with_filters_wishlist', ['includeRatings' => false, 'includePages' => false, 'includeName' => true, 'includeAuthor' => true ])

@section('title', 'Accepted Books')

@section('content')
    <h2 class="title-wishlist">My Wishlist</h2>
    <div class="book-container" id="bookContainer">
        @foreach ($acceptedBooks as $book)
            <div class="book-card-library-wishlist" id="book-{{ $book->id }}" data-title="{{ $book->title }}">
                <form action="{{ route('delete.accepted.book', $book->id) }}" method="POST" onsubmit="return handleDelete(event, {{ $book->id }});">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="image-button-wishlist">
                        <img src="{{ asset('images/arrow-left.png') }}" alt="Delete from Library" class="action-image-wishlist"/>
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
    <script>
        function handleDelete(event, bookId) {
            event.preventDefault(); // Prevent the form from submitting immediately
            const bookCard = document.getElementById(`book-${bookId}`);
            bookCard.classList.add('fade-out'); // Apply the fade-out class

            // Wait for the fade-out animation to complete before submitting the form
            setTimeout(() => {
                event.target.submit();
            }, 500); // Match this duration to the CSS transition duration
        }
    </script>
@endsection
