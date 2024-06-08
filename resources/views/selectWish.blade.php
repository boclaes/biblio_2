@extends('layouts.app')

@section('title', 'Select Book - Add Book')

@section('content')
    <h2 class="add-book-title">Add to wishlist</h2>

    @forelse ($books as $book)
        <div class="book-card">
            @php
                $googleBooksId = $book['id'];
            @endphp

            <div class="book-details-container">
                <div class="book-image-container">
                    <img src="{{ $book['volumeInfo']['imageLinks']['thumbnail'] ?? asset('images/default_cover.jpg') }}" alt="Cover Image" class="book-image-search">

                    <div class="button-container">
                        @if (array_key_exists($googleBooksId, $acceptedBookMap))
                            <button class="button-already-in-wishlist" disabled>Already in Wishlist</button>
                        @else
                            <form method="post" action="{{ route('add.book.wish') }}" class="button-add-library-container">
                                @csrf
                                <input type="hidden" name="bookId" value="{{ $book['id'] }}">
                                <input type="hidden" name="query" value="{{ request('query') }}">
                                <input type="hidden" name="searchType" value="title">
                                <input type="hidden" name="wish" value="{{ request('wish', 1) }}"> <!-- Ensure the wish parameter is included -->
                                <button type="submit" class="button-add-library">Add to Wishlist</button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="book-details">
                    <div class="book-header-add">
                        <h3>{{ $book['volumeInfo']['title'] }}</h3>
                        <p class="search-author">{{ isset($book['volumeInfo']['authors']) ? implode(', ', $book['volumeInfo']['authors']) : 'Unknown Author' }}</p>
                        <p>{{ isset($book['volumeInfo']['publishedDate']) ? date('Y', strtotime($book['volumeInfo']['publishedDate'])) : 'N/A' }}</p>
                        <p class="search-pages">{{ $book['volumeInfo']['pageCount'] ?? 'N/A' }} pages</p>
                        <p>{{ $book['volumeInfo']['categories'][0] ?? 'Classics' }}</p>
                    </div>
                </div>
            </div>
            <div class="description-container">
                <p class="description-title">Description</p>
                <p class="description-content">{{ $book['volumeInfo']['description'] ?? 'Description not available' }}</p>
            </div>
        </div>
    @empty
        <p>No books found for your search query.</p>
    @endforelse
@endsection

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
