@extends('layouts.userDash')

@section('title', 'Edit Task')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Task: {{ $task->title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Task
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        @if(Session::get('user')['role'] === 'admin')
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $task->title) }}" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $task->description) }}</textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="project_id">Project</label>
                                <select class="form-control" id="project_id" name="project_id" required>
                                    <option value="">Select Project</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id', $task->project_id) == $project->id ? 'selected' : '' }}>
                                            {{ $project->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="user_id">Assign To</label>
                                <select class="form-control" id="user_id" name="user_id" required>
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $task->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="tags">Tags (comma separated)</label>
                                <input type="text" class="form-control" id="tags" name="tags" value="{{ old('tags', $task->tags) }}">
                            </div>
                            <div class="form-group">
                                <label for="priority">Priority</label>
                                <select class="form-control" id="priority" name="priority" required>
                                    <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>High</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="due_date">Due Date</label>
                                <input type="date" class="form-control" id="due_date" name="due_date" value="{{ old('due_date', $task->due_date) }}">
                            </div>
                        @else
                            <div class="form-group">
                                <label>Title</label>
                                <p class="form-control-static">{{ $task->title }}</p>
                                <input type="hidden" name="title" value="{{ $task->title }}">
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <p class="form-control-static">{{ $task->description ?: 'N/A' }}</p>
                                <input type="hidden" name="description" value="{{ $task->description }}">
                            </div>
                            <div class="form-group">
                                <label>Project</label>
                                {{-- <p class="form-control-static">{{ $task->project_title }}</p> --}}
                                <input type="hidden" name="project_id" value="{{ $task->project_id }}">
                            </div>
                            <div class="form-group">
                                <label>Tags</label>
                                <p class="form-control-static">{{ $task->tags ?: 'N/A' }}</p>
                                <input type="hidden" name="tags" value="{{ $task->tags }}">
                            </div>
                            <div class="form-group">
                                <label>Priority</label>
                                <p class="form-control-static">
                                    @if($task->priority == 'high')
                                        <span class="badge badge-danger">High</span>
                                    @elseif($task->priority == 'medium')
                                        <span class="badge badge-warning">Medium</span>
                                    @else
                                        <span class="badge badge-info">Low</span>
                                    @endif
                                </p>
                                <input type="hidden" name="priority" value="{{ $task->priority }}">
                            </div>
                            <div class="form-group">
                                <label>Due Date</label>
                                   <p class="form-control-static">{{ $task->due_date ? date('M d, Y', strtotime($task->due_date)) : 'N/A' }}</p>
                                <input type="hidden" name="due_date" value="{{ $task->due_date }}">
                            </div>
                            <input type="hidden" name="user_id" value="{{ $task->user_id }}">
                        @endif
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="not_started" {{ old('status', $task->status) == 'not_started' ? 'selected' : '' }}>Not Started</option>
                                <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Task</button>
                    </form>
                    <div class="mt-4">
                        <h4>Add Comment</h4>
                        <form action="{{ route('tasks.comments.add', $task->id) }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <textarea class="form-control" name="content" rows="3" placeholder="Write your comment here..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success">Add Comment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection