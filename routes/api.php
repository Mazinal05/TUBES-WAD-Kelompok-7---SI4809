<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UmkmController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
*/

// UMKM API Endpoints
Route::get('/umkms', [UmkmController::class, 'index']);       // GET: Menampilkan semua UMKM
Route::post('/umkms', [UmkmController::class, 'store']);      // POST: Menambahkan UMKM baru
Route::delete('/umkms/{id}', [UmkmController::class, 'destroy']); // DELETE: Menghapus UMKM
