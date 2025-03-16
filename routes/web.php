<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShoeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;

// Rute utama
Route::get('/', function () {
    return view('welcome');
});

// Rute untuk dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Rute untuk manajemen inventory (sepatu)
Route::resource('shoes', ShoeController::class)->except(['show']);
Route::get('/shoes/{shoe}/print-barcode', [ShoeController::class, 'printBarcode'])->name('shoes.print-barcode');
Route::post('/scan-barcode', [ShoeController::class, 'scanBarcode'])->name('shoes.scan-barcode');
Route::get('/shoes/barcode/{id}', [ShoeController::class, 'showBarcode'])->name('shoes.barcode');

// Rute untuk penjualan/kasir
Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::post('/transactions/add-to-cart', [TransactionController::class, 'addToCart'])->name('transactions.add-to-cart');
Route::post('/transactions/manual-add', [TransactionController::class, 'manualAdd'])->name('transactions.manual-add');
Route::post('/transactions/checkout', [TransactionController::class, 'checkout'])->name('transactions.checkout');
Route::post('/transactions/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');