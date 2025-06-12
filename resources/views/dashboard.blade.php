@extends('layouts.userDash')

@section('title','Dashboard')

@section('content')

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
    <h1>Welcome, {{ session('user')['name'] }}</h1>
    <p>Here's an overview of your tasks and projects</p>
</div>

<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon">📊</div>
        <div class="stat-content">
            <h3>{{ $taskStatusCounts['not_started'] + $taskStatusCounts['in_progress'] + $taskStatusCounts['completed'] }}</h3>
            <p>Total Tasks</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🚀</div>
        <div class="stat-content">
            <h3>{{ $taskStatusCounts['in_progress'] }}</h3>
            <p>In Progress</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div class="stat-content">
            <h3>{{ $taskStatusCounts['completed'] }}</h3>
            <p>Completed</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📁</div>
        <div class="stat-content">
            <h3>{{ count($projects) }}</h3>
            <p>Projects</p>
        </div>
    </div>
</div>

<div class="dashboard-content">
    <div class="dashboard-section">
        <div class="section-header">
            <h2>My Tasks</h2>
            <a href="{{ route('tasks.index') }}" class="view-all">View All</a>
        </div>
        
        @if(count($tasks) > 0)
        <div class="task-list">
            @foreach($tasks as $task)
            <div class="task-card">
                <div class="task-header">
                    <h3><a href="{{ route('tasks.show', $task->id) }}">{{ $task->title }}</a></h3>
                    <span class="task-priority priority-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
                </div>
                <div class="task-project">Project: {{ $task->project_title }}</div>
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
            <p>You don't have any tasks assigned yet.</p>
        </div>
        @endif
    </div>
    
    <div class="dashboard-section">
        <div class="section-header">
            <h2>My Projects</h2>
            <a href="{{ route('projects.index') }}" class="view-all">View All</a>
        </div>
        
        @if(count($projects) > 0)
        <div class="project-list">
            @foreach($projects as $project)
            <div class="project-card">
                <div class="project-header">
                    <h3><a href="{{ route('projects.show', $project->id) }}">{{ $project->title }}</a></h3>
                    <span class="project-priority priority-{{ $project->priority }}">{{ ucfirst($project->priority) }}</span>
                </div>
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
            <p>You don't have any projects assigned yet.</p>
        </div>
        @endif
    </div>
</div>

@endsection
