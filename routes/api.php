<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContactController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// API問い合わせ登録
Route::middleware(['api.key', 'throttle:api'])->group(function () {
    Route::post('/contacts', [ContactController::class, 'store'])->name('api.contacts.store');
});

// API統計情報（認証が必要）
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/stats/contacts', [ContactController::class, 'stats'])->name('api.stats.contacts');
});
