@extends(session('user')['role'] === 'admin' ? 'admin.layouts.adminDash' : 'layouts.userDash')

@section(session('user')['role'] === 'admin' ? 'mainContent' : 'content')
@section('title') Edit Project - {{ $project->title }} @endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Project: {{ $project->title }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('projects.show', $project->id) }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Project
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

                    <form action="{{ route('projects.update', $project->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $project->title) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $project->description) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="tags">Tags (comma separated)</label>
                            <input type="text" class="form-control" id="tags" name="tags" value="{{ old('tags', $project->tags) }}">
                        </div>
                        <div class="form-group">
                            <label for="priority">Priority</label>
                            <select class="form-control" id="priority" name="priority" required>
                                <option value="low" {{ old('priority', $project->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority', $project->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority', $project->priority) == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="not_started" {{ old('status', $project->status) == 'not_started' ? 'selected' : '' }}>Not Started</option>
                                <option value="in_progress" {{ old('status', $project->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="due_date">Due Date</label>
                            <input type="date" class="form-control" id="due_date" name="due_date" value="{{ old('due_date', $project->due_date) }}">
                        </div>
                        <div class="form-group">
                            <label for="user_ids">Assign Users</label>
                            <select class="form-control" id="user_ids" name="user_ids[]" multiple required>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ (is_array(old('user_ids')) && in_array($user->id, old('user_ids'))) || (empty(old('user_ids')) && in_array($user->id, $assignedUserIds)) ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Hold Ctrl (or Cmd on Mac) to select multiple users</small>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Project</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection