<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReadingMaterialController;
use App\Http\Controllers\ReadingGoalController;
use App\Http\Controllers\ReadingNoteController;
use App\Http\Controllers\ReadingSessionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('reading-materials', ReadingMaterialController::class);
    Route::post('reading-materials/{readingMaterial}/cover', [ReadingMaterialController::class, 'updateCover'])->name('reading-materials.cover');
    Route::resource('categories', CategoryController::class);
    Route::resource('authors', AuthorController::class);
    Route::post('reading-sessions/start/{readingGoal}', [ReadingSessionController::class, 'start'])->name('reading-sessions.start');
    Route::post('reading-sessions/pause/{readingSession}', [ReadingSessionController::class, 'pause'])->name('reading-sessions.pause');
    Route::post('reading-sessions/resume/{readingSession}', [ReadingSessionController::class, 'resume'])->name('reading-sessions.resume');
    Route::get('reading-sessions/finish/{readingSession}', [ReadingSessionController::class, 'confirmFinish'])->name('reading-sessions.confirm-finish');
    Route::post('reading-sessions/finish/{readingSession}', [ReadingSessionController::class, 'finish'])->name('reading-sessions.finish');
    Route::resource('reading-sessions', ReadingSessionController::class);
    Route::resource('reading-notes', ReadingNoteController::class);
    Route::resource('reading-goals', ReadingGoalController::class);
    Route::resource('bookmarks', BookmarkController::class)->only(['index', 'store', 'destroy']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
