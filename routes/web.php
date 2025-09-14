<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\ProfileController;

// 認証ルート（Breeze）
require __DIR__.'/auth.php';

// プロファイル関連ルート（Breeze）
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// /dashboardルートは削除（Breezeのデフォルト動作を無効化）

// 管理ダッシュボード（ログイン+管理者限定）
Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        $applications = \App\Models\Application::withCount('contacts')
                                             ->where('user_id', auth()->id())
                                             ->get();
        $totalContacts = \App\Models\Contact::whereHas('application', fn($q) => $q->where('user_id', auth()->id()))->count();
        $totalApplications = \App\Models\Application::where('user_id', auth()->id())->count();
        
        return view('admin.dashboard', compact('applications', 'totalContacts', 'totalApplications'));
    })->name('dashboard');

    Route::resource('applications', ApplicationController::class)
        ->except(['show']);

    Route::resource('contacts', ContactController::class)
        ->only(['index','show','destroy']);
});

// 公開フォーム（ログイン不要）
use App\Http\Controllers\ContactController as PublicContactController;
Route::get('/contact/{app}', [PublicContactController::class, 'showForm'])->name('contacts.form');
Route::post('/contact/{app}', [PublicContactController::class, 'store'])->name('contacts.store');
Route::get('/contact/{app}/thanks', [PublicContactController::class, 'thanks'])->name('contacts.thanks');

Route::view('/', 'welcome');
