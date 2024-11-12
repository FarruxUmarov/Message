<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/messages', [MessageController::class, 'index'])->middleware(['auth']);
Route::get('/messages/{id}', [MessageController::class, 'show'])->middleware(['auth'])->name('messages-show');
Route::post('/messages', [MessageController::class, 'store'])->middleware(['auth'])->name('messages-store');

require __DIR__.'/auth.php';
