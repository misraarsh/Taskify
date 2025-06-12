<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TaskController extends Controller
{
    public function index()
    {
        $user = Session::get('user');
        if ($user['role'] === 'admin'){
            $tasks = DB::table('tasks')
                ->select('tasks.*', 'users.name as assigned_to', 'projects.title as project_title')
                ->join('users', 'tasks.user_id', '=', 'users.id')
                ->join('projects', 'tasks.project_id', '=', 'projects.id')
                ->orderBy('tasks.created_at', 'desc')
                ->get();
        } 
        else{
            $tasks = DB::table('tasks')
                ->select('tasks.*', 'users.name as assigned_to', 'projects.title as project_title')
                ->join('users', 'tasks.user_id', '=', 'users.id')
                ->join('projects', 'tasks.project_id', '=', 'projects.id')
                ->where('tasks.user_id', $user['id'])
                ->orderBy('tasks.created_at', 'desc')
                ->get();
        }
        
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        if (Session::get('user')['role'] !== 'admin') {
            return redirect()->route('tasks.index')->with('error', 'Unauthorized access');
        }
        $projects = DB::table('projects')->get();
        $users = DB::table('users')->where('role', 'user')->get();
        
        return view('tasks.create', compact('projects', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:not_started,in_progress,completed',
            'due_date' => 'nullable|date',
        ]);
        $isUserAssigned = DB::table('project_users')
            ->where('project_id', $request->project_id)
            ->where('user_id', $request->user_id)
            ->exists();
        if (!$isUserAssigned) {
            DB::table('project_users')->insert([
                'project_id' => $request->project_id,
                'user_id' => $request->user_id,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        $taskId = DB::table('tasks')->insertGetId([
            'title' => $request->title,
            'description' => $request->description,
            'tags' => $request->tags,
            'project_id' => $request->project_id,
            'user_id' => $request->user_id,
            'priority' => $request->priority,
            'status' => $request->status,
            'due_date' => $request->due_date,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return redirect()->route('tasks.index')->with('success', 'Task created successfully');
    }
    public function show($id)
    {
        $user = Session::get('user');
        $task = DB::table('tasks')
            ->select('tasks.*', 'users.name as assigned_to', 'projects.title as project_title')
            ->join('users', 'tasks.user_id', '=', 'users.id')
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->where('tasks.id', $id)
            ->first();
            
        if (!$task) {
            return redirect()->route('tasks.index')->with('error', 'Task not found');
        }
        if ($user['role'] !== 'admin' && $task->user_id != $user['id']) {
            return redirect()->route('tasks.index')->with('error', 'Unauthorized access');
        }
        
        $comments = DB::table('comments')
            ->select('comments.*', 'users.name as user_name')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->where('comments.task_id', $id)
            ->orderBy('comments.created_at', 'desc')
            ->get();
            
        return view('tasks.show', compact('task', 'comments'));
    }

    public function edit($id)
    {
        $user = Session::get('user');
        
        $task = DB::table('tasks')->find($id);
        
        if (!$task) {
            return redirect()->route('tasks.index')->with('error', 'Task not found');
        }
        
      
        if ($user['role'] !== 'admin' && $task->user_id != $user['id']) {
            return redirect()->route('tasks.index')->with('error', 'Unauthorized access');
        }
        
        $projects = DB::table('projects')->get();
        $users = DB::table('users')->where('role', 'user')->get();
        
        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    public function update(Request $request, $id)
    {
        $user = Session::get('user');
        
        $task = DB::table('tasks')->find($id);
        
        if (!$task) {
            return redirect()->route('tasks.index')->with('error', 'Task not found');
        }
        
      
        if ($user['role'] !== 'admin' && $task->user_id != $user['id']) {
            return redirect()->route('tasks.index')->with('error', 'Unauthorized access');
        }
        
       
        if ($user['role'] === 'admin') {
            
            $validationRules = [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'project_id' => 'required|exists:projects,id',
                'user_id' => 'required|exists:users,id',
                'priority' => 'required|in:low,medium,high',
                'status' => 'required|in:not_started,in_progress,completed',
                'due_date' => 'nullable|date',
                'tags' => 'nullable|string',
            ];
            
            $request->validate($validationRules);
            
            $updateData = [
                'title' => $request->title,
                'description' => $request->description,
                'tags' => $request->tags,
                'priority' => $request->priority,
                'status' => $request->status,
                'due_date' => $request->due_date,
                'project_id' => $request->project_id,
                'user_id' => $request->user_id,
                'updated_at' => now()
            ];
            
     
            $isUserAssigned = DB::table('project_users')
                ->where('project_id', $request->project_id)
                ->where('user_id', $request->user_id)
                ->exists();
                
            if (!$isUserAssigned) {
              
                DB::table('project_users')->insert([
                    'project_id' => $request->project_id,
                    'user_id' => $request->user_id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        } else {
            
            $validationRules = [
                'status' => 'required|in:not_started,in_progress,completed',
                'title' => 'required',
                'project_id' => 'required',
                'user_id' => 'required',
            ];
            
            $request->validate($validationRules);
            $updateData = [
                'status' => $request->status,
                'updated_at' => now()
            ];
        }

        DB::table('tasks')
            ->where('id', $id)
            ->update($updateData);
            
        return redirect()->route('tasks.show', $id)->with('success', 'Task updated successfully');
    }

    public function destroy($id)
    {

        if (Session::get('user')['role'] !== 'admin') {
            return redirect()->route('tasks.index')->with('error', 'Unauthorized access');
        }
        
        DB::table('tasks')->where('id', $id)->delete();
        
        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully');
    }
    
    public function addComment(Request $request, $id)
    {
        $user = Session::get('user');
        
        $task = DB::table('tasks')->find($id);
        
        if (!$task) {
            return redirect()->route('tasks.index')->with('error', 'Task not found');
        }
        
        if ($user['role'] !== 'admin') {
            $hasAccess = DB::table('project_users')
                ->join('tasks', 'project_users.project_id', '=', 'tasks.project_id')
                ->where('tasks.id', $id)
                ->where('project_users.user_id', $user['id'])
                ->exists();
                
            if (!$hasAccess) {
                return redirect()->route('tasks.index')->with('error', 'Unauthorized access');
            }
        }
        
        $request->validate([
            'content' => 'required|string'
        ]);
        
        DB::table('comments')->insert([
            'task_id' => $id,
            'user_id' => $user['id'],
            'content' => $request->content,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return redirect()->route('tasks.show', $id)->with('success', 'Comment added successfully');
    }
}