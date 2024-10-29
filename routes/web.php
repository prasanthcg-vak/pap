<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckRolePermission;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TasksController;


Route::get('/', function () {
    return redirect()->route('login');
});


Auth::routes();


Route::middleware(['auth'])->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

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

    //Tasks
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


    Route::get('users', [UserController::class, 'index'])->name('users.index');          // Display a listing of the users
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');  // Show the form for creating a new user
    Route::post('users', [UserController::class, 'store'])->name('users.store');          // Store a newly created user
    Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');   // Show the form for editing the user
    Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');    // Update the specified user
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');// Remove the specified user

    // Additional routes
    Route::get('users/get-data', [UserController::class, 'getData'])->name('users.getData'); // Get data for DataTable or similar
    Route::get('users/my-profile', [UserController::class, 'myprofile'])->name('users.myprofile'); // Display the logged-in user's profile
    Route::post('users/update-profile', [UserController::class, 'updateprofile'])->name('users.updateprofile'); // Update profile

});

