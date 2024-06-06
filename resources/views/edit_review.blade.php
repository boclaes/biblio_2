@extends('layouts.app')

@section('title', 'Edit-page - Edit-page')

@section('content')

    <h2 class="title-notes">Book rating</h2>
    <form method="POST" action="{{ route('save.review', $book->id) }}">
        @csrf
        <div class="blur-overlay-notes"></div>
        <p class="sub-title-notes">Review</p>
        <div class="container">
            <textarea id="review" name="review" rows="4" cols="50">{{ old('review', $review) }}</textarea><br>
        </div>
        <button class="save-notes" type="submit">Save</button>
    </form>
    <a href="{{ route('details.book', $book->id) }}">
        <button class="cancel-notes" type="button">Cancel</button>
    </a>

@endsection