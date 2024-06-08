@extends('layouts.app')

@section('title', 'Edit-page - Edit-page')

@section('content')

<div class="back-button-container-notes">
    <a href="{{ route('details.book', $book->id) }}" class="close-button" aria-label="Back to Library">
        &times;
    </a>
</div>

<h2 class="title-notes">Book rating</h2>
<div class="book-details" data-book-id="{{ $book->id }}" data-csrf-token="{{ csrf_token() }}"></div>
<div class="stars" data-book-id="{{ $book->id }}" data-csrf-token="{{ csrf_token() }}">
@php
    $response = app()->call('App\Http\Controllers\BookController@getBookRating', ['id' => $book->id]);
    $rating = $response->original['rating'];
    $rating = $rating ? $rating : 0;
@endphp
@for ($i = 1; $i <= 5; $i++)
    @php
        $activeClass = $i <= $rating ? 'active' : '';
    @endphp
    <span class="star-review {{ $activeClass }}" data-value="{{ $i }}">&#9733;</span>
@endfor
</div>
<form method="POST" action="{{ route('save.review', $book->id) }}">
    @csrf
    <div class="blur-overlay-notes"></div>
    <p class="sub-title-review">Review</p>
    <div class="container">
        <textarea id="review" name="review" rows="4" cols="50">{{ old('review', $review) }}</textarea><br>
    </div>
    <button class="save-notes" type="submit">Save</button>
</form>
<a href="{{ route('details.book', $book->id) }}">
    <button class="cancel-notes" type="button">Cancel</button>
</a>

<div class="blur-overlay-buttons"></div>
<div class="button-container-review">
    <a href="{{ route('edit.book', $book->id) }}" class="btn-with-icon">
        <img src="{{ asset('images/edit-notes.png') }}" class="images-notes-pen" alt="Edit Book Icon">
        <span>Edit Book</span>
    </a>
    <a href="{{ route('edit.notes', $book->id) }}" class="btn-with-icon">
        <img src="{{ asset('images/notes.png') }}" class="images-notes" alt="Notes Icon">
        <span>Notes</span>
    </a>
    <a href="{{ route('edit.review', $book->id) }}" class="btn-with-icon">
        <img src="{{ asset('images/review-notes.png') }}" class="images-notes" alt="Review Icon">
        <span>Review</span>
    </a>
    <form action="{{ route('delete.book', $book->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this book?');" class="btn-with-icon">
        @csrf
        @method('DELETE')
        <button type="submit" class="delete-button">
            <img src="{{ asset('images/delete-notes.png') }}" class="images-notes-delete" alt="Delete Icon">
            <span>Delete</span>
        </button>
    </form>
</div>

<script>
    const bookId = "{{ $book->id }}";
    const token = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/books.js') }}"></script>
@endsection
