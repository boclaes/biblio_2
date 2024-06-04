@extends('layouts.app')

@section('title', 'Select Book - Add Book')

@section('content')
    <h2 class="add-book-title">Add your books</h2>

    @forelse ($books as $book)
        <div class="book-card">
            @php
                $googleBooksId = $book['id'];
            @endphp

            <div class="book-details-container">
                <div class="book-image-container">
                    @if (array_key_exists($googleBooksId, $userBookMap))
                    <a href="{{ route('details.book.search', ['id' => $userBookMap[$googleBooksId], 'query' => request('query')]) }}">
                        <img src="{{ $book['volumeInfo']['imageLinks']['thumbnail'] ?? asset('images/default_cover.jpg') }}" alt="Cover Image" class="book-image-hover">
                    </a>
                    @else
                        <img src="{{ $book['volumeInfo']['imageLinks']['thumbnail'] ?? asset('images/default_cover.jpg') }}" alt="Cover Image" class="book-image-search">
                    @endif
                    @if (!array_key_exists($googleBooksId, $userBookMap))
                        <form method="post" action="{{ route('addBook') }}" class="button-add-library-container">
                            @csrf
                            <input type="hidden" name="bookId" value="{{ $book['id'] }}">
                            <input type="hidden" name="query" value="{{ request('query') }}">
                            <input type="hidden" name="searchType" value="title"> 
                            <button type="submit" class="button-add-library">Add to Library</button>
                        </form>
                    @endif
                    <div class="button-container">
                        @if (array_key_exists($googleBooksId, $userBookMap))
                            <form method="post" action="{{ route('delete.book', ['id' => $userBookMap[$googleBooksId]]) }}" class="image-form" onsubmit="return confirm('Are you sure you want to delete this book?');">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="query" value="{{ request('query') }}">
                                <button type="submit" class="image-button-hover">
                                    <img src="{{ asset('images/delete.png') }}" alt="Delete from Library" class="action-image"/>
                                </button>
                            </form>
                            <form method="get" action="{{ route('edit.book', ['id' => $userBookMap[$googleBooksId]]) }}" class="image-form">
                                <button type="submit" class="image-button-hover">
                                    <img src="{{ asset('images/edit.png') }}" alt="Edit Book" class="action-image"/>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="book-details">
                    <div class="book-header-add">
                        <h3>{{ $book['volumeInfo']['title'] }}</h3>
                        <p class="search-author">{{ isset($book['volumeInfo']['authors']) ? implode(', ', $book['volumeInfo']['authors']) : 'Unknown Author' }}</p>
                        <p>{{ $book['volumeInfo']['publishedDate'] ? date('Y', strtotime($book['volumeInfo']['publishedDate'])) : 'N/A' }}</p>
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
