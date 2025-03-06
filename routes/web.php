<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    DashboardController,
    OfferController,
    CompanyController,
    AgencyController,
    UserController,
    SettingController,
    StatusController,
    BackupController
};
use App\Http\Controllers\Admin\{
    RoleController,
    ModuleGeneratorController,
    ModuleController,
    CommentController,
    ResourceDefinitionController,
    ActivityController
};
use Illuminate\Http\Request;

// Ana Sayfa
Route::redirect('/', '/dashboard');

// Kimlik Doğrulama
Auth::routes([
    'register' => false,
    'reset' => true,
    'verify' => false,
]);

// CSRF Token Route
Route::get('/csrf-token', function () {
    return response()->json(['token' => csrf_token()]);
});

// Kimlik Doğrulaması Gereken Rotalar
Route::middleware(['auth', 'active.user'])->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');
    Route::get('/dashboard/offers/{offer}', [DashboardController::class, 'getOffer'])->name('dashboard.offers.show');
    Route::get('/dashboard/files/{file}/download', [DashboardController::class, 'downloadFile'])->name('dashboard.files.download');
    
    // Offer Routes
    Route::prefix('offers')->name('offers.')->group(function () {
        Route::get('/', [OfferController::class, 'index'])->name('index');
        Route::get('/create', [OfferController::class, 'create'])->name('create');
        Route::post('/', [OfferController::class, 'store'])->name('store');
        Route::get('/{offer}', [OfferController::class, 'show'])->name('show');
        Route::get('/{offer}/edit', [OfferController::class, 'edit'])->name('edit');
        Route::put('/{offer}', [OfferController::class, 'update'])->name('update');
        Route::delete('/{offer}', [OfferController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [OfferController::class, 'bulkDelete'])->name('bulk-delete');
        Route::delete('/files/{file}', [OfferController::class, 'deleteFile'])->name('delete-file');
        Route::get('/files/{file}/download', [OfferController::class, 'downloadFile'])->name('download-file');
    });
    
    // Company Routes
    Route::prefix('companies')->name('companies.')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('index');
        Route::get('/create', [CompanyController::class, 'create'])->name('create');
        Route::post('/', [CompanyController::class, 'store'])->name('store');
        Route::get('/{company}', [CompanyController::class, 'show'])->name('show');
        Route::get('/{company}/edit', [CompanyController::class, 'edit'])->name('edit');
        Route::put('/{company}', [CompanyController::class, 'update'])->name('update');
        Route::delete('/{company}', [CompanyController::class, 'destroy'])->name('destroy');
        Route::post('/{company}/status', [CompanyController::class, 'updateStatus'])->name('update-status');
        Route::get('/search/query', [CompanyController::class, 'search'])->name('search');
    });
    
    // Agency Routes
    Route::prefix('agencies')->name('agencies.')->group(function () {
        Route::get('/', [AgencyController::class, 'index'])->name('index');
        Route::get('/create', [AgencyController::class, 'create'])->name('create');
        Route::post('/', [AgencyController::class, 'store'])->name('store');
        Route::get('/{agency}', [AgencyController::class, 'show'])->name('show');
        Route::get('/{agency}/edit', [AgencyController::class, 'edit'])->name('edit');
        Route::put('/{agency}', [AgencyController::class, 'update'])->name('update');
        Route::delete('/{agency}', [AgencyController::class, 'destroy'])->name('destroy');
        Route::post('/{agency}/status', [AgencyController::class, 'updateStatus'])->name('update-status');
        Route::put('/{agency}/toggle-status', [AgencyController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/search/query', [AgencyController::class, 'search'])->name('search');
    });
    
    // Modal Routes
    Route::get('/modals/add-new-form', function(Request $request) {
        return view('components.add-new-form', [
            'url' => $request->input('url'),
            'title' => $request->input('title')
        ]);
    })->name('modals.add-new-form');
});

// Admin Routes
Route::middleware(['auth', 'role:admin,super-admin'])->prefix('admin')->name('admin.')->group(function () {
    // User Management
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
    
    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    
    // Status Management
    Route::prefix('settings/statuses')->name('settings.statuses.')->group(function () {
        Route::get('/', [StatusController::class, 'index'])->name('index');
        Route::post('/', [StatusController::class, 'store'])->name('store');
        Route::put('/{status}', [StatusController::class, 'update'])->name('update');
        Route::delete('/{status}', [StatusController::class, 'destroy'])->name('destroy');
        Route::put('/{status}/toggle', [StatusController::class, 'toggle'])->name('toggle');
    });
    
    // Backup Management
    Route::prefix('backups')->name('backups.')->group(function () {
        Route::get('/', [BackupController::class, 'index'])->name('index');
        Route::get('/create', [BackupController::class, 'create'])->name('create');
        Route::post('/', [BackupController::class, 'store'])->name('store');
        Route::get('/progress', [BackupController::class, 'progress'])->name('progress');
        Route::post('/cancel', [BackupController::class, 'cancel'])->name('cancel');
        Route::get('/download/{backup}', [BackupController::class, 'download'])->name('download');
        Route::delete('/{backup}', [BackupController::class, 'destroy'])->name('destroy');
    });
    
    // Role Management
    Route::resource('roles', RoleController::class)->middleware('role:super-admin');
    
    // Module Management
    Route::prefix('modules')->name('modules.')->group(function () {
        Route::get('/', [ModuleController::class, 'index'])->name('index');
        Route::delete('/{module}', [ModuleController::class, 'destroy'])->name('destroy');
        Route::post('/{module}/structure', [ModuleController::class, 'updateStructure'])->name('update.structure');
    });
    
    // Module Generator
    Route::prefix('module-generator')->name('module-generator.')->group(function () {
        Route::get('/', [ModuleGeneratorController::class, 'index'])->name('index');
        Route::post('/generate', [ModuleGeneratorController::class, 'generate'])->name('generate');
    });
    
    // Comment Management
    Route::prefix('comments')->name('comments.')->group(function () {
        Route::get('/', [CommentController::class, 'index'])->name('index');
        Route::get('/create', [CommentController::class, 'create'])->name('create');
        Route::post('/', [CommentController::class, 'store'])->name('store');
        Route::get('/{comment}', [CommentController::class, 'show'])->name('show');
        Route::get('/{comment}/edit', [CommentController::class, 'edit'])->name('edit');
        Route::put('/{comment}', [CommentController::class, 'update'])->name('update');
        Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [CommentController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/reorder', [CommentController::class, 'reorder'])->name('reorder');
        Route::get('/{id}/view', [CommentController::class, 'view'])->name('view');
    });
    
    // Resource Definition Management
    Route::prefix('resource-definitions')->name('resource-definitions.')->group(function () {
        Route::get('/', [ResourceDefinitionController::class, 'index'])->name('index');
        Route::post('/', [ResourceDefinitionController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ResourceDefinitionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ResourceDefinitionController::class, 'update'])->name('update');
        Route::delete('/{id}', [ResourceDefinitionController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [ResourceDefinitionController::class, 'bulkDelete'])->name('bulk-delete');
        Route::post('/reorder', [ResourceDefinitionController::class, 'reorder'])->name('reorder');
    });
    
    // Activity Routes
    Route::get('/activities', [ActivityController::class, 'index'])->name('activities.index');
    Route::get('/activities/{id}', [ActivityController::class, 'show'])->name('activities.show');
    Route::get('/activities/user/{userId}', [ActivityController::class, 'userActivities'])->name('activities.user');
    Route::get('/activities/module/{module}', [ActivityController::class, 'moduleActivities'])->name('activities.module');
    Route::get('/activities/date-range', [ActivityController::class, 'dateRangeActivities'])->name('activities.date-range');
    Route::get('/activities/type/{type}', [ActivityController::class, 'typeActivities'])->name('activities.type');
    Route::delete('/activities/{id}', [ActivityController::class, 'destroy'])->name('activities.destroy');
    Route::delete('/activities/bulk', [ActivityController::class, 'bulkDestroy'])->name('activities.bulk-destroy');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Test route'u
Route::get('/test-users', function() {
    dd(auth()->user(), 'Test route working');
});
