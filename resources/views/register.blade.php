@extends('layouts.unit')
{{-- @include('app/signUp.php') --}}
@section('title', 'Register')

@section('form')
<h1 class="head">Register Now</h1>
<div class="form-wrapper">
    <div class="form-image">
        <img src="{{ asset('images/indexBG.webp') }}" alt="Register Illustration">
    </div>
    <div class="form-container">
        <form action="{{ route('register') }}" class="form-details" method="post">
            @csrf
            <input type="text" class="form-inputs" id="name" name="name" placeholder="Full Name">
            <input type="email" class="form-inputs" id="email" name="email" placeholder="Email">
            <input type="password" class="form-inputs" id="password" name="password" placeholder="Password">
            <input type="password" class="form-inputs" id="cpassword" name="password_confirmation" placeholder="Confirm Password">
            <button class="submit-btn" type="submit">Register</button>
            <p class="login-link">Already Registered? <a href="/login">Log In</a></p>
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
