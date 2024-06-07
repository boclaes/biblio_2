@extends('layouts.app')

@section('title', 'Edit Book')

@section('content')

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h2 class="title-notes-book">Edit Book</h2>
    <div class="blur-overlay-book"></div>
    <form method="post" action="{{ route('update.book', ['id' => $book->id]) }}" class="edit-book-form">
        @csrf
        @method('PUT')

        <input type="hidden" name="query" value="{{ old('query', $query) }}">

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $book->title) }}" required>
        </div>

        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" id="author" name="author" value="{{ old('author', $book->author) }}" required>
        </div>

        <div class="form-group">
            <label for="pages">Pages</label>
            <input type="number" id="pages" name="pages" value="{{ old('pages', $book->pages) }}" required>
        </div>

        <div class="form-group">
            <label for="place">Place</label>
            <input type="number" id="place" name="place" value="{{ old('place', $book->place) }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" required>{{ old('description', $book->description) }}</textarea>
        </div>

        <button type="submit" class="save-notes-book">Save</button>
    </form>
    <a href="{{ route('details.book', $book->id) }}">
        <button type="button" class="cancel-notes-book">Cancel</button>
    </a>
    

    <div class="blur-overlay-buttons-book"></div>
    <div class="button-container-book">
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
