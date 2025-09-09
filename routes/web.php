<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Finance\BudgetController;
use App\Http\Controllers\Finance\InvoiceController;
use App\Http\Controllers\Finance\PaymentController;
use App\Http\Controllers\Finance\ProcurementController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ServiceRequestController;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('auth.login');
});

Route::get('/index',[ViewController::class,'index']); 
Route::get('/login',[ViewController::class,'login']);
Route::get('/ZUHAIB',[ViewController::class,'ZUHAIB']);
Route::get('/signup',[ViewController::class,'signup']);
Route::get('/users',[ViewController::class,'users']);
Route::get('/form',[ViewController::class,'form']);
Auth::routes();

// Admin Dashboard
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard'); 
})->name('admin.dashboard');
// PM Dashboard
Route::get('/pm/dashboard', [App\Http\Controllers\ViewController::class, 'pmDashboard'])
    ->name('pm.dashboard');
// FCO Dashboard
Route::get('/fco/dashboard', [App\Http\Controllers\ViewController::class, 'fcoDashboard'])
    ->name('fco.dashboard');
// PMO Dashboard
Route::get('/pmo/dashboard', [App\Http\Controllers\ViewController::class, 'pmoDashboard'])
    ->name('pmo.dashboard');
// CSO Dashboard
Route::get('/cso/dashboard', [App\Http\Controllers\ViewController::class, 'csoDashboard'])
    ->name('cso.dashboard');    
    
// User Management - Protected route for Admin only
Route::get('/admin/user-management', [App\Http\Controllers\UserManagementController::class, 'index'])
        ->name('admin.user-management');
// Assign Role to User - Protected route for Admin only
Route::put('/users/{user}/assign-role', [App\Http\Controllers\UserManagementController::class, 'assignRole'])
    ->name('users.assignRole');

// Admin Register Form
Route::get('/admin/register', [AdminController::class, 'showRegisterForm'])->name('admin.register');

// Admin Register Store
// Route::post('/admin/register', [AdminController::class, 'register'])->name('admin.register.store');

//pending approval route
Route::get('/no-role', function () {return view('no-role');})->name('no.role');


// Protected routes for users with roles
Route::middleware(['role:Admin'])->group(function () {
    Route::get('/Admin/user', [UserManagementController::class, 'index'])->name('Admin.user');
    Route::post('/admin/users/{id}/assign-role', [UserManagementController::class, 'assignRole'])->name('admin.users.assignRole');
});

// Settings - Backup & Restore
Route::prefix('settings')->name('settings.')->group(function () {
    // Settings ka main page (GET)
    Route::get('/', [SettingsController::class, 'index'])->name('backup&restore');
    

     // Backup database download
    Route::get('/backup/download', [SettingsController::class, 'backupDatabase'])->name('backup.download');

    // Restore backup
    Route::post('/backup/restore', [SettingsController::class, 'restoreBackup'])->name('backup.restore');

     // Security
    Route::get('/setting', [SettingsController::class, 'security'])->name('security');   // <- ye zaroori hai
    Route::post('/security/change-password', [SettingsController::class, 'changePassword'])->name('security.changePassword');

    // Logo
    // GET: form show karne ke liye
    
    Route::get('/settings/logo', [SettingsController::class, 'showLogoForm'])->name('settings.logo');

// POST: logo update karne ke liye
Route::post('/update-logo', [SettingsController::class, 'updateLogo'])->name('updateLogo');

});

// ====== FINANCE MODULES ======
// Finance Module Routes
Route::prefix('finance')->name('finance.')->group(function () {

    // Budgets
    Route::prefix('budgets')->name('budgets.')->group(function () {
        Route::get('/', [BudgetController::class, 'index'])->name('index');         // route('finance.budgets.index')
        Route::get('/create', [BudgetController::class, 'create'])->name('create'); // route('finance.budgets.create')
        Route::post('/store', [BudgetController::class, 'store'])->name('store');
        Route::get('/{budget}/edit', [BudgetController::class, 'edit'])->name('edit');
        Route::put('/{budget}', [BudgetController::class, 'update'])->name('update');
        Route::delete('/{budget}', [BudgetController::class, 'destroy'])->name('destroy');
    });

    // Invoices
    Route::prefix('invoices')->name('invoices.')->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])->name('index');
    Route::get('/create', [InvoiceController::class, 'create'])->name('create');
    Route::post('/store', [InvoiceController::class, 'store'])->name('store');

    // EDIT route should come before {invoice} catch-all routes
    Route::get('/{invoice}/edit', [InvoiceController::class, 'edit'])->name('edit');
    Route::put('/{invoice}', [InvoiceController::class, 'update'])->name('update');

    Route::delete('/{invoice}', [InvoiceController::class, 'destroy'])->name('destroy');
});


// Payments
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/create', [PaymentController::class, 'create'])->name('create');
        Route::post('/', [PaymentController::class, 'store'])->name('store');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
        Route::get('/{payment}/edit', [PaymentController::class, 'edit'])->name('edit');
        Route::put('/{payment}', [PaymentController::class, 'update'])->name('update');
        Route::delete('/{payment}', [PaymentController::class, 'destroy'])->name('destroy');
    });





    // Procurements
    Route::prefix('procurements')->name('procurements.')->group(function () {
        Route::get('/', [ProcurementController::class, 'index'])->name('index');         // route('finance.procurements.index')
        Route::get('/create', [ProcurementController::class, 'create'])->name('create'); // route('finance.procurements.create')
        Route::post('/store', [ProcurementController::class, 'store'])->name('store');
        Route::get('/{procurement}/edit', [ProcurementController::class, 'edit'])->name('edit');
        Route::put('/{procurement}', [ProcurementController::class, 'update'])->name('update');
        Route::delete('/{procurement}', [ProcurementController::class, 'destroy'])->name('destroy');
    });

});


// Department CRUD routes
Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

// Service Route

Route::prefix('services')->name('services.')->group(function () {
    // List all service requests
    Route::get('/', [ServiceRequestController::class, 'index'])->name('index');           // views/services/index.blade.php

    // Create service request form
    Route::get('/create', [ServiceRequestController::class, 'create'])->name('create');   // views/services/create.blade.php
    Route::post('/store', [ServiceRequestController::class, 'store'])->name('store');     // store action

    // Edit service request form
    Route::get('/{serviceRequest}/edit', [ServiceRequestController::class, 'edit'])->name('edit'); // views/services/edit.blade.php
    Route::put('/{serviceRequest}/update', [ServiceRequestController::class, 'update'])->name('update'); // update action

    // Show service request details
    Route::get('/{serviceRequest}/show', [ServiceRequestController::class, 'show'])->name('show'); // views/services/show.blade.php

    // Delete service request
    Route::delete('/{serviceRequest}/delete', [ServiceRequestController::class, 'destroy'])->name('destroy'); // delete action
});


