<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\VendorRankingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SystemSettingsController;
use App\Http\Controllers\Vendor\OrderController;
use App\Http\Controllers\Vendor\RankingController;
use App\Http\Controllers\Vendor\SettingsController as VendorSettingsController; 
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\PendingApprovalController;
use App\Http\Controllers\Vendor\DashboardController as VendorDashboardController;
use App\Http\Controllers\Vendor\ProductController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public home
Route::get('/', [GuestController::class, 'index'])->name('home');

// Pending approval page
Route::middleware(['auth'])->get('/pending-approval', [PendingApprovalController::class, 'index'])
    ->name('pending.approval');

// ===== ADMIN ROUTES =====
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Vendor Management
    Route::get('/vendors', function () {
        return Inertia::render('Admin/Vendors');
    })->name('vendors.index');

    Route::get('/vendors/pending', [VendorController::class, 'pending'])->name('vendors.pending');
    Route::get('/vendors/approved', [VendorController::class, 'approved'])->name('vendors.approved');
    Route::put('/vendors/{id}/approve', [VendorController::class, 'approve'])->name('vendors.approve');
    Route::delete('/vendors/{id}', [VendorController::class, 'destroy'])->name('vendors.destroy');

    //Vendor Suspension-Admin
    Route::put('/vendors/{id}/suspend', [VendorController::class, 'suspend'])->name('vendors.suspend');
    Route::put('/vendors/{id}/unsuspend', [VendorController::class, 'unsuspend'])->name('vendors.unsuspend');

    // Product Management
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class)
        ->except(['show']);

    // Order Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

    // Admin Rankings
    Route::get('/rankings', [VendorRankingController::class, 'index'])->name('rankings');

    // Admin System Settings
    Route::get('/system-settings', [SystemSettingsController::class, 'index'])->name('system-settings');
    Route::put('/system-settings/general', [SystemSettingsController::class, 'updateGeneral'])->name('system-settings.update-general');
    Route::put('/system-settings/payment', [SystemSettingsController::class, 'updatePayment'])->name('system-settings.update-payment');

    // Category Management
    Route::get('/settings', [CategoryController::class, 'index'])->name('settings');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Admin Notifications
    Route::get('/notifications/create', [NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications/send', [NotificationController::class, 'send'])->name('notifications.send');
});

// ===== VENDOR ROUTES =====
Route::middleware(['auth', 'vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');

    // Product catalog & price list
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/price-list/download', [ProductController::class, 'download'])->name('price-list.download');
    Route::get('/price-list/download-pdf', [ProductController::class, 'downloadPDF'])->name('price-list.download-pdf');
    Route::get('/price-list', [ProductController::class, 'priceList'])->name('price-list');

    // Orders
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/orders/last-updated', [OrderController::class, 'lastUpdated'])->name('orders.last-updated');

    // Vendor Rankings
    Route::get('/rankings', [RankingController::class, 'index'])->name('rankings');

    // Vendor Settings (using alias)
    Route::get('/settings', [VendorSettingsController::class, 'index'])->name('settings');
    Route::put('/settings/profile', [VendorSettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::put('/settings/password', [VendorSettingsController::class, 'updatePassword'])->name('settings.update-password');
    Route::put('/settings/preferences', [VendorSettingsController::class, 'updatePreferences'])->name('settings.update-preferences');

    // Vendor Suspension
    Route::get('/suspended', function () {
        return Inertia::render('Vendor/Suspended');
    })->name('vendor.suspended');
});

// Default Breeze auth routes
require __DIR__.'/auth.php';