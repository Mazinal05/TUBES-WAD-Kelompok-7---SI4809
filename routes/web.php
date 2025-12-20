<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UmkmController;
use App\Http\Controllers\AdminUmkmController;
use App\Http\Controllers\AuthController;

// 1. HALAMAN PUBLIK (Dashboard & Detail)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/umkm/{id}', [HomeController::class, 'show'])->name('umkm.show');

// 2. AUTHENTICATION (Login/Register Sederhana)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. FITUR USER (Harus Login)
Route::middleware(['auth'])->group(function () {
    Route::post('/umkm/{id}/order', [UmkmController::class, 'processOrder'])->name('umkm.order');
});

// 4. FITUR ADMIN (Harus Login & Role Admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('umkms', AdminUmkmController::class);
});

Route::middleware(['auth'])->group(function () {
    // ... route order yang lama ...
    Route::post('/umkm/{id}/review', [UmkmController::class, 'storeReview'])->name('umkm.review');
});