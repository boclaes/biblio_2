@extends('layouts.app')

@section('title', 'Select Book - Add Book')

@section('content')
    <h2>Add your books</h2>

    @forelse ($books as $book)
        <div class="book-card">
            @php
                $googleBooksId = $book['id'];
            @endphp

            <div class="book-details-container">
                <div class="book-image-container">
                    <img src="{{ $book['volumeInfo']['imageLinks']['thumbnail'] ?? asset('images/default_cover.jpg') }}" alt="Cover Image" class="book-image">
                    @if (!array_key_exists($googleBooksId, $userBookMap))
                        <form method="post" action="{{ route('addBook') }}" class="button-add-library-container">
                            @csrf
                            <input type="hidden" name="bookId" value="{{ $book['id'] }}">
                            <input type="hidden" name="query" value="{{ request('query') }}">
                            <input type="hidden" name="searchType" value="title"> 
                            <button type="submit" class="button-add-library">Add to Library</button>
                        </form>
                    @endif
                </div>
                <div class="book-details">
                    <div class="book-header">
                        <h3>{{ $book['volumeInfo']['title'] }}</h3>
                        <p class="search-author">{{ isset($book['volumeInfo']['authors']) ? implode(', ', $book['volumeInfo']['authors']) : 'Unknown Author' }}</p>
                        <p>{{ $book['volumeInfo']['pageCount'] ?? 'N/A' }} pages</p>
                        <p>{{ $book['volumeInfo']['categories'][0] ?? 'Classics' }}</p>
                    </div>
                    @if (array_key_exists($googleBooksId, $userBookMap))
                        <form method="post" action="{{ route('delete.book', ['id' => $userBookMap[$googleBooksId]]) }}" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            @if (request('query'))
                                <input type="hidden" name="query" value="{{ request('query') }}">
                            @endif
                            <button type="submit" class="btn btn-delete">Delete from Library</button>
                        </form>
                        <form method="get" action="{{ route('edit.book', ['id' => $userBookMap[$googleBooksId]]) }}" style="display: inline;">
                            @if (request('query'))
                                <input type="hidden" name="query" value="{{ request('query') }}">
                            @endif
                            <button type="submit" class="btn btn-edit">Edit</button>
                        </form>
                    @endif
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
