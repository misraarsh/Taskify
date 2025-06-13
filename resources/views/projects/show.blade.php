@extends(session('user')['role'] === 'admin' ? 'admin.layouts.adminDash' : 'layouts.userDash')

@section(session('user')['role'] === 'admin' ? 'mainContent' : 'content')
@section('title', 'Project Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Project Details: {{ $project->title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('projects.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Projects
                        </a>
                        @if(Session::get('user')['role'] === 'admin')
                            <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit Project
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Project Information</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <th style="width: 30%">Title:</th>
                                            <td>{{ $project->title }}</td>
                                        </tr>
                                        <tr>
                                            <th>Description:</th>
                                            <td>{{ $project->description ?: 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Priority:</th>
                                            <td>
                                                @if($project->priority == 'high')
                                                    <span class="badge badge-danger">High</span>
                                                @elseif($project->priority == 'medium')
                                                    <span class="badge badge-warning">Medium</span>
                                                @else
                                                    <span class="badge badge-info">Low</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                @if($project->status == 'completed')
                                                    <span class="badge badge-success">Completed</span>
                                                @elseif($project->status == 'in_progress')
                                                    <span class="badge badge-primary">In Progress</span>
                                                @else
                                                    <span class="badge badge-secondary">Not Started</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Due Date:</th>
                                            <td>{{ $project->due_date ? date('M d, Y', strtotime($project->due_date)) : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created By:</th>
                                            <td>{{ $project->created_by }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At:</th>
                                            <td>{{ date('M d, Y H:i', strtotime($project->created_at)) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tags:</th>
                                            <td>
                                                @if($project->tags)
                                                    @foreach(explode(',', $project->tags) as $tag)
                                                        <span class="badge badge-secondary">{{ trim($tag) }}</span>
                                                    @endforeach
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Assigned Users</h4>
                                </div>
                                <div class="card-body">
                                    @if(count($assignedUsers) > 0)
                                        <ul class="list-group">
                                            @foreach($assignedUsers as $user)
                                                <li class="list-group-item">{{ $user->name }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>No users assigned to this project.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Tasks</h4>
                                    <div class="card-tools">
                                        @if(Session::get('user')['role'] === 'admin')
                                            <a href="{{ route('tasks.create') }}?project_id={{ $project->id }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-plus"></i> Add Task
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if(count($tasks) > 0)
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Title</th>
                                                        <th>Priority</th>
                                                        <th>Status</th>
                                                        <th>Due Date</th>
                                                        <th>Assigned To</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($tasks as $task)
                                                        <tr>
                                                            <td>{{ $task->title }}</td>
                                                            <td>
                                                                @if($task->priority == 'high')
                                                                    <span class="badge badge-danger">High</span>
                                                                @elseif($task->priority == 'medium')
                                                                    <span class="badge badge-warning">Medium</span>
                                                                @else
                                                                    <span class="badge badge-info">Low</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($task->status == 'completed')
                                                                    <span class="badge badge-success">Completed</span>
                                                                @elseif($task->status == 'in_progress')
                                                                    <span class="badge badge-primary">In Progress</span>
                                                                @else
                                                                    <span class="badge badge-secondary">Not Started</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $task->due_date ? date('M d, Y', strtotime($task->due_date)) : 'N/A' }}</td>
                                                            <td>{{ $task->assigned_to }}</td>
                                                            <td>
                                                                <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-info btn-sm">
                                                                    <i class="fas fa-eye"></i> View
                                                                </a>
                                                                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning btn-sm">
                                                                    <i class="fas fa-edit"></i> Edit
                                                                </a>
                                                                @if(Session::get('user')['role'] === 'admin')
                                                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: inline-block;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this task?')">
                                                                            <i class="fas fa-trash"></i> Delete
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-info">No tasks found for this project.</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection