<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Finance\InvoiceController;
use App\Http\Controllers\Finance\PaymentController;
use App\Http\Controllers\Finance\BudgetController;
use App\Http\Controllers\Finance\ProcurementController;
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
Route::get('/admin/dashboard', [App\Http\Controllers\Auth\LoginController::class, 'dashboard'])
    ->name('admin.dashboard');
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

// Finance Routes

// ================= INVOICES =================
Route::prefix('finance/invoices')->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])->name('finance.invoices.index');
    Route::get('/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/store', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/{id}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/{id}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('/{id}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('/{id}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
});

// ================= PAYMENTS =================
Route::prefix('finance/payments')->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('finance.payments.index');
    Route::get('/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/store', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/{id}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/{id}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/{id}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/{id}', [PaymentController::class, 'destroy'])->name('payments.destroy');
});

// ================= BUDGETS =================
Route::prefix('finance/budgets')->group(function () {
    Route::get('/', [BudgetController::class, 'index'])->name('finance.budgets.index'); // index route ka naam thoda unique hai
    Route::get('/create', [BudgetController::class, 'create'])->name('budgets.create');
    Route::post('/store', [BudgetController::class, 'store'])->name('budgets.store');
    Route::get('/{id}/edit', [BudgetController::class, 'edit'])->name('budgets.edit');
    Route::put('/{id}', [BudgetController::class, 'update'])->name('budgets.update');
    Route::delete('/{id}', [BudgetController::class, 'destroy'])->name('budgets.destroy');
});

// ================= PROCUREMENTS =================
Route::prefix('finance/procurements')->group(function () {
    Route::get('/', [ProcurementController::class, 'index'])->name('finance.procurements.index');
    Route::get('/create', [ProcurementController::class, 'create'])->name('procurements.create');
    Route::post('/store', [ProcurementController::class, 'store'])->name('procurements.store');
    Route::get('/{id}', [ProcurementController::class, 'show'])->name('procurements.show');
    Route::get('/{id}/edit', [ProcurementController::class, 'edit'])->name('procurements.edit');
    Route::put('/{id}', [ProcurementController::class, 'update'])->name('procurements.update');
    Route::delete('/{id}', [ProcurementController::class, 'destroy'])->name('procurements.destroy');
});



