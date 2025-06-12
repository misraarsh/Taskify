@extends('layouts.userDash')

@section('title', 'Projects')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Projects</h3>
                    @if(Session::get('user')['role'] === 'admin')
                    <div class="card-tools">
                        <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Create New Project
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

                    @if(count($projects) > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Due Date</th>
                                        <th>Created By</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projects as $project)
                                        <tr>
                                            <td>{{ $project->title }}</td>
                                            <td>
                                                @if($project->priority == 'high')
                                                    <span class="badge badge-danger">High</span>
                                                @elseif($project->priority == 'medium')
                                                    <span class="badge badge-warning">Medium</span>
                                                @else
                                                    <span class="badge badge-info">Low</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($project->status == 'completed')
                                                    <span class="badge badge-success">Completed</span>
                                                @elseif($project->status == 'in_progress')
                                                    <span class="badge badge-primary">In Progress</span>
                                                @else
                                                    <span class="badge badge-secondary">Not Started</span>
                                                @endif
                                            </td>
                                            <td>{{ $project->due_date ? date('M d, Y', strtotime($project->due_date)) : 'N/A' }}</td>
                                            <td>{{ $project->created_by }}</td>
                                            <td>
                                                <a href="{{ route('projects.show', $project->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                                @if(Session::get('user')['role'] === 'admin')
                                                    <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display: inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this project?')">
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
                        <div class="alert alert-info">No projects found.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection