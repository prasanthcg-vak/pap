<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\TasksController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('tasks', [TasksController::class, 'index'])->name('tasks.index');           // Display the task listing
Route::get('tasks/create', [TasksController::class, 'create'])->name('tasks.create');   // Show the form to create a new task
Route::post('tasks', [TasksController::class, 'store'])->name('tasks.store');           // Store a new task
Route::get('tasks/{task}/edit', [TasksController::class, 'edit'])->name('tasks.edit');  // Show the form to edit a task
Route::put('tasks/{task}', [TasksController::class, 'update'])->name('tasks.update');   // Update a specific task
Route::get('tasks/{task}', [TasksController::class, 'show'])->name('tasks.show');       // Show details for a specific task
Route::delete('tasks/{id}', [TasksController::class, 'destroy'])->name('tasks.destroy'); // Soft delete a specific task

// Display all comments for a specific task
Route::get('comments/task/{taskId}', [CommentController::class, 'index'])->name('comments.index');

// Store a new comment or reply
Route::post('comments', [CommentController::class, 'store'])->name('comments.store');

// Delete a comment
Route::delete('comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy');
