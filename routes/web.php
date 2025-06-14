<?php
// Workflow: Ticket -> Job Order -> Requisition -> Inventory -> Purchase Order -> Document Management

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;


Route::view('/', 'landing')->name('home');

Route::view('/app/{any?}', 'spa')
    ->where('any', '.*')
    ->name('spa');


Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::get('/forgot-password', [ForgotPasswordController::class, 'show'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');


use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\JobOrderController;
use App\Http\Controllers\RequisitionController;
use App\Http\Controllers\InventoryItemController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentDashboardController;
use App\Http\Controllers\DocumentTrackingController;
use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\KpiAuditDashboardController;
use App\Http\Controllers\TicketCategoryController;
use App\Http\Controllers\JobOrderTypeController;
use App\Http\Controllers\InventoryCategoryController;
use App\Http\Controllers\DocumentCategoryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\AnnouncementController;

Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/help', [HelpController::class, 'index'])->name('help');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class, 'data'])->name('dashboard.data');
    Route::resource('users', UserController::class)
        ->except(['show'])
        ->middleware('role:admin');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::resource('tickets', TicketController::class)->except('show');
    Route::post('tickets/{ticket}/convert', [TicketController::class, 'convertToJobOrder'])->name('tickets.convert');
    Route::post('tickets/{ticket}/requisition', [TicketController::class, 'convertToRequisition'])->name('tickets.requisition');
    Route::post('tickets/{ticket}/comments', [TicketController::class, 'storeComment'])->name('tickets.comment');
    Route::get('tickets/{ticket}/attachment', [TicketController::class, 'downloadAttachment'])->name('tickets.attachment');
    Route::resource('job-orders', JobOrderController::class)->except('show');
    Route::get('job-orders/{jobOrder}/attachment', [JobOrderController::class, 'downloadAttachment'])->name('job-orders.attachment');
    Route::put('job-orders/{jobOrder}/complete', [JobOrderController::class, 'complete'])->name('job-orders.complete');
    Route::put('job-orders/{jobOrder}/close', [JobOrderController::class, 'close'])->name('job-orders.close');
    Route::post('job-orders/{jobOrder}/materials', [JobOrderController::class, 'requestMaterials'])->name('job-orders.materials');

    Route::get('job-order-types/{parent}/children', [JobOrderTypeController::class, 'children'])->name('job-order-types.children');
    Route::get('job-orders/approvals', [JobOrderController::class, 'approvals'])->name('job-orders.approvals')->middleware('role:head,president,finance');
    Route::put('job-orders/{jobOrder}/approve', [JobOrderController::class, 'approve'])->name('job-orders.approve')->middleware('role:head,president,finance');
    Route::get('job-orders/assignments', [JobOrderController::class, 'assignments'])->name('job-orders.assignments')->middleware('role:itrc,admin');
    Route::put('job-orders/{jobOrder}/assign', [JobOrderController::class, 'assign'])->name('job-orders.assign')->middleware('role:itrc,admin');
    Route::get('job-orders/assigned', [JobOrderController::class, 'assigned'])->name('job-orders.assigned')->middleware('role:staff,itrc');
    Route::put('job-orders/{jobOrder}/start', [JobOrderController::class, 'start'])->name('job-orders.start');
    Route::put('job-orders/{jobOrder}/finish', [JobOrderController::class, 'finish'])->name('job-orders.finish');
    Route::get('requisitions/approvals', [RequisitionController::class,'approvals'])
        ->middleware('role:head,president,finance')
        ->name('requisitions.approvals');
    Route::put('requisitions/{requisition}/approve', [RequisitionController::class,'approve'])
        ->middleware('role:head,president,finance')
        ->name('requisitions.approve');
    Route::get('requisitions/{requisition}/attachment', [RequisitionController::class, 'downloadAttachment'])->name('requisitions.attachment');
    Route::resource('requisitions', RequisitionController::class)->except('show');
    Route::middleware('role:admin,itrc')->group(function () {
        Route::resource('inventory', InventoryItemController::class)->except('show');
        Route::post('inventory/{inventory}/issue', [InventoryItemController::class, 'issue'])->name('inventory.issue');
        Route::post('inventory/{inventory}/return', [InventoryItemController::class, 'return'])->name('inventory.return');
    });
    Route::get('purchase-orders/{purchaseOrder}/attachment', [PurchaseOrderController::class, 'downloadAttachment'])->name('purchase-orders.attachment');
    Route::resource('purchase-orders', PurchaseOrderController::class)->except('show');
    Route::resource('documents', DocumentController::class);
    Route::get('documents/{document}/versions/{version}/download', [DocumentController::class, 'download'])
        ->name('documents.download');
    Route::get('documents-dashboard', [DocumentDashboardController::class, 'index'])->name('documents.dashboard');
    Route::get('kpi-dashboard', [KpiAuditDashboardController::class, 'index'])
        ->name('kpi.dashboard');
    Route::get('kpi-dashboard/export', [KpiAuditDashboardController::class, 'export'])
        ->name('kpi.dashboard.export')
        ->middleware('role:admin');
    Route::get('audit-trails', [AuditTrailController::class, 'index'])->name('audit-trails.index');

    Route::middleware('role:admin')->prefix('settings')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('settings.index');
        Route::get('theme', [SettingController::class, 'editTheme'])->name('settings.theme');
        Route::put('theme', [SettingController::class, 'updateTheme'])->name('settings.theme.update');
        Route::get('branding', [SettingController::class, 'editBranding'])->name('settings.branding');
        Route::put('branding', [SettingController::class, 'updateBranding'])->name('settings.branding.update');
        Route::get('institution', [SettingController::class, 'editInstitution'])->name('settings.institution');
        Route::put('institution', [SettingController::class, 'updateInstitution'])->name('settings.institution.update');
        Route::get('localization', [SettingController::class, 'editLocalization'])->name('settings.localization');
        Route::put('localization', [SettingController::class, 'updateLocalization'])->name('settings.localization.update');
        Route::get('notifications', [SettingController::class, 'editNotifications'])->name('settings.notifications');
        Route::put('notifications', [SettingController::class, 'updateNotifications'])->name('settings.notifications.update');

        Route::resource('ticket-categories', TicketCategoryController::class)->except('show');
        Route::resource('document-categories', DocumentCategoryController::class)->except('show');
        Route::put('inventory-categories/{inventoryCategory}/disable', [InventoryCategoryController::class, 'disable'])->name('inventory-categories.disable');
        Route::resource('inventory-categories', InventoryCategoryController::class)->except('show');
        Route::put('job-order-types/{jobOrderType}/disable', [JobOrderTypeController::class, 'disable'])->name('job-order-types.disable');
        Route::resource('job-order-types', JobOrderTypeController::class)->except('show');

        Route::resource('announcements', AnnouncementController::class)->except('show');
    });
    Route::prefix('document-tracking')->group(function () {
        Route::get('incoming', [DocumentTrackingController::class, 'incoming'])->name('document-tracking.incoming');
        Route::get('outgoing', [DocumentTrackingController::class, 'outgoing'])->name('document-tracking.outgoing');
        Route::get('for-approval', [DocumentTrackingController::class, 'forApproval'])->name('document-tracking.for-approval');
        Route::get('tracking', [DocumentTrackingController::class, 'tracking'])->name('document-tracking.tracking');
        Route::get('reports', [DocumentTrackingController::class, 'reports'])->name('document-tracking.reports');
    });
});
