<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show');
Route::post('/articles/{id}/like', [ArticleController::class, 'like'])->name('articles.like');
Route::post('/articles/{id}/comment', [ArticleController::class, 'comment'])->name('articles.comment');
Route::get('/channels/{channel}', [ChannelController::class, 'show'])->name('channels.show');
Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');

Route::get('/dashboard', [ArticleController::class, 'dashboard'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/articles/photo/{filename}', [ArticleController::class, 'preview'])
    ->where('filename', '.*')
    ->name('articles.preview');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('channels', ChannelController::class)->only(['update', 'destroy']);
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('/users/{user}/make-moderator', [UserController::class, 'makeModerator'])->name('users.makeModerator');
    Route::post('/users/{user}/ban', [UserController::class, 'ban'])->name('users.ban');
});

Route::middleware(['auth', 'role:admin,moder'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/make-author', [UserController::class, 'makeAuthor'])->name('users.makeAuthor');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('articles', ArticleController::class)->except(['create', 'store']);
    Route::post('/articles/{article}/publish', [ArticleController::class, 'publish'])->name('articles.publish');
});

require __DIR__.'/auth.php';
