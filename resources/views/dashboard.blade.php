@extends('layouts.unit')

@section('title','Dashboard')

@section('form')

{{-- @if(session('success'))
<div class="popup-overlay">
    <div class="popup-message">
        <p>{{ session('success') }}</p> 
    </div>
</div>
    <script>
        setTimeout(() => {
            const msg = document.querySelector('.popup-overlay');
            if (msg) msg.style.display = 'none';
        }, 900); 
    </script>
@endif --}}

<div class="dashdiv">
    <h1>🎉 Congratulations on successfully crossing our authentication service!</h1>
    <a href="logout"><button class="logoutBtn">LOGOUT</button></a>
</div>



@endsection
