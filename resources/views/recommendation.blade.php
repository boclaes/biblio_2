@extends('layouts.app')

@section('title', 'Discover - Book Discover')

@section('content')
    <div class="recommendation-container">
        <div class="title-section">
            <h1 class="main-title">Discover your next <span class="highlight">favorite read!</span></h1>
            <h2 class="subtitle">Based on genre</h2>
        </div>
        @if ($books)
            <div class="books-grid">
                @foreach ($books as $book)
                    <div class="book-section" id="book-{{ $book['google_books_id'] }}" data-book="{{ json_encode($book) }}">
                        <img class="arrow-right" src="{{ asset('images/arrow-right.png') }}" onclick="handleDecision('accept', '{{ $book['google_books_id'] }}')">
                        <div class="book-cover">
                            <img src="{{ $book['cover'] ?? asset('images/default_cover.jpg') }}" alt="Cover Image of {{ $book['title'] ?? 'No Title' }}">
                        </div>
                        <div class="book-details">
                            <h3 class="book-title">{{ $book['title'] ?? 'No Title' }}</h3>
                            <p class="book-author">{{ $book['author'] ?? 'Unknown Author' }}</p>
                            <p class="book-description">{{ Str::limit($book['description'] ?? 'No description available.', 100, '...') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>No recommendations available at the moment.</p>
        @endif
    </div>
    <script src="{{ asset('js/recommendationHandler.js') }}"></script>
@endsection
