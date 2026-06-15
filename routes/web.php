<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\KioskController;
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\AdminController;

// TV Display routes
Route::get('/', [DisplayController::class, 'index'])->name('display.index');
Route::get('/api/state', [DisplayController::class, 'state'])->name('display.state');

// Kiosk routes
Route::get('/kiosk', [KioskController::class, 'index'])->name('kiosk.index');
Route::post('/api/kiosk/issue', [KioskController::class, 'issue'])->name('kiosk.issue');

// Operator routes
Route::get('/operator', [OperatorController::class, 'index'])->name('operator.index');
Route::post('/api/operator/call-next', [OperatorController::class, 'callNext'])->name('operator.call-next');
Route::post('/api/operator/recall', [OperatorController::class, 'recall'])->name('operator.recall');
Route::get('/api/operator/stats', [OperatorController::class, 'stats'])->name('operator.stats');

// Admin routes
Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
Route::post('/api/admin/settings', [AdminController::class, 'updateSettings'])->name('admin.update-settings');
Route::post('/api/admin/counters', [AdminController::class, 'storeCounter'])->name('admin.store-counter');
Route::post('/api/admin/counters/delete', [AdminController::class, 'deleteCounter'])->name('admin.delete-counter');
Route::post('/api/admin/reset', [AdminController::class, 'reset'])->name('admin.reset');
Route::post('/api/admin/slideshow/upload', [AdminController::class, 'uploadSlideshowImage'])->name('admin.slideshow.upload');
Route::post('/api/admin/slideshow/delete', [AdminController::class, 'deleteSlideshowImage'])->name('admin.slideshow.delete');
