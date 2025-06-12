<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taskify - Project Management System</title>
    <link rel="shortcut icon" href="{{ asset('images/titleIcon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body>
    <header class="header">
        <div class="header-content">
            <h1 class="logo">TASKIFY</h1>
            <nav class="nav-links">
                <a href="{{ route('login') }}" class="btn btn-login">Login</a>
                <a href="{{ route('register') }}" class="btn btn-register">Register</a>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <section class="hero">
            <div class="hero-content">
                <h1>Manage Your Projects With Ease</h1>
                <p>Taskify helps teams work more efficiently by organizing tasks, tracking progress, and improving collaboration.</p>
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                    <a href="{{ route('login') }}" class="btn btn-secondary">Sign In</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="{{ asset('images/indexBG.webp') }}" alt="Task Management Illustration">
            </div>
        </section>

        <section class="features">
            <h2>Why Choose Taskify?</h2>
            <div class="feature-cards">
                <div class="feature-card">
                    <div class="feature-icon">📋</div>
                    <h3>Task Management</h3>
                    <p>Create, assign, and track tasks with ease. Set priorities and deadlines to stay organized.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3>Team Collaboration</h3>
                    <p>Work together seamlessly with real-time updates and communication tools.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Progress Tracking</h3>
                    <p>Monitor project progress with visual dashboards and detailed reports.</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <p>&copy; {{ date('Y') }} Taskify. All rights reserved.</p>
    </footer>
</body>
</html>