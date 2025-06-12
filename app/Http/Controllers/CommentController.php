<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $user = Session::get('user');
        
        $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'content' => 'required|string'
        ]);
        
        $task = DB::table('tasks')->find($request->task_id);
        
        if (!$task) {
            return redirect()->back()->with('error', 'Task not found');
        }
        
        // Check if user has access to this task
        if ($user['role'] !== 'admin') {
            $hasAccess = DB::table('project_users')
                ->join('tasks', 'project_users.project_id', '=', 'tasks.project_id')
                ->where('tasks.id', $request->task_id)
                ->where('project_users.user_id', $user['id'])
                ->exists();
                
            if (!$hasAccess) {
                return redirect()->back()->with('error', 'Unauthorized access');
            }
        }
        




        DB::table('comments')->insert([
            'task_id' => $request->task_id,
            'user_id' => $user['id'],
            'content' => $request->content,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        return redirect()->back()->with('success', 'Comment added successfully');
    }

    public function destroy($id)
    {
        $user = Session::get('user');
        
        $comment = DB::table('comments')->find($id);
        
        if (!$comment) {
            return redirect()->back()->with('error', 'Comment not found');
        }
        if ($user['role'] !== 'admin' && $comment->user_id != $user['id']) {
            return redirect()->back()->with('error', 'Unauthorized access');
        }
        
        DB::table('comments')->where('id', $id)->delete();
        
        return redirect()->back()->with('success', 'Comment deleted successfully');
    }
}