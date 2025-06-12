@extends('admin.layouts.adminDash')

@section('title', 'Assign Projects')

@section('mainContent')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Assign Projects to {{ $user->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('users.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Users
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
                    
                    <form action="{{ route('users.assignments.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label>Select Projects</label>
                            <div class="project-list">
                                @foreach($projects as $project)
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="project-{{ $project->id }}" 
                                               name="projects[]" 
                                               value="{{ $project->id }}"
                                               {{ in_array($project->id, $assignedProjects) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="project-{{ $project->id }}">
                                            {{ $project->title }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Update Project Assignments</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .project-list {
        max-height: 400px;
        overflow-y: auto;
        border: 1px solid #ddd;
        padding: 15px;
        border-radius: 5px;
    }
    
    .custom-control {
        margin-bottom: 10px;
    }
</style>
@endpush