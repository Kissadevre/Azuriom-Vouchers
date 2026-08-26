<?php

use Azuriom\Plugin\Vouchers\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;

Route::get('/', [VoucherController::class, 'index'])->name('index');
Route::post('/redeem', [VoucherController::class, 'redeem'])
    ->middleware(['throttle:vouchers-redeem', 'captcha'])
    ->name('redeem');
