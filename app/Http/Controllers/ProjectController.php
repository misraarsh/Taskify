<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProjectController extends Controller
{
    public function index()
    {
        $user = Session::get('user');
        
        
        if ($user['role'] === 'admin') {
            $projects = DB::table('projects')
                ->select('projects.*', 'users.name as created_by')
                ->join('users', 'projects.user_id', '=', 'users.id')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            
            $projects = DB::table('projects')
                ->select('projects.*', 'users.name as created_by')
                ->join('users', 'projects.user_id', '=', 'users.id')
                ->join('project_users', 'projects.id', '=', 'project_users.project_id')
                ->where('project_users.user_id', $user['id'])
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
       
        if (Session::get('user')['role'] !== 'admin') {
            return redirect()->route('projects.index')->with('error', 'Unauthorized access');
        }
        
        $users = DB::table('users')->where('role', 'user')->get();
        return view('projects.create', compact('users'));
    }

    public function store(Request $request)
    {
      
        if (Session::get('user')['role'] !== 'admin') {
            return redirect()->route('projects.index')->with('error', 'Unauthorized access');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:not_started,in_progress,completed',
            'due_date' => 'nullable|date',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);
        
        $user = Session::get('user');
        
       
        $projectId = DB::table('projects')->insertGetId([
            'title' => $request->title,
            'description' => $request->description,
            'tags' => $request->tags,
            'user_id' => $user['id'],
            'priority' => $request->priority,
            'status' => $request->status,
            'due_date' => $request->due_date,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
       
        foreach ($request->user_ids as $userId) {
            DB::table('project_users')->insert([
                'project_id' => $projectId,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        return redirect()->route('projects.index')->with('success', 'Project created successfully');
    }

    public function show($id)
    {
        $user = Session::get('user');
        if ($user['role'] !== 'admin') {
            $hasAccess = DB::table('project_users')
                ->where('project_id', $id)
                ->where('user_id', $user['id'])
                ->exists();
                
            if (!$hasAccess) {
                return redirect()->route('projects.index')->with('error', 'Unauthorized access');
            }
        }
        
        $project = DB::table('projects')
            ->select('projects.*', 'users.name as created_by')
            ->join('users', 'projects.user_id', '=', 'users.id')
            ->where('projects.id', $id)
            ->first();
            
        if (!$project) {
            return redirect()->route('projects.index')->with('error', 'Project not found');
        }
        
        $assignedUsers = DB::table('users')
            ->join('project_users', 'users.id', '=', 'project_users.user_id')
            ->where('project_users.project_id', $id)
            ->select('users.id', 'users.name')
            ->get();
            
        //how many tasks are there in thus project with the user id of the current logged in user
        $tasks = DB::table('tasks')
            ->select('tasks.*', 'users.name as assigned_to')
        ->join('users', 'tasks.user_id', '=', 'users.id')
        ->where('tasks.project_id', $id)
        ->orderBy('tasks.created_at', 'desc')
            ->get();
            
        return view('projects.show', compact('project', 'assignedUsers', 'tasks'));
    }

    public function edit($id)
    {
        if (Session::get('user')['role'] !== 'admin') {
            return redirect()->route('projects.index')->with('error', 'Unauthorized access');
        }
        
        $project = DB::table('projects')->find($id);
        
        if (!$project) {
            return redirect()->route('projects.index')->with('error', 'Project not found');
        }
        
        $users = DB::table('users')->where('role', 'user')->get();
        
        //give all user id assigned to this project
        $assignedUserIds = DB::table('project_users')
            ->where('project_id', $id)
            ->pluck('user_id')//give array of only user ids
            ->toArray(); 
            
        return view('projects.edit', compact('project', 'users', 'assignedUserIds'));
    }

    public function update(Request $request, $id)
    {

        if (Session::get('user')['role'] !== 'admin') {
            return redirect()->route('projects.index')->with('error', 'Unauthorized access');
        }
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:not_started,in_progress,completed',
            'due_date' => 'nullable|date',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);
        
        DB::table('projects')
            ->where('id', $id)
            ->update([
                'title' => $request->title,
                'description' => $request->description,
                'tags' => $request->tags,
                'priority' => $request->priority,
                'status' => $request->status,
                'due_date' => $request->due_date,
                'updated_at' => now()
            ]);
            
        DB::table('project_users')->where('project_id', $id)->delete(); //clear all users and again assign them to the project
        
        foreach ($request->user_ids as $userId) {
            DB::table('project_users')->insert([
                'project_id' => $id,
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        return redirect()->route('projects.show', $id)->with('success', 'Project updated successfully');
    }

    public function destroy($id)
    {
        if (Session::get('user')['role'] !== 'admin') {
            return redirect()->route('projects.index')->with('error', 'Unauthorized access');
        }
        
        DB::table('projects')->where('id', $id)->delete();
        
        return redirect()->route('projects.index')->with('success', 'Project deleted successfully');
    }
}