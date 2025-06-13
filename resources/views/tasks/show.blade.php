@extends(session('user')['role'] === 'admin' ? 'admin.layouts.adminDash' : 'layouts.userDash')

@section(session('user')['role'] === 'admin' ? 'mainContent' : 'content')
@section('title', 'Task Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Task Details: {{ $task->title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('tasks.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Tasks
                        </a>
                        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit Task
                        </a>
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
                                    <h4>Task Information</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <th style="width: 30%">Title:</th>
                                            <td>{{ $task->title }}</td>
                                        </tr>
                                        <tr>
                                            <th>Description:</th>
                                            <td>{{ $task->description ?: 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Project:</th>
                                            <td>
                                                <a href="{{ route('projects.show', $task->project_id) }}">
                                                    {{ $task->project_title }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Assigned To:</th>
                                            <td>{{ $task->assigned_to }}</td>
                                        </tr>
                                        <tr>
                                            <th>Priority:</th>
                                            <td>
                                                @if($task->priority == 'high')
                                                    <span class="badge badge-danger">High</span>
                                                @elseif($task->priority == 'medium')
                                                    <span class="badge badge-warning">Medium</span>
                                                @else
                                                    <span class="badge badge-info">Low</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                @if($task->status == 'completed')
                                                    <span class="badge badge-success">Completed</span>
                                                @elseif($task->status == 'in_progress')
                                                    <span class="badge badge-primary">In Progress</span>
                                                @else
                                                    <span class="badge badge-secondary">Not Started</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Due Date:</th>
                                            <td>{{ $task->due_date ? date('M d, Y', strtotime($task->due_date)) : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At:</th>
                                            <td>{{ date('M d, Y H:i', strtotime($task->created_at)) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tags:</th>
                                            <td>
                                                @if($task->tags)
                                                    @foreach(explode(',', $task->tags) as $tag)
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
                                    <h4>Add Comment</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('tasks.comments.add', $task->id) }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <textarea class="form-control" name="content" rows="3" placeholder="Write your comment here..." required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Add Comment</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>Comments</h4>
                                </div>
                                <div class="card-body">
                                    @if(count($comments) > 0)
                                        <div class="timeline">
                                            @foreach($comments as $comment)
                                                <div class="time-label">
                                                    <span class="bg-info">{{ date('M d, Y', strtotime($comment->created_at)) }}</span>
                                                </div>
                                                <div>
                                                    <i class="fas fa-comments bg-primary"></i>
                                                    <div class="timeline-item">
                                                        <span class="time"><i class="fas fa-clock"></i> {{ date('H:i', strtotime($comment->created_at)) }}</span>
                                                        <h3 class="timeline-header"><a href="#">{{ $comment->user_name }}</a> commented</h3>
                                                        <div class="timeline-body">
                                                            {{ $comment->content }}
                                                        </div>
                                                        @if(Session::get('user')['id'] == $comment->user_id || Session::get('user')['role'] === 'admin')
                                                            <div class="timeline-footer">
                                                                <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" style="display: inline-block;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this comment?')">
                                                                        <i class="fas fa-trash"></i> Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-info">No comments yet.</div>
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