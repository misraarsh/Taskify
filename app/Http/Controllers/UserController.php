<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        if (Session::get('user')['role'] !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }
        
        $users = DB::table('users')
            ->select('users.*', 
                DB::raw('(SELECT COUNT(*) FROM tasks WHERE tasks.user_id = users.id) as task_count'),
                DB::raw('(SELECT COUNT(*) FROM project_users WHERE project_users.user_id = users.id) as project_count')
            )
            ->orderBy('users.name')
            ->get();
            
        return view('users.index', compact('users'));
    }
    
    public function create()
    {
        if (Session::get('user')['role'] !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }
        
        return view('users.create');
    }
    
    public function store(Request $request)
    {
        if (Session::get('user')['role'] !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,user',
        ]);
        
        $userId = DB::table('users')->insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        return redirect()->route('users.index')->with('success', 'User created successfully');
    }
    
    public function edit($id)
    {
        if (Session::get('user')['role'] !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }
        
        $user = DB::table('users')->find($id);
        
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found');
        }
        
        return view('users.edit', compact('user'));
    }
    
    public function update(Request $request, $id)
    {
        if (Session::get('user')['role'] !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }
        
        $user = DB::table('users')->find($id);
        
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'role' => 'required|in:admin,user',
        ]);
        
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'updated_at' => now(),
        ];
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:6',
            ]);
            
            $updateData['password'] = Hash::make($request->password);
        }
        
        DB::table('users')
            ->where('id', $id)
            ->update($updateData);
            
        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }
    
    public function destroy($id)
    {
        if (Session::get('user')['role'] !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }
        
        $user = DB::table('users')->find($id);
        
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found');
        }
        $taskCount = DB::table('tasks')->where('user_id', $id)->count();
        
        if ($taskCount > 0) {
            return redirect()->route('users.index')->with('error', 'Cannot delete user with assigned tasks. Reassign tasks first.');
        }
        DB::table('project_users')->where('user_id', $id)->delete();
        DB::table('users')->where('id', $id)->delete();
        
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
    
    public function assignProjects($id)
    {
        if (Session::get('user')['role'] !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }
        
        $user = DB::table('users')->find($id);
        
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found');
        }
        
        $assignedProjects = DB::table('project_users')
            ->where('user_id', $id)
            ->pluck('project_id')
            ->toArray();
            
        $projects = DB::table('projects')
            ->select('id', 'title')
            ->orderBy('title')
            ->get();
            
        return view('users.assign_projects', compact('user', 'projects', 'assignedProjects'));
    }
    
    public function updateAssignments(Request $request, $id)
    {
        if (Session::get('user')['role'] !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Unauthorized access');
        }
        
        $user = DB::table('users')->find($id);
        
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found');
        }
        
        DB::table('project_users')->where('user_id', $id)->delete();
        if ($request->has('projects')) {
            $now = now();
            
            foreach ($request->projects as $projectId) {
                DB::table('project_users')->insert([
                    'project_id' => $projectId,
                    'user_id' => $id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
        
        return redirect()->route('users.index')->with('success', 'Project assignments updated successfully');
    }
}