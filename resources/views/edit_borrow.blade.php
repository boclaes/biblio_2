@extends('layouts.app')

@section('title', 'Add borrow')

@section('content')
<div class="container">
    <div class="background-container">
        <img src="{{ asset('images/book_back.png') }}" alt="Background Image" class="background-image-borrow">
        <div class="blur-overlay-borrow"></div>
    </div>
    <h2 class="title-borrow">Who borrowed my book?</h2>
    <div class="borrow-container">
        <form action="{{ route('borrowings.update', $borrowing->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="book_id">Select a book</label>
                <select name="book_id" id="book_id" class="form-control-select" required>
                    @foreach ($books as $book)
                        <option value="{{ $book->id }}" {{ $book->id == $borrowing->book_id ? 'selected' : '' }}>{{ $book->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="borrower_name">Who borrowed it</label>
                <input type="text" id="borrower_name" name="borrower_name" class="form-control-name" value="{{ $borrowing->borrower_name }}" required>
            </div>
            <div class="form-group">
                <label for="borrowed_since">Borrowed since</label>
                <input type="date" id="borrowed_since" name="borrowed_since" class="form-control-date" value="{{ $borrowing->borrowed_since->format('Y-m-d') }}" required>
                <label for="borrowed_since" class="icon-label"><i class="fa fa-calendar" aria-hidden="true"></i></label>
            </div>
            <div class="button-group">
                <button type="submit" class="btn save-borrow">Update</button>
                <a href="{{ route('borrowed-books') }}" class="btn cancel-borrow">Cancel</a>
            </div>
        </form>
    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
@endsection