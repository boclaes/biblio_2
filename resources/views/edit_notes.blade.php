@extends('layouts.app')

@section('title', 'Edit-page - Edit-page')

@section('content')

    <h2 class="title-notes">My Book notes</h2>


    <form method="POST" action="{{ route('save.notes', $book->id) }}">
        @csrf
        <div class="blur-overlay-notes"></div>
        
        <p class="sub-title-notes">Notes</p>
        <div class="container">
            <textarea id="notes" name="notes" rows="4" cols="50">{{ $book->notes_user }}</textarea><br>
        </div>
        
        <button class="save-notes" type="submit">Save</button>
    </form>
    <a href="{{ route('details.book', $book->id) }}">
        <button class="cancel-notes" type="button">Cancel</button>
    </a>


    <div class="blur-overlay-buttons"></div>
    <div class="button-container-notes">
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


@endsection