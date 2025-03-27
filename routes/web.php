<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShoeController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('shoes', ShoeController::class)->except(['show']);
Route::get('/shoes/{shoe}/print-barcode', [ShoeController::class, 'printBarcode'])->name('shoes.print-barcode');
Route::post('/scan-barcode', [ShoeController::class, 'scanBarcode'])->name('shoes.scan-barcode');
Route::get('/shoes/qr-detail/{barcode}', [ShoeController::class, 'showFromBarcode'])->name('shoes.qr-detail');

Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::post('/transactions/add-to-cart', [TransactionController::class, 'addToCart'])->name('transactions.add-to-cart');
Route::post('/transactions/checkout', [TransactionController::class, 'checkout'])->name('transactions.checkout');
Route::post('/transactions/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');
