@extends('layouts.app')

@section('title', 'Scanning Page - Book Scanner')

@section('content')
    <div class="background-container">
        <img src="{{ asset('images/book_back.png') }}" alt="Background Image" class="background-image">
        <div class="blur-overlay"></div>
    </div>

    <h2 class="search-title">Add your books</h2>

    <div class="search-selection">
        Select Item Type
    </div>

    <div class="item-type-options">
        <label class="item-type-option">
            <input type="radio" name="itemType" value="book" checked> Book
        </label>
        <label class="item-type-option">
            <input type="radio" name="itemType" value="ebook"> E-book
        </label>
    </div>

    <form id="searchForm" method="post" action="{{ route('search') }}">
        @csrf
        <div class="search-options">
            <span class="search-option" id="searchByTitle" onclick="setSearchType('title')">Search Books</span>
            <span class="search-option" id="searchByIsbn" onclick="setSearchType('isbn')">Scan Books</span>
        </div>

        <input class="search-search" id="searchInput" type="text" name="query" placeholder="Enter Book Title" required autofocus autocomplete="off">
        <input class="search-search" id="searchType" type="hidden" name="searchType" value="title"> <!-- Default to title -->
        <span class="search-icon-search"><i class="fas fa-search"></i></span>

        <div class="search-description">
            Search by ISBN or keyword. ISBN search will auto-add an item.
        </div>

        <button class="search-accept" type="submit">Search Book</button>
    </form>
@endsection
