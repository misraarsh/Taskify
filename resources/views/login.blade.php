@extends('layouts.unit')

@section('title', 'Login')

@section('form')
@if(session('success'))
<div class="popup-overlay">
    <div class="popup-message">
        <p>{{ session('success') }}</p>
    </div>
</div>
    <script>
        setTimeout(() => {
            const msg = document.querySelector('.popup-overlay');
            if (msg) msg.style.display = 'none';
        }, 3000); 
    </script>
@endif

<h1 class="head">Login</h1>
<div class="form-wrapper">
    <div class="form-image">
        <img src="{{ asset('images/indexBG.webp') }}" alt="Login Illustration">
    </div>
    <div class="form-container">
        <form action="{{ route('login') }}" class="form-details" method="post">
            @csrf
            <input type="email" class="form-inputs" id="email" name="email" placeholder="Email">
            <input type="password" class="form-inputs" id="password" name="password" placeholder="Password">
            <button class="submit-btn" type="submit">Log In</button>
            <p class="login-link">New User? <a href="{{ route('register') }}">Sign Up</a></p>
            <div class="errorMsg"></div>
            @if ($errors->any())
                <div class="errorMsg">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li style="color:red;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </form>

    </div>
</div>
@endsection
