<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ChannelController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show');
Route::post('/articles/{id}/like', [ArticleController::class, 'like'])->name('articles.like');
Route::post('/articles/{id}/comment', [ArticleController::class, 'comment'])->name('articles.comment');

Route::get('/channel/{id}', [ChannelController::class, 'show'])->name('channel.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('channels', ChannelController::class)->only(['update', 'destroy']);
});

require __DIR__.'/auth.php';
