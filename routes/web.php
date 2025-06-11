<?php
// Workflow: Ticket -> Job Order -> Requisition -> Inventory -> Purchase Order -> Document Management

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;


Route::view('/', 'landing')->name('home');


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

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class)
        ->except(['show', 'create', 'store'])
        ->middleware('role:admin');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::resource('tickets', TicketController::class)->except('show');
    Route::post('tickets/{ticket}/convert', [TicketController::class, 'convertToJobOrder'])->name('tickets.convert');
    Route::post('tickets/{ticket}/requisition', [TicketController::class, 'convertToRequisition'])->name('tickets.requisition');
    Route::post('tickets/{ticket}/comments', [TicketController::class, 'storeComment'])->name('tickets.comment');
    Route::resource('job-orders', JobOrderController::class)->except('show');
    Route::put('job-orders/{jobOrder}/complete', [JobOrderController::class, 'complete'])->name('job-orders.complete');
    Route::post('job-orders/{jobOrder}/materials', [JobOrderController::class, 'requestMaterials'])->name('job-orders.materials');
    Route::resource('requisitions', RequisitionController::class)->except('show');
    Route::resource('inventory', InventoryItemController::class)->except('show');
    Route::resource('purchase-orders', PurchaseOrderController::class)->except('show');
    Route::resource('documents', DocumentController::class)->except('show');
    Route::get('documents-dashboard', [DocumentDashboardController::class, 'index'])->name('documents.dashboard');
    Route::get('audit-trails', [AuditTrailController::class, 'index'])->name('audit-trails.index');
    Route::prefix('document-tracking')->group(function () {
        Route::get('incoming', [DocumentTrackingController::class, 'incoming'])->name('document-tracking.incoming');
        Route::get('outgoing', [DocumentTrackingController::class, 'outgoing'])->name('document-tracking.outgoing');
        Route::get('for-approval', [DocumentTrackingController::class, 'forApproval'])->name('document-tracking.for-approval');
        Route::get('tracking', [DocumentTrackingController::class, 'tracking'])->name('document-tracking.tracking');
        Route::get('reports', [DocumentTrackingController::class, 'reports'])->name('document-tracking.reports');
    });
});
