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
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\RequestApproval;


Route::get('/clear-cache', function () {
    try {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        return 'cache cleared successfully';
    } catch (\Exception $e){
        return 'Error Clearing cache: ' . $e->getMessage();
    }
});


Route::get('/', function () {
    return view('auth.login');
});

Route::get('/index',[ViewController::class,'index']); 
Route::get('/login',[ViewController::class,'login']);
Route::get('/signup',[ViewController::class,'signup']);
Route::get('/users',[ViewController::class,'users']);
Route::get('/form',[ViewController::class,'form']);
Auth::routes();

// Protected Dashboards
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\ViewController::class, 'adminDashboard'])
        ->name('admin.dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/settings', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.settings');
    Route::post('/profile/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});


// Notification Routes
Route::middleware('auth')->prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/toggle/{id}', [NotificationController::class, 'toggleRead'])->name('notifications.toggle');
    Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unreadCount');
});


// User Management - Protected route for Admin only
Route::get('/admin/user-management', [App\Http\Controllers\UserManagementController::class, 'index'])
        ->name('admin.user-management');

// Deleted Users Page Route
Route::get('admin/users/deleted', [UserManagementController::class, 'deletedUsers'])
    ->name('admin.deleted-users');

// User Restore Route
Route::post('admin/users/{id}/restore', [UserManagementController::class, 'restore'])
    ->name('admin.users.restore');

// User Permanent Delete Route (POST ya DELETE method zyada secure hai)
Route::delete('admin/users/{id}/force-delete', [UserManagementController::class, 'forceDelete'])
    ->name('admin.users.force-delete');


Route::patch('/users/{user}/status', [UserManagementController::class, 'updateStatus'])->name('users.update-status');

// Assign Role to User - Protected route for Admin only
Route::put('/users/{user}/assign-role', [App\Http\Controllers\UserManagementController::class, 'assignRole'])
    ->name('users.assignRole');

// Admin Register Form
Route::get('/admin/register', [AdminController::class, 'showRegisterForm'])->name('admin.register');

// Admin Register Store
Route::post('/admin/register', [AdminController::class, 'register'])->name('admin.register.store');

//pending approval route
Route::get('/no-role', function () {return view('no-role');})->name('no.role');


// Permissions page
Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
Route::post('/permissions/{user}', [PermissionController::class, 'update'])->name('permissions.update');

// Users ki permissions ka page dikhane ke liye naya route
Route::get('/users/{user}/permissions', [PermissionController::class, 'editPermissions'])->name('users.edit-permissions');

// Permissions ko update karne ke liye naya route
Route::post('/users/{user}/permissions', [PermissionController::class, 'updatePermissions'])->name('users.update-permissions');


// Protected routes for users with roles
Route::middleware(['role:Admin'])->group(function () {
    Route::get('/Admin/user', [UserManagementController::class, 'index'])->name('Admin.user');
    Route::post('/admin/users/{id}/assign-role', [UserManagementController::class, 'assignRole'])->name('admin.users.assignRole');
});

// Admin Role Management Routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles/store', [RoleController::class, 'store'])->name('roles.store');
        // Show roles list
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.show');
      //  Edit role form
    Route::get('/roles/{id}/edit', [RoleController::class, 'edit'])->name('roles.edit');

    //  Update role
    Route::put('/roles/{id}', [RoleController::class, 'update'])->name('roles.update');

    // Delete role
    Route::delete('/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy');
});


// ====== FINANCE MODULES ======
// Finance Module Routes
Route::prefix('finance')->name('finance.')->middleware(['auth'])->group(function () {
    
    // Budgets
    Route::get('/budgets', [BudgetController::class, 'index'])->name('budgets.index');
    Route::get('/budgets/create', [BudgetController::class, 'create'])->name('budgets.create');
    Route::post('/budgets/store', [BudgetController::class, 'store'])->name('budgets.store');
    Route::get('/budgets/{budget}', [BudgetController::class, 'show'])->name('budgets.show');
    Route::get('/budgets/{budget}/edit', [BudgetController::class, 'edit'])->name('budgets.edit');
    Route::put('/budgets/{budget}', [BudgetController::class, 'update'])->name('budgets.update');
    Route::delete('/budgets/{budget}', [BudgetController::class, 'destroy'])->name('budgets.destroy');
    Route::post('/budgets/{id}/status', [BudgetController::class, 'updateStatus'])->name('budget.updateStatus');

    // Invoices
    Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('invoices/create', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('invoices/store', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('invoices/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoices.edit');
    Route::put('invoices/{invoice}', [InvoiceController::class, 'update'])->name('invoices.update');
    Route::delete('invoices/{invoice}', [InvoiceController::class, 'destroy'])->name('invoices.destroy');
    Route::get('finance/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('finance.invoices.download');



    //  Download Route
    Route::get('invoices/{id}/download', [InvoiceController::class, 'download'])->name('invoices.download');


    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments/store', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    // Procurements
    Route::get('/procurements', [ProcurementController::class, 'index'])->name('procurements.index');
    Route::get('/procurements/create', [ProcurementController::class, 'create'])->name('procurements.create');
    Route::post('/procurements/store', [ProcurementController::class, 'store'])->name('procurements.store');
    Route::get('/procurements/{procurement}', [ProcurementController::class, 'show'])->name('procurements.show');
    Route::get('/procurements/{procurement}/edit', [ProcurementController::class, 'edit'])->name('procurements.edit');
    Route::put('/procurements/{procurement}', [ProcurementController::class, 'update'])->name('procurements.update');
    Route::delete('/procurements/{procurement}', [ProcurementController::class, 'destroy'])->name('procurements.destroy');
});

// User Management Routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::resource('users', UserManagementController::class);

});

// Department routes
Route::get('departments', [DepartmentController::class, 'index'])->name('departments.index');
Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');

// Service Route
Route::prefix('services')->name('services.')->group(function () {
    // List all service requests
    Route::get('/services', [ServiceRequestController::class, 'index'])->name('index');           // views/services/index.blade.php

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


// Request Route
Route::resource('requests', RequestController::class);
Route::post('/requests/{id}/update-status', [RequestController::class, 'updateStatus'])->name('requests.updateStatus');

// ================= Approvals =================
Route::prefix('approvals')->name('approvals.')->group(function () {
    // Sare approvals show karna
    Route::get('/', [ApprovalController::class, 'index'])->name('index');

    // Approval create form (agar chahiye)
    Route::get('/create', [ApprovalController::class, 'create'])->name('create');

    // Store new approval
    Route::post('/store', [ApprovalController::class, 'store'])->name('store');
});



// ====== AUDIT MODULES ======
// Audit Log
Route::prefix('audit-logs')->name('audit.logs.')->group(function () {
    Route::get('/', [AuditLogController::class, 'index'])->name('index');
    Route::get('/export', [AuditLogController::class, 'export'])->name('export');
    Route::get('/export-full', [AuditLogController::class, 'exportFull'])->name('exportFull');
});


// ====== SEARCH MODULES ======
// Search ke liye route banayein
Route::get('/search-results', [SearchController::class, 'index'])->name('search.results');



// ====== REPORT MODULES ======
// Reports Routes
Route::prefix('reports')->middleware(['auth'])->group(function () {
    Route::get('/finance', [ReportsController::class, 'financeReport'])->name('reports.finance');
    Route::get('/finance/export/excel', [ReportsController::class, 'exportFinanceExcel'])->name('reports.finance.export.excel');
    Route::get('/finance/export/pdf', [ReportsController::class, 'exportFinancePdf'])->name('reports.finance.export.pdf');
    
    Route::get('/audit', [ReportsController::class, 'auditReport'])->name('reports.audit');
    Route::get('/audit/export/excel', [ReportsController::class, 'exportAuditExcel'])->name('reports.audit.export.excel');
    Route::get('/audit/export/pdf', [ReportsController::class, 'exportAuditPDF'])->name('reports.audit.export.pdf');
    
    Route::get('/procurement', [ReportsController::class, 'procurementReport'])->name('reports.procurement');
    Route::get('/procurement/export/excel', [ReportsController::class, 'exportProcurementExcel'])->name('reports.procurement.export.excel');
    Route::get('/procurement/export/pdf', [ReportsController::class, 'exportProcurementPDF'])->name('reports.procurement.export.pdf');
    
    Route::get('/requests', [ReportsController::class, 'requestReport'])->name('reports.requests');
    Route::get('/requests/export/excel', [ReportsController::class, 'exportRequestExcel'])->name('reports.requests.export.excel');
    Route::get('/requests/export/pdf', [ReportsController::class, 'exportRequestPDF'])->name('reports.requests.export.pdf');
    
    Route::get('/workflow', [ReportsController::class, 'workFlowReport'])->name('reports.workflow');
    Route::get('/workflows/export/excel', [ReportsController::class, 'exportWorkflowExcel'])->name('reports.workflows.export.excel');
    Route::get('/workflows/export/pdf', [ReportsController::class, 'exportWorkflowPDF'])->name('reports.workflows.export.pdf');
});

// Pending and Rejected Requests
Route::get('/requests.pending', [RequestApproval::class, 'pending'])->name('requests.pending');
Route::get('/requests.rejected', [RequestApproval::class, 'rejected'])->name('requests.rejected');

// Backup & Restore
Route::get('/settings/backup-restore', [BackupController::class, 'index'])->name('settings.backup&restore');
Route::post('/settings/backup', [BackupController::class, 'backup'])->name('settings.backup');
Route::post('/settings/restore', [BackupController::class, 'restore'])->name('settings.restore');

// approvals
Route::post('/procurement/{id}/update-status', [ProcurementController::class, 'updateStatus'])->name('procurement.updateStatus');