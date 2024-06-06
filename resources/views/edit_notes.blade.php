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

@endsection