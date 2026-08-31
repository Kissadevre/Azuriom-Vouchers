<?php

use Azuriom\Plugin\Vouchers\Controllers\Admin\VoucherController;
use Azuriom\Plugin\Vouchers\Controllers\Admin\RedemptionController;
use Azuriom\Plugin\Vouchers\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

Route::middleware('can:vouchers.admin')->group(function () {
    Route::get('/settings', [SettingController::class, 'show'])->name('settings');
    Route::post('/settings', [SettingController::class, 'save'])->name('settings.save');
    Route::post('/settings/webhook/test', [SettingController::class, 'testWebhook'])->name('settings.webhook.test');
    Route::get('/redemptions', [RedemptionController::class, 'index'])->name('redemptions.index');
    Route::get('/', [VoucherController::class, 'index'])->name('codes.index');
    Route::get('/index', [VoucherController::class, 'index'])->name('index');
    Route::get('/create', [VoucherController::class, 'create'])->name('codes.create');
    Route::post('/', [VoucherController::class, 'store'])->name('codes.store');
    Route::post('/generate', [VoucherController::class, 'generate'])->name('codes.generate');
    Route::get('/{voucher}/edit', [VoucherController::class, 'edit'])->name('codes.edit');
    Route::patch('/{voucher}/disable', [VoucherController::class, 'disable'])->name('codes.disable');
    Route::put('/{voucher}', [VoucherController::class, 'update'])->name('codes.update');
    Route::delete('/{voucher}', [VoucherController::class, 'destroy'])->name('codes.destroy');
});
