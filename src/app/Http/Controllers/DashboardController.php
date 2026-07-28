<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ReadingGoal;
use App\Models\ReadingMaterial;
use App\Models\ReadingNote;
use App\Models\ReadingSession;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $totalMaterials = ReadingMaterial::where('user_id', $userId)->count();
        $totalSessions = ReadingSession::where('user_id', $userId)->count();
        $totalNotes = ReadingNote::where('user_id', $userId)->count();
        $activeGoals = ReadingGoal::where('user_id', $userId)->where('status', 'active')->count();

        $recentSessions = ReadingSession::where('user_id', $userId)
        ->with('readingGoal.readingMaterial')
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

        $allGoals = ReadingGoal::where('user_id', $userId)->get();
        $averageProgress = $allGoals->count() > 0
            ? round($allGoals->avg(fn ($g) => $g->target_value > 0 ? min(100, ($g->current_value / $g->target_value) * 100) : 0))
            : 0;

        $materialsByCategory = ReadingMaterial::where('user_id', $userId)
            ->with('category')
            ->get()
            ->groupBy(fn ($m) => $m->category->name ?? 'Uncategorized')
            ->map(fn ($group) => $group->count());

        $days = collect(range(6, 0))->map(fn ($i) => now()->subDays($i)->format('Y-m-d'));
        $sessionsByDay = ReadingSession::where('user_id', $userId)
            ->whereBetween('session_date', [$days->first(), $days->last()])
            ->selectRaw('session_date, count(*) as total')
            ->groupBy('session_date')
            ->pluck('total', 'session_date');
        $sessionCounts = $days->map(fn ($day) => $sessionsByDay->get($day, 0));

        return view('dashboard', compact(
            'totalMaterials',
            'totalSessions',
            'totalNotes',
            'activeGoals',
            'recentSessions',
            'recentNotes',
            'activeReadingGoals',
            'averageProgress',
            'materialsByCategory',
            'days',
            'sessionCounts',
        ));
    }
}
