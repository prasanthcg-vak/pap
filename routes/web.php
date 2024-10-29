<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\TasksController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckRolePermission;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\RoleController::class, 'index'])->name('home');

    //Roles
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:roles.index');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:roles.store');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create')->middleware('permission:roles.create');
    Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show')->middleware('permission:roles.show');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:roles.update');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:roles.destroy');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:roles.edit');

    //permission
    Route::get('roles/{role}/permissions', [RolePermissionController::class, 'edit'])->name('roles.permissions.edit')->middleware('permission:roles.permissions.edit');
    Route::put('roles/{role}/permissions', [RolePermissionController::class, 'update'])->name('roles.permissions.update')->middleware('permission:roles.permissions.update');

    //user-roles
    Route::get('users/{user}/roles', [UserRoleController::class, 'edit'])->name('users.roles.edit')->middleware('permission:users.roles.edit');
    Route::put('users/{user}/roles', [UserRoleController::class, 'update'])->name('users.roles.update')->middleware('permission:users.roles.update');

    Route::get('users', [UserController::class, 'index'])->name('users.index')->middleware('permission:users.index');
    Route::post('users', [UserController::class, 'store'])->name('users.store')->middleware('permission:users.store');
    
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
});

