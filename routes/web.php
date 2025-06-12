<?php

use App\Http\Middleware\admincheck;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Signup;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\loginCheck;

Route::get('/', function () {
    return view('index');
})->name('home');

Route::get('/register', [Signup::class, 'showRegister'])->name('register');
Route::post('/register', [Signup::class, 'register']);

Route::get('/login', [Signup::class, 'showLogin'])->name('login');
Route::post('/login', [Signup::class, 'login']);

Route::get('/logout', [Signup::class, 'logout'])->name('logout');




Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(loginCheck::class);
Route::get('/adminDashboard', [DashboardController::class, 'index'])->name('adminDash')->middleware(admincheck::class);

Route::middleware(loginCheck::class)->group(function () {
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create')->middleware(admincheck::class);
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store')->middleware(admincheck::class);
    Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{id}/edit', [ProjectController::class, 'edit'])->name('projects.edit')->middleware(admincheck::class);
    Route::put('/projects/{id}', [ProjectController::class, 'update'])->name('projects.update')->middleware(admincheck::class);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy')->middleware(admincheck::class);
});



Route::middleware(loginCheck::class)->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create')->middleware(admincheck::class);
    Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store')->middleware(admincheck::class);
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy')->middleware(admincheck::class);
    Route::post('/tasks/{id}/comments', [TaskController::class, 'addComment'])->name('tasks.comments.add');
});

Route::middleware(loginCheck::class)->group(function () {
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
});


Route::middleware([loginCheck::class, admincheck::class])->group(function () {

    Route::controller(UserController::class)->prefix('users')->name('users.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::get('/{id}/assign', 'assignProjects')->name('assign');
        Route::put('/{id}/assignments', 'updateAssignments')->name('assignments.update');
    });

});


Route::fallback(function () {
    return "<h1> Page Not Found</h1>"; 
});
