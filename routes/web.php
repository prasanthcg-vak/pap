<?php

use App\Http\Controllers\CampaignsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckRolePermission;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AssetTypeController;
use App\Http\Controllers\ImageController;
// use App\Http\Controllers\ClientGroupsController;


Route::get('/', function () {
    return redirect()->route('login');
});


// Auth::routes();

// Forgot Password and Reset Password Routes
Auth::routes(['verify' => true]);


Route::middleware(['auth'])->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    //Roles
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:roles.index');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:roles.store');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:roles.edit');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:roles.update');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:roles.destroy');
    Route::get('/roles/{role}/permissions/edit', [RolePermissionController::class, 'edit'])->name('roles.permissions.edit')->middleware('permission:roles.permissions.edit');
    Route::post('/roles/{role}/permissions/update', [RolePermissionController::class, 'update'])->name('roles.permissions.update')->middleware('permission:roles.permissions.update');

    //user-roles
    Route::get('users/{user}/roles', [UserRoleController::class, 'edit'])->name('users.roles.edit')->middleware('permission:users.roles.edit');
    Route::put('users/{user}/roles', [UserRoleController::class, 'update'])->name('users.roles.update')->middleware('permission:users.roles.update');

    // Categories Routes with Individual Permissions
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index')->middleware('permission:categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store')->middleware('permission:categories.store');
    // Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit')->middleware('permission:categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update')->middleware('permission:categories.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('permission:categories.destroy');

    //asset types
    Route::get('asset-types', [AssetTypeController::class, 'index'])->name('asset-types.index')->middleware('permission:asset-types.index');
    Route::post('asset-types', [AssetTypeController::class, 'store'])->name('asset-types.store')->middleware('permission:asset-types.store');
    Route::put('asset-types/{assetType}', [AssetTypeController::class, 'update'])->name('asset-types.update')->middleware('permission:asset-types.update');
    Route::delete('asset-types/{assetType}', [AssetTypeController::class, 'destroy'])->name('asset-types.destroy')->middleware('permission:asset-types.destroy');

    //Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index')->middleware('permission:users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store')->middleware('permission:users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:users.destroy');

    // Client Groups
    Route::get('/client-groups', [ClientGroupsController::class, 'index'])->name('client-groups.index')->middleware('permission:client-groups.index');
    Route::post('/client-groups', [ClientGroupsController::class, 'store'])->name('client-groups.store')->middleware('permission:client-groups.store');
    Route::put('/client-groups/{clientGroups}', [ClientGroupsController::class, 'update'])->name('client-groups.update')->middleware('permission:client-groups.update');
    Route::delete('/client-groups/{clientGroups}', [ClientGroupsController::class, 'destroy'])->name('client-groups.destroy')->middleware('permission:client-groups.destroy');


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

    // Additional routes
    Route::get('users/get-data', [UserController::class, 'getData'])->name('users.getData'); // Get data for DataTable or similar
    Route::get('/myprofile', 'App\Http\Controllers\UserController@myprofile')->name('myprofile');
    Route::put('/profile', [UserController::class, 'updateprofile'])->name('profile.update');

    Route::get('campaigns', [CampaignsController::class, 'index'])->name('campaigns.index');
    Route::get('campaigns/create', [CampaignsController::class, 'create'])->name('campaigns.create');
    Route::post('campaigns', [CampaignsController::class, 'store'])->name('campaigns.store');
    Route::get('campaigns/{id}', [CampaignsController::class, 'show'])->name('campaigns.show');
    Route::get('campaigns/{id}/edit', [CampaignsController::class, 'edit'])->name('campaigns.edit');
    Route::put('campaigns/{id}', [CampaignsController::class, 'update'])->name('campaigns.update');
    Route::delete('campaigns/{id}', [CampaignsController::class, 'destroy'])->name('campaigns.destroy');
    Route::get('/campaigns/assets/{id}', [CampaignsController::class, 'assets_view'])->name('campaigns.assets_view');

 
    Route::get('/images', [ImageController::class, 'index'])->name('images.index');
    Route::get('/images/create', [ImageController::class, 'create'])->name('images.create');
    Route::post('/images', [ImageController::class, 'store'])->name('images.store');
    Route::delete('/images', [ImageController::class, 'destroy'])->name('images.delete');
    Route::get('/imageslist', [ImageController::class, 'listImages']);

     // Show the form to create a new client-partner relationship
     Route::get('clientpartner/create', [UserController::class, 'create_client_partner'])->name('clientpartner.create');
     // Store the newly created client-partner relationship
     Route::post('clientpartner', [UserController::class, 'store_client_partner'])->name('clientpartner.store');
     // Show the form to edit a specific client-partner relationship
     Route::get('clientpartner/{id}/edit', [UserController::class, 'edit_client_partner'])->name('clientpartner.edit');
     // Update an existing client-partner relationship
     Route::put('clientpartner/{id}', [UserController::class, 'update_client_partner'])->name('clientpartner.update');
     // Delete a specific client-partner relationship
     Route::delete('clientpartner/{id}', [UserController::class, 'destroy_client_partner'])->name('clientpartner.destroy');
 
});

