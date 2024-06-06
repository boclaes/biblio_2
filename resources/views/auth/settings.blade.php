@extends('layouts.app')

@section('title', 'Account settings')

@section('content')
<div class="container">
    <h2 class="title-borrow">Account Settings</h2>
</div>
<div class="account-settings">
    <!-- Error and success message display -->
    @if (session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif

    @if (session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <!-- Navigation buttons for account settings -->
    <ul>
        <li>
            <div class="button-container">
                <button onclick="toggleVisibility('change-email')">Change Email</button>
                <div id="change-email" class="form-container" style="display:none;">
                    <form action="{{ route('account.email') }}" method="POST">
                        @csrf
                        <input type="email" name="email" placeholder="New email" value="{{ auth()->user()->email }}">
                        <button type="submit">Update Email</button>
                    </form>
                </div>
            </div>
        </li>
        <li>
            <div class="button-container">
                <button onclick="toggleVisibility('change-password')">Change Password</button>
                <div id="change-password" class="form-container" style="display:none;">
                    <form action="{{ route('account.password') }}" method="POST">
                        @csrf
                        <input type="password" name="current_password" placeholder="Current Password" required>
                        <input type="password" name="new_password" placeholder="New Password" required>
                        <input type="password" name="new_password_confirmation" placeholder="Confirm New Password" required>
                        <button type="submit">Update Password</button>
                    </form>
                </div>
            </div>
        </li>
        <li>
            <div class="button-container">
                <button onclick="toggleVisibility('change-name')">Change Name</button>
                <div id="change-name" class="form-container" style="display:none;">
                    <form action="{{ route('account.name') }}" method="POST">
                        @csrf
                        <input type="text" name="name" placeholder="New name" value="{{ auth()->user()->name }}">
                        <button type="submit">Update Name</button>
                    </form>
                </div>
            </div>
        </li>
        <li>
            <div class="button-container">
                <button onclick="toggleVisibility('delete-account')">Delete Account</button>
                <div id="delete-account" class="form-container" style="display:none;">
                    <form action="{{ route('account.delete') }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Are you sure you want to delete your account?');">
                            Delete Account
                        </button>
                    </form>
                </div>
            </div>
        </li>
    </ul>
</div>

<script>
    function toggleVisibility(id) {
        var forms = ['change-email', 'change-password', 'change-name', 'delete-account'];
        forms.forEach(function(form) {
            var element = document.getElementById(form);
            if (form === id) {
                element.style.display = element.style.display === 'block' ? 'none' : 'block';
            } else {
                element.style.display = 'none';
            }
        });
    }
</script>
@endsection
