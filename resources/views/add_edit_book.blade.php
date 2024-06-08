@extends('layouts.app')

@section('title', 'Edit Book')

@section('content')

    <div class="back-button-container-notes">
        <a href="javascript:void(0);" onclick="window.history.back();" class="close-button" aria-label="Back to Library">
            &times;
        </a>
    </div>

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
    <a href="javascript:void(0);" onclick="window.history.back();">
        <button type="button" class="cancel-notes-book">Cancel</button>
    </a>
@endsection
