<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\ProductAttributeController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Manager\ManagerDashboardController;
use App\Http\Controllers\Staff\StaffDashboardController;

use App\Http\Controllers\Inventory\StockTransactionController;

Route::redirect('/', '/login');

// =======================
// Dashboard per role
// =======================
Route::get('/dashboard', function() {
    $role = Auth::user()?->role;
    return match ($role) {
        'Admin'          => redirect()->route('admin.dashboard'),
        'Manajer Gudang' => redirect()->route('manager.dashboard'),
        'Staff Gudang'   => redirect()->route('staff.dashboard'),
        default          => abort(403),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth','role:Admin'])->get('/admin', AdminDashboardController::class)->name('admin.dashboard');
Route::middleware(['auth','role:Manajer Gudang'])->get('/manajer', ManagerDashboardController::class)->name('manager.dashboard');
Route::middleware(['auth','role:Staff Gudang'])->get('/staff', StaffDashboardController::class)->name('staff.dashboard');

// =======================
// Profile
// =======================
Route::middleware('auth')->group(function() {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =======================
// Transaksi Stok
// =======================
Route::middleware(['auth'])->group(function () {
    // Staff + Manager + Admin → buat transaksi (Pending)
    Route::middleware('role:Staff Gudang,Manajer Gudang,Admin')->group(function () {
        Route::resource('transactions', StockTransactionController::class)
            ->only(['index', 'create', 'store', 'show']);
    });

    // Manager + Admin → approval, eksekusi, koreksi
    Route::middleware('role:Manajer Gudang,Admin')->group(function () {
        Route::get('transactions/{transaction}/approve', [StockTransactionController::class, 'approveForm'])
            ->name('transactions.approve_form');
        Route::post('transactions/{transaction}/approve', [StockTransactionController::class, 'approveIn'])
            ->name('transactions.approve_in');

        Route::post('transactions/{transaction}/dispatch', [StockTransactionController::class, 'dispatchOut'])
            ->name('transactions.dispatch');
        Route::post('transactions/{transaction}/reject', [StockTransactionController::class, 'reject'])
            ->name('transactions.reject');

        Route::get('transactions/{transaction}/correct', [StockTransactionController::class, 'correctForm'])
            ->name('transactions.correct_form');
        Route::post('transactions/{transaction}/correct', [StockTransactionController::class, 'correct'])
            ->name('transactions.correct');
    });
});

// =======================
// Categories
// =======================
Route::middleware(['auth'])->group(function () {
    Route::resource('categories', CategoryController::class);
});

// =======================
// Suppliers
// =======================
Route::middleware(['auth'])->group(function() {
    Route::resource('suppliers', SupplierController::class);
});

// =======================
// Products
// =======================
Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);
});


// =======================
// Users (Admin only)
// =======================
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('users', UserController::class);
    });

// =======================
// Product Attributes (Admin only)
// =======================
// Route::middleware(['auth', 'role:Admin'])->group(function () {
//     Route::resource('attributes', ProductAttributeController::class);
// });

// Admin full access
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::resource('attributes', ProductAttributeController::class);
});

// Manager hanya view
Route::middleware(['auth', 'role:Manajer Gudang'])->group(function () {
    Route::resource('attributes', ProductAttributeController::class)
        ->only(['index', 'show']);
});

require __DIR__.'/auth.php';