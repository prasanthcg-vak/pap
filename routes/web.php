<?php

use App\Http\Controllers\CampaignsController;
use App\Http\Controllers\GroupController;
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
use App\Http\Controllers\LibraryController;

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientGroupController;
// use App\Http\Controllers\ClientUserController;


Route::get('/', function () {
    return redirect()->route('login');
});


// Auth::routes();

// Forgot Password and Reset Password Routes
Auth::routes(['verify' => true]);


Route::middleware(['auth'])->group(function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [ImageController::class, 'listCampaignImages'])->name('dashboard');

    //Roles
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:roles.index');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:roles.store');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:roles.edit');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:roles.update');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:roles.destroy');
    Route::get('roles/{role}/permissions/edit', [RolePermissionController::class, 'edit'])->name('roles.permissions.edit')->middleware('permission:roles.permissions.edit');
    Route::post('roles/{role}/permissions/update', [RolePermissionController::class, 'update'])->name('roles.permissions.update')->middleware('permission:roles.permissions.update');

    // User-Roles
    Route::get('users/{user}/roles', [UserRoleController::class, 'edit'])->name('users.roles.edit')->middleware('permission:users.roles.edit');
    Route::put('users/{user}/roles', [UserRoleController::class, 'update'])->name('users.roles.update')->middleware('permission:users.roles.update');

    // Categories
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index')->middleware('permission:categories.index');
    Route::post('categories', [CategoryController::class, 'store'])->name('categories.store')->middleware('permission:categories.store');
    Route::put('categories/{id}', [CategoryController::class, 'update'])->name('categories.update')->middleware('permission:categories.update');
    Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy')->middleware('permission:categories.destroy');

    // Asset Types
    Route::get('asset-types', [AssetTypeController::class, 'index'])->name('asset-types.index')->middleware('permission:asset-types.index');
    Route::post('asset-types', [AssetTypeController::class, 'store'])->name('asset-types.store')->middleware('permission:asset-types.store');
    Route::put('asset-types/{assetType}', [AssetTypeController::class, 'update'])->name('asset-types.update')->middleware('permission:asset-types.update');
    Route::delete('asset-types/{assetType}', [AssetTypeController::class, 'destroy'])->name('asset-types.destroy')->middleware('permission:asset-types.destroy');

    // Users
    Route::get('users', [UserController::class, 'index'])->name('users.index')->middleware('permission:users.index');
    Route::post('users', [UserController::class, 'store'])->name('users.store')->middleware('permission:users.store');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:users.destroy');

    // Tasks
    Route::get('tasks', [TasksController::class, 'index'])->name('tasks.index')->middleware('permission:tasks.index');
    Route::get('tasks/create', [TasksController::class, 'create'])->name('tasks.create')->middleware('permission:tasks.create');
    Route::post('tasks', [TasksController::class, 'store'])->name('tasks.store')->middleware('permission:tasks.store');
    Route::get('tasks/{task}/edit', [TasksController::class, 'edit'])->name('tasks.edit')->middleware('permission:tasks.edit');
    Route::put('tasks/{task}', [TasksController::class, 'update'])->name('tasks.update')->middleware('permission:tasks.update');
    Route::get('tasks/{task}', [TasksController::class, 'show'])->name('tasks.show')->middleware('permission:tasks.show');
    Route::delete('tasks/{id}', [TasksController::class, 'destroy'])->name('tasks.destroy')->middleware('permission:tasks.destroy');

    // Comments
    Route::get('comments/task/{taskId}', [CommentController::class, 'index'])->name('comments.index')->middleware('permission:comments.index');
    Route::post('comments', [CommentController::class, 'store'])->name('comments.store')->middleware('permission:comments.store');
    Route::delete('comments/{id}', [CommentController::class, 'destroy'])->name('comments.destroy')->middleware('permission:comments.destroy');

    // Campaigns
    Route::get('campaigns', [CampaignsController::class, 'index'])->name('campaigns.index')->middleware('permission:campaigns.index');
    Route::get('campaigns/create', [CampaignsController::class, 'create'])->name('campaigns.create')->middleware('permission:campaigns.create');
    Route::post('campaigns', [CampaignsController::class, 'store'])->name('campaigns.store')->middleware('permission:campaigns.store');
    Route::get('campaigns/{id}', [CampaignsController::class, 'show'])->name('campaigns.show')->middleware('permission:campaigns.show');
    Route::get('campaigns/{id}/assets', [CampaignsController::class, 'assetsView'])->name('campaigns.assetsview');

    Route::put('campaigns/{id}', [CampaignsController::class, 'update'])->name('campaigns.update')->middleware('permission:campaigns.update');
    Route::delete('campaigns/{id}', [CampaignsController::class, 'destroy'])->name('campaigns.destroy')->middleware('permission:campaigns.destroy');

    // Groups
    Route::get('groups', [GroupController::class, 'index'])->name('groups.index')->middleware('permission:groups.index');
    Route::get('groups/create', [GroupController::class, 'create'])->name('groups.create')->middleware('permission:groups.create');
    Route::post('groups', [GroupController::class, 'store'])->name('groups.store')->middleware('permission:groups.store');
    Route::get('groups/{group}/edit', [GroupController::class, 'edit'])->name('groups.edit')->middleware('permission:groups.edit');
    Route::put('groups/{group}', [GroupController::class, 'update'])->name('groups.update')->middleware('permission:groups.update');
    Route::delete('groups/{group}', [GroupController::class, 'destroy'])->name('groups.destroy')->middleware('permission:groups.destroy');
    Route::get('/library', [LibraryController::class, 'index'])->name('library.index')->middleware('permission:library.index');

    Route::get('clientpartner/create', [UserController::class, 'create_client_partner'])->name('clientpartner.create')->middleware('permission:clientpartner.create');
    Route::post('clientpartner', [UserController::class, 'store_client_partner'])->name('clientpartner.store')->middleware('permission:clientpartner.store');
    Route::get('clientpartner/{id}/edit', [UserController::class, 'edit_client_partner'])->name('clientpartner.edit')->middleware('permission:clientpartner.edit');
    Route::put('clientpartner/{id}', [UserController::class, 'update_client_partner'])->name('clientpartner.update')->middleware('permission:clientpartner.update');
    Route::delete('clientpartner/{id}', [UserController::class, 'destroy_client_partner'])->name('clientpartner.destroy')->middleware('permission:clientpartner.destroy');
    Route::get('/myprofile', [UserController::class, 'myprofile'])->name('myprofile')->middleware('permission:myprofile.view');
    Route::put('/profile', [UserController::class, 'updateprofile'])->name('profile.update')->middleware('permission:myprofile.update');

    Route::put('/password/update', [UserController::class, 'updatepassword'])->name('password.update');

    // Client Routes
    Route::get('/clients', [ClientController::class, 'index'])->name('clients.index')->middleware('permission:clients.index');
    Route::post('/clients', [ClientController::class, 'store'])->name('clients.store')->middleware('permission:clients.store');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('clients.update')->middleware('permission:clients.update');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy')->middleware('permission:clients.destroy');

    // Client Groups
    Route::get('/client-groups', [ClientGroupController::class, 'index'])->name('client-groups.index')->middleware('permission:client-groups.index');
    Route::post('/client-groups', [ClientGroupController::class, 'store'])->name('client-groups.store')->middleware('permission:client-groups.store');
    Route::put('/client-groups/{clientGroup}', [ClientGroupController::class, 'update'])->name('client-groups.update')->middleware('permission:client-groups.update');
    Route::delete('/client-groups/{clientGroup}', [ClientGroupController::class, 'destroy'])->name('client-groups.destroy')->middleware('permission:client-groups.destroy');

    // Images
    Route::get('/images', [ImageController::class, 'index'])->name('images.index');
    Route::get('/list-all-images', [ImageController::class, 'list_all_images'])->name('images.list_all_images');
    Route::get('/images/create', [ImageController::class, 'create'])->name('images.create');
    Route::post('/images', [ImageController::class, 'store'])->name('images.store');
    Route::delete('/images', [ImageController::class, 'destroy'])->name('images.delete');

    Route::get('/get-client-groups/{clientId}', [CampaignsController::class, 'getClientGroups']);
Route::get('/get-partners/{groupId}', [CampaignsController::class, 'getPartners']);
Route::get('/get-partners-by-campaign/{campaignId}', [TasksController::class, 'getPartnersByCampaign']);


});

// Clear application cache
Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    return 'Application cache has been cleared';
});

// Clear route cache
Route::get('/route-cache', function () {
    Artisan::call('route:cache');
    return 'Routes cache has been cleared';
});

// Clear config cache
Route::get('/config-cache', function () {
    Artisan::call('config:cache');
    return 'Config cache has been cleared';
});

// Clear view cache
Route::get('/view-clear', function () {
    Artisan::call('view:clear');
    return 'View cache has been cleared';
});

// Optimize application
Route::get('/optimize-clear', function () {
    Artisan::call('optimize:clear');
    return 'Optimization has been cleared';
});