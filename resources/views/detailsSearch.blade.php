@extends('layouts.app')

@section('title', 'Book Details')

@section('content')

@php
    $rating = $book->reviews->avg('rating');
    $rating = $rating ? $rating : 0;
    $numStars = round($rating);
    $status = '';
    if ($book->want_to_read) {
        $status = 'Want to Read';
    } elseif ($book->reading) {
        $status = 'Reading';
    } elseif ($book->done_reading) {
        $status = 'Done Reading';
    }
@endphp
    <div class="container-details">
        <div class="back-button-container">
            <a href="javascript:void(0);" onclick="window.history.back();" class="close-button" aria-label="Back to Library">
                &times;
            </a>
        </div>
        <div class="book-card-details">
            <div class="book-details-container">
                <div class="book-image-container-details">
                    @if ($book->cover)
                        <img src="{{ $book->cover }}" alt="Book Cover" class="book-image">
                    @else
                        <p>No Cover Image</p>
                    @endif
                    <div class="book-details" data-book-id="{{ $book->id }}" data-csrf-token="{{ csrf_token() }}">
                        <div class="stars">
                        @for ($i = 1; $i <= $numStars; $i++)
                            <span class="star">&#9733;</span>
                        @endfor
                        @for ($i = $numStars + 1; $i <= 5; $i++)
                            <span class="star">&#9734;</span>
                        @endfor
                        </div>
                        <div class="reading-status">
                            <button class="status-button">To be read</button>
                            <div class="status-dropdown">
                                <span class="dropdown-item" data-status="to_be_read">To be read</span>
                                <span class="dropdown-item" data-status="in_progress">In progress</span>
                                <span class="dropdown-item" data-status="completed">Completed</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="book-text-details">
                    <h3>{{ $book->title }}</h3>
                    <p class="author-details">{{ $book->author }}</p>
                    <p>{{ $book->pages }} Pages</p>
                    <p>{{ $book->year }}</p>
                </div>
            </div>

            <div class="tab-container">
                <div class="tab-buttons">
                    <span class="tab-label active" data-tab="description">Description</span>
                    <span class="tab-label" data-tab="notes">Notes</span>
                    <span class="tab-label" data-tab="review">Review</span>
                </div>
                <div class="tab-content active" id="description">
                    <span class="description-field">
                        {{ $book->description }}
                    </span>
                </div>
                <div class="tab-content" id="notes">
                    <span class="description-field">
                        {{ $book->notes_user ? $book->notes_user : 'No custom notes' }}
                    </span>
                </div>
                <div class="tab-content" id="review">
                    <span class="description-field">
                        {{ $book->review ? $book->review : 'No custom review' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/books.js') }}"></script>
@endsection
