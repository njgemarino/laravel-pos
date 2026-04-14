<?php
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {

    // ADMIN ONLY
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::get('/reports/daily-sales', [ReportController::class, 'dailySales'])->name('reports.daily-sales');
        Route::get('/reports/daily-sales/export', [ReportController::class, 'exportDailySales'])->name('reports.daily-sales.export');
        Route::get('/reports/monthly-sales', [ReportController::class, 'monthlySales'])->name('reports.monthly-sales');
        });

    // ADMIN + INVENTORY STAFF
    Route::middleware(['role:admin,inventory'])->group(function () {
        Route::resource('products', ProductController::class);
        Route::middleware(['role:admin,inventory'])->group(function () {
        Route::resource('products', ProductController::class);
        
        Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
        Route::post('/inventory/{product}/update-stock', [InventoryController::class, 'updateStock'])->name('inventory.updateStock');
        Route::get('/inventory/logs', [InventoryController::class, 'logs'])->name('inventory.logs');
        Route::get('/inventory/logs/export', [InventoryController::class, 'export'])->name('inventory.logs.export');
});
    });

    // ADMIN + CASHIER
    Route::middleware(['role:admin,cashier'])->group(function () {
        Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
        Route::post('/sales', [SalesController::class, 'store'])->name('sales.store');
        Route::get('/sales/receipt/{id}', [SalesController::class, 'receipt'])->name('sales.receipt');

        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::get('/transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
    });
});

require __DIR__.'/auth.php';