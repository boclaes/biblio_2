@extends('layouts.app_with_filters', ['includeRatings' => true, 'includePages' => false, 'includeName' => true, 'includeAuthor' => true])

@section('title', 'Simple Book List')

@section('content')
    <h2 class="title-library">My library</h2>
    <div class="book-container" id="bookContainer">
        @foreach ($books->sortBy('title') as $book)
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
            <div class="book-card-library" data-title="{{ $book->title }}">
                <form action="{{ route('show.book', $book->id) }}" method="POST">
                    @csrf
                    <button class="image-button-library" type="submit">
                        <img src="{{ asset('images/Showbook.png') }}" alt="Delete from Library" class="action-image-wishlist"/>
                    </button>
                </form>
                @if ($book->cover)
                    <a href="{{ route('details.book', $book->id) }}">
                        <img src="{{ $book->cover }}" alt="Book Cover" class="book-image">
                    </a>
                @else
                    <p>No Cover Image</p>
                @endif
                <h3 class="title-library-small">{{ $book->title }}</h3>
                <p class="author">{{ $book->author }}</p>
                <div class="stars" data-rating="{{ $numStars }}">
                    @for ($i = 1; $i <= $numStars; $i++)
                        <span class="star">&#9733;</span>
                    @endfor
                    @for ($i = $numStars + 1; $i <= 5; $i++)
                        <span class="star">&#9734;</span>
                    @endfor
                </div>
            </div>

        @endforeach
    </div>
@endsection
