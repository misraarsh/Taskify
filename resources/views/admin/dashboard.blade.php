@extends('admin.layouts.adminDash')

@section('title','Admin Dashboard')

@section('mainContent')

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<div class="dashboard-header">
    <h1>Admin Dashboard</h1>
    <p>Welcome, {{ session('user')['name'] }}</p>
</div>

<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon">📊</div>
        <div class="stat-content">
            <h3>{{ $totalProjects }}</h3>
            <p>Total Projects</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📝</div>
        <div class="stat-content">
            <h3>{{ $totalTasks }}</h3>
            <p>Total Tasks</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-content">
            <h3>{{ $totalUsers }}</h3>
            <p>Total Users</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-content">
            <h3>{{ $taskStatusCounts['completed'] }}</h3>
            <p>Completed Tasks</p>
        </div>
    </div>
</div>

<div class="dashboard-actions">
    <a href="{{ route('projects.create') }}" class="action-button">
        <span class="action-icon">➕</span>
        <span class="action-text">Create Project</span>
    </a>
    <a href="{{ route('tasks.create') }}" class="action-button">
        <span class="action-icon">➕</span>
        <span class="action-text">Create Task</span>
    </a>
    <a href="{{ route('projects.index') }}" class="action-button">
        <span class="action-icon">📋</span>
        <span class="action-text">View Projects</span>
    </a>
    <a href="{{ route('tasks.index') }}" class="action-button">
        <span class="action-icon">📋</span>
        <span class="action-text">View Tasks</span>
    </a>
</div>

<div class="dashboard-content">
    <div class="dashboard-section">
        <div class="section-header">
            <h2>Recent Projects</h2>
            <a href="{{ route('projects.index') }}" class="view-all">View All</a>
        </div>
        
        @if(count($recentProjects) > 0)
        <div class="project-list">
            @foreach($recentProjects as $project)
            <div class="project-card">
                <div class="project-header">
                    <h3><a href="{{ route('projects.show', $project->id) }}">{{ $project->title }}</a></h3>
                    <span class="project-priority priority-{{ $project->priority }}">{{ ucfirst($project->priority) }}</span>
                </div>
                <div class="project-meta">Created by: {{ $project->created_by }}</div>
                <div class="project-description">{{ Str::limit($project->description, 100) }}</div>
                <div class="project-footer">
                    <div class="project-status status-{{ $project->status }}">{{ str_replace('_', ' ', ucfirst($project->status)) }}</div>
                    @if($project->due_date)
                    <div class="project-due-date">Due: {{ date('M d, Y', strtotime($project->due_date)) }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <p>No projects created yet.</p>
        </div>
        @endif
    </div>
    
    <div class="dashboard-section">
        <div class="section-header">
            <h2>Recent Tasks</h2>
            <a href="{{ route('tasks.index') }}" class="view-all">View All</a>
        </div>
        
        @if(count($recentTasks) > 0)
        <div class="task-list">
            @foreach($recentTasks as $task)
            <div class="task-card">
                <div class="task-header">
                    <h3><a href="{{ route('tasks.show', $task->id) }}">{{ $task->title }}</a></h3>
                    <span class="task-priority priority-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
                </div>
                <div class="task-meta">
                    <span>Project: {{ $task->project_title }}</span>
                    <span>Assigned to: {{ $task->assigned_to }}</span>
                </div>
                <div class="task-description">{{ Str::limit($task->description, 100) }}</div>
                <div class="task-footer">
                    <div class="task-status status-{{ $task->status }}">{{ str_replace('_', ' ', ucfirst($task->status)) }}</div>
                    @if($task->due_date)
                    <div class="task-due-date">Due: {{ date('M d, Y', strtotime($task->due_date)) }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <p>No tasks created yet.</p>
        </div>
        @endif
    </div>
    
    <div class="dashboard-section">
        <div class="section-header">
            <h2>Task Status Overview</h2>
        </div>
        
        <div class="status-overview">
            <div class="status-card status-not_started">
                <h3>Not Started</h3>
                <div class="status-count">{{ $taskStatusCounts['not_started'] }}</div>
            </div>
            <div class="status-card status-in_progress">
                <h3>In Progress</h3>
                <div class="status-count">{{ $taskStatusCounts['in_progress'] }}</div>
            </div>
            <div class="status-card status-completed">
                <h3>Completed</h3>
                <div class="status-count">{{ $taskStatusCounts['completed'] }}</div>
            </div>
        </div>
    </div>
</div>

@endsection