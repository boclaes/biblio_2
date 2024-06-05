@extends('layouts.app')

@section('title', 'Contact_platform')

@section('content')
    <div class="contact-view">
        <div class="container">
            <a href="{{ route('support_platform') }}"><img src="{{ Vite::asset('resources/images/back.png') }}" alt="Go back" class="back" ></a>
            <div class="wrapper">
                <div class="contact-view-box">
                    <div class="center">
                        <h1 class="title">Contact us</h1>
                        <div class="login-message">
                            <span>Reach out to us with any inquiries or feedback – we're here to assist you!</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <label for="name">Name</label>
                        <input type="text" name="name" placeholder="Name" value="{{ old('name') }}" required>
                        @error('name')
                        <div style="color: red;">{{ $message }}</div>
                        @enderror

                        <label for="email">Email</label>
                        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
                        @error('email')
                        <div style="color: red;">{{ $message }}</div>
                        @enderror

                        <label for="comments">Comments / Questions</label>
                        <input type="comments" name="comments" placeholder="Type your comment / question here" minlength="8" required>
                        @error('comments')
                        <div style="color: red;">{{ $message }}</div>
                        @enderror

                        <button type="submit" class="primary big-button">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endsection