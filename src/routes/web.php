<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReadingMaterialController;
use App\Http\Controllers\ReadingGoalController;
use App\Http\Controllers\ReadingNoteController;
use App\Http\Controllers\ReadingSessionController;
use App\Models\ReadingGoal;
use App\Models\ReadingMaterial;
use App\Models\ReadingNote;
use App\Models\ReadingSession;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        $userId = auth()->id();

        $totalMaterials = ReadingMaterial::where('user_id', $userId)->count();
        $totalSessions = ReadingSession::where('user_id', $userId)->count();
        $totalNotes = ReadingNote::where('user_id', $userId)->count();
        $activeGoals = ReadingGoal::where('user_id', $userId)->where('status', 'active')->count();

        $recentSessions = ReadingSession::where('user_id', $userId)
            ->with('readingMaterial')
            ->latest()
            ->take(5)
            ->get();

        $recentNotes = ReadingNote::where('user_id', $userId)
            ->with('readingMaterial')
            ->latest()
            ->take(5)
            ->get();

        $activeReadingGoals = ReadingGoal::where('user_id', $userId)
            ->where('status', 'active')
            ->with('readingMaterial')
            ->latest()
            ->get();

        return view('dashboard', compact(
            'totalMaterials',
            'totalSessions',
            'totalNotes',
            'activeGoals',
            'recentSessions',
            'recentNotes',
            'activeReadingGoals',
        ));
    })->name('dashboard');

    Route::resource('reading-materials', ReadingMaterialController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('authors', AuthorController::class);
    Route::resource('reading-sessions', ReadingSessionController::class);
    Route::resource('reading-notes', ReadingNoteController::class);
    Route::resource('reading-goals', ReadingGoalController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
