<?php

use App\Models\StockTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Staff\StaffDashboardController;
use App\Http\Controllers\Manager\ManagerDashboardController;
use App\Http\Controllers\Inventory\StockTransactionController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::redirect('/', '/login');

Route::get('/dashboard', function() {
    $role = Auth::user()?->role;    //
    return match($role) {
        'Admin'             => redirect()->route('admin.dashboard'),
        'Manajer Gudang'    => redirect()->route('manager.dashboard'),
        'Staff Gudang'      => redirect()->route('staff.dashboard'),
        default             => abort(403),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:Admin'])
    ->get('/admin', AdminDashboardController::class)->name('admin.dashboard');

Route::middleware(['auth', 'role:Manajer Gudang'])
    ->get('/manajer', ManagerDashboardController::class)->name('manager.dashboard');

Route::middleware(['auth', 'role:Staff Gudang'])
    ->get('/staff', StaffDashboardController::class)->name('staff.dashboard');

Route::middleware('auth')->group(function() {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =====================================================
// 🔹 Tambahan untuk Transaksi Stok
// =====================================================

// Staff/Manajer/Admin boleh bikin transaksi (Pending)
// Route::middleware(['auth', 'role:Staff Gudang,Manajer Gudang,Admin'])->group(function() {
//     // Route::get('/transaction', [StockTransactionController::class, 'store'])
//     //     ->name('transactions.store');
//     Route::get('/transactions/create', [StockTransactionController::class, 'create'])
//         ->name('transactions.create');
// });

// // Manajer/Admin menyetujui/mengeksekusi/menolaj
// Route::middleware(['auth', 'role:Manajer Gudang,Admin'])->group(function() {
//     Route::post('/transactions/{transaction}/approve-in', [StockTransactionController::class, 'approveIn'])
//         ->name('transaction.approve_in');

//     Route::post('/transactions/{transaction}/dispatch-out', [StockTransactionController::class, 'dispatchOut'])
//         ->name('transaction.dispatch_out');

//     Route::post('/transactions/{transaction}/reject', [StockTransactionController::class, 'reject'])
//         ->name('transaction.reject');
// });
Route::middleware(['auth'])->group(function () {
    // Staff/Admin/Manajer → boleh membuat transaksi (Pending)
    Route::middleware('role:Staff Gudang,Manajer Gudang,Admin')->group(function () {
        Route::resource('transactions', StockTransactionController::class)
            ->only(['index', 'create', 'store', 'show']);
    });

    // Manajer/Admin → approval & eksekusi
    Route::middleware('role:Manajer Gudang,Admin')->group(function () {
        Route::post('transactions/{transaction}/approve-in', [StockTransactionController::class, 'approveIn'])
            ->name('transactions.approve_in');
        Route::post('transactions/{transaction}/dispatch-out', [StockTransactionController::class, 'dispatchOut'])
            ->name('transactions.dispatch_out');
        Route::post('transactions/{transaction}/reject', [StockTransactionController::class, 'reject'])
            ->name('transactions.reject');
    });
});

// Form approve transaksi masuk
Route::get('transactions/{transaction}/approve', [StockTransactionController::class, 'approveForm'])
    ->name('transactions.approve_form');

// Proses approve
Route::post('transactions/{transaction}/approve', [StockTransactionController::class, 'approveIn'])
    ->name('transactions.approve_in');

// Proses koreksi kesalahan input pilihan suplier dari akun Manajer
Route::middleware(['auth','role:Manajer Gudang,Admin'])->group(function () {
    Route::get('transactions/{transaction}/correct', [StockTransactionController::class, 'correctForm'])
        ->name('transactions.correct_form');
    Route::post('transactions/{transaction}/correct', [StockTransactionController::class, 'correct'])
        ->name('transactions.correct');
});


// Category Controller admin
Route::middleware(['auth','role:Admin'])->group(function () {
    Route::resource('categories', CategoryController::class);
});

// SupplierController sementara untuk admin dulu
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);
});

// CRUD Products sementara admin dulu
Route::middleware(['auth', 'role:Admin'])->group(function() {
    Route::resource('products', \App\Http\Controllers\ProductController::class);
});

// CRUD Users untuk role Admin
// Route::middleware(['auth', 'role:Admin'])->group(function() {
//     Route::resource('users', \App\Http\Controllers\UserController::class);
// });
Route::middleware(['auth', 'role:Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function() {
        Route::resource('users', \App\Http\Controllers\UserController::class);
    });

// ProductAttribute
Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::resource('attributes', \App\Http\Controllers\Admin\ProductAttributeController::class);
});

Route::middleware(['auth', 'role:Manager Gudang'])->group(function () {
    Route::resource('suppliers', SupplierController::class);
});

require __DIR__.'/auth.php';