<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Session::get('user');
        
        if ($user['role'] === 'admin') {
            return $this->adminDashboard();
        } else {
            return $this->userDashboard();
        }
    }
    
    private function adminDashboard()
    {
        $totalProjects = DB::table('projects')->count();
        $totalTasks = DB::table('tasks')->count();
        $totalUsers = DB::table('users')->where('role', 'user')->count();
        $taskStatusCounts = [
            'not_started' => DB::table('tasks')->where('status', 'not_started')->count(),
            'in_progress' => DB::table('tasks')->where('status', 'in_progress')->count(),
            'completed' => DB::table('tasks')->where('status', 'completed')->count(),
        ];
        $recentProjects = DB::table('projects')
            ->select('projects.*', 'users.name as created_by')
            ->join('users', 'projects.user_id', '=', 'users.id')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $recentTasks = DB::table('tasks')
            ->select('tasks.*', 'users.name as assigned_to', 'projects.title as project_title')
            ->join('users', 'tasks.user_id', '=', 'users.id')
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->orderBy('tasks.created_at', 'desc')
            ->limit(5)
            ->get();
            
        return view('admin.dashboard', compact(
            'totalProjects', 
            'totalTasks', 
            'totalUsers', 
            'taskStatusCounts', 
            'recentProjects', 
            'recentTasks'
        ));
    }
    
    private function userDashboard()
    {
        $user = Session::get('user');
        $projects = DB::table('projects')
            ->select('projects.*')
            ->join('project_users', 'projects.id', '=', 'project_users.project_id')
            ->where('project_users.user_id', $user['id'])
            ->orderBy('created_at', 'desc')
            ->get();
        $tasks = DB::table('tasks')
            ->select('tasks.*', 'projects.title as project_title')
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->where('tasks.user_id', $user['id'])
            ->orderBy('tasks.created_at', 'desc')
            ->get();
    
        $taskStatusCounts = [
            'not_started' => DB::table('tasks')
                ->where('user_id', $user['id'])
                ->where('status', 'not_started')
                ->count(),
            'in_progress' => DB::table('tasks')
                ->where('user_id', $user['id'])
                ->where('status', 'in_progress')
                ->count(),
            'completed' => DB::table('tasks')
                ->where('user_id', $user['id'])
                ->where('status', 'completed')
                ->count(),
        ];
        
        return view('dashboard', compact('projects', 'tasks', 'taskStatusCounts'));
    }
}