<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Member Management
    Route::resource('members', MemberController::class);
    
    // Product Management
    Route::resource('products', ProductController::class);
    
    // Point of Sale
    Route::controller(PosController::class)->prefix('pos')->name('pos.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/transactions', 'store')->name('store');
        Route::get('/receipt/{transaction}', 'show')->name('receipt');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
