<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalMovies = Movie::where('user_id', $user->id)->count();
        $totalGenres = Genre::count();
        $totalWatched = Movie::where('user_id', $user->id)->where('status', 'watched')->count();
        $totalWatching = Movie::where('user_id', $user->id)->where('status', 'watching')->count();
        $totalPlanToWatch = Movie::where('user_id', $user->id)->where('status', 'plan_to_watch')->count();
        $recentMovies = Movie::where('user_id', $user->id)
            ->with('genre')
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', compact(
            'totalMovies', 'totalGenres', 'totalWatched',
            'totalWatching', 'totalPlanToWatch', 'recentMovies'
        ));
    }
}
