<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskify - @yield('title')</title>
    <link rel="shortcut icon" href="{{ asset('images/titleIcon.ico') }}" type="image/x-icon">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>
    <header class="heading">
        <div class="header-content">
            <h1 class="logo">TASKIFY</h1>
        </div>
    </header>

    @yield('form')

    <footer class="last">
        <p>&copy; {{ date('Y') }} Taskify. All rights reserved.</p>
    </footer>

    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
