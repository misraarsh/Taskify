<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Taskify - @yield('title')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" href="{{ asset('images/titleIcon.ico') }}" type="image/x-icon">
  <link rel="stylesheet" href="{{ asset('css/userDash.css') }}">
</head>
<body>
    <div class="sidebar">
      <h2>Hello, {{ session('user') ['name']}} </h2>
      <div class="nav-section">
        <div class="nav-title">Overview</div>
        <a href="{{ route('adminDash') }}" class="nav-link {{ request()->routeIs('adminDash') ? 'active' : '' }}">� Dashboard Overview</a>
      </div>
  
   
      <div class="nav-section">
        <div class="nav-title">Projects</div>
        <div class="nav-submenu">
          <a href="{{ route('projects.index') }}" class="nav-link {{ request()->routeIs('projects.index') ? 'active' : '' }}">📁 All Projects</a>
          <a href="{{ route('projects.create') }}" class="nav-link {{ request()->routeIs('projects.create') ? 'active' : '' }}">➕ Create Project</a>
        </div>
      </div>
  
    
      <div class="nav-section">
        <div class="nav-title">Tasks</div>
        <div class="nav-submenu">
          <a href="{{ route('tasks.index') }}" class="nav-link {{ request()->routeIs('tasks.index') ? 'active' : '' }}">� All Tasks</a>
          <a href="{{ route('tasks.create') }}" class="nav-link {{ request()->routeIs('tasks.create') ? 'active' : '' }}">➕ Create Task</a>
        </div>
      </div>
  
      <div class="nav-section">
        <div class="nav-title">Users</div>
        <div class="nav-submenu">
          <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">👥 All Users</a>
          <a href="{{ route('users.create') }}" class="nav-link {{ request()->routeIs('users.create') ? 'active' : '' }}">➕ Add User</a>
        </div>
      </div>
  
      <div class="nav-section">
        <div class="nav-title">Settings</div>
        <div class="nav-submenu">
          <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">⚙️ Edit Profile</a>
        </div>
      </div>
    </div>
  
   
      <div class="navbar">
        <div class="logo">📂 Taskify</div>
        <div class="nav-right">
     <div class="user-info">
            <span class="user-name">{{ session('user')['name'] }}</span>
            <span class="user-role">{{ ucfirst(session('user')['role']) }}</span>
               </div>
          <a href="{{ route('profile.edit') }}">
            <img src=" {{asset('images/adminpp.png')}} " alt="DP" title="Edit Profile" style="cursor: pointer;" />
          </a>
          <a href="{{ route('logout') }}"><button class="logout-button">Logout</button></a>
        </div>
    </div>
    
    <div class="main-container">
      <div class="main-content">
        @yield('content')
      </div>
    </div>
</body>
</html>
