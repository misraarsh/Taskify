@extends(session('user')['role'] === 'admin' ? 'admin.layouts.adminDash' : 'layouts.userDash')

@section(session('user')['role'] === 'admin' ? 'mainContent' : 'content')
@section('title', 'Tasks')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tasks</h3>
                    @if(Session::get('user')['role'] === 'admin')
                    <div class="card-tools">
                        <a href="{{ route('tasks.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Create New Task
                        </a>
                    </div>
                    @endif
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if(count($tasks) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Project</th>
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
                                            <td>{{ $task->project_title }}</td>
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
                        <div class="alert alert-info">No tasks found.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection