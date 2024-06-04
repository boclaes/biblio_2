@extends('layouts.app_no_filters', ['includeRatings' => false, 'includePages' => false, 'includeName' => true, 'includeAuthor' => true ])

@section('title', 'Support')

@section('content')
    <div class="container">
        <h2 class="title-borrow">Frequently asked questions</h2>
    </div>
    <div class="search-bar">
        <input type="text" class="search" id="search" placeholder="Search" autocomplete="off">
        <span class="search-icon"><i class="fas fa-search"></i></span>
    </div>

    <div class="support-button">
    <button type="button" class="primary big-button" onclick="window.location.href='{{ route('contact_platform') }}';">Ask a question</button>
    </div>

    <div class="accordion-platform">
    <div class="accordion-item">
        <button class="accordion-button" type="button">
            My item is missing some information, what can I do?
        </button>
        <div class="accordion-content">
            <p>All users can edit covers, titles, descriptions and creators. So feel free to make these changes as needed. Pro users can edit all data, so any missing information can be quickly added and removed. Alternatively, every item on the website has a “flag” option. If an item is flagged it will go under review and a Libib staff member will make any necessary changes within 48 business hours.</p>
        </div>
    </div>
    <div class="accordion-item">
        <button class="accordion-button" type="button">
            Is there a limit to how many items I can store?
        </button>
        <div class="accordion-content">
            <p>You can store up to 5000 books with the free version. With BiblioScan Pro you can store up to 10000 items.</p>
        </div>
        <div class="accordion-item">
        <button class="accordion-button" type="button">
            What does a Pro subscription get me?
        </button>
        <div class="accordion-content">
            <p>It gives you much more options and storage. Visit the pricing page for more information.</p>
        </div>
        <div class="accordion-item">
        <button class="accordion-button" type="button">
            What happens when I make a change to my subscription?
        </button>
        <div class="accordion-content">
            <p>If you change to pro you get a lot more options. If you change to free, all the extra's will become unavailable.</p>
        </div>
        <div class="accordion-item">
        <button class="accordion-button" type="button">
            How can I delete my account?
        </button>
        <div class="accordion-content">
            <p>You can delete your account in 'account settings'.</p>
        </div>
    </div>
   
</div>

