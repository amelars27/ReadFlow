<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReadingGoalRequest;
use App\Http\Requests\UpdateReadingGoalRequest;
use App\Models\ReadingGoal;
use App\Models\ReadingMaterial;

class ReadingGoalController extends Controller
{
    public function index()
    {
        $readingGoals = ReadingGoal::where('user_id', auth()->id())
            ->with(['readingSessions' => function ($query) {
                $query->where('status', 'Completed')
                      ->select('id', 'reading_goal_id', 'end_page', 'duration_minutes', 'total_seconds', 'created_at');
            }])
            ->latest()
            ->paginate(10);

        return view('reading-goals.index', compact('readingGoals'));
    }

    public function create()
    {
        $readingMaterials = ReadingMaterial::where('user_id', auth()->id())
            ->orderBy('title')
            ->get();

        return view('reading-goals.create', compact('readingMaterials'));
    }

    public function store(StoreReadingGoalRequest $request)
    {
        ReadingGoal::create([
            ...$request->validated(),
            'current_value' => 0,
            'status' => 'active',
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('reading-goals.index')
            ->with('success', 'Reading goal created successfully.');
    }

    public function show(ReadingGoal $readingGoal)
    {
        $this->authorizeAccess($readingGoal);

        $readingGoal->load([
            'readingMaterial.author',
            'readingMaterial.category',
            'readingSessions' => function ($query) {
                $query->where('status', 'Completed');
            },
        ]);

        return view('reading-goals.show', compact('readingGoal'));
    }

    public function edit(ReadingGoal $readingGoal)
    {
        $this->authorizeAccess($readingGoal);

        $readingMaterials = ReadingMaterial::where('user_id', auth()->id())
            ->orderBy('title')
            ->get();

        return view('reading-goals.edit', compact('readingGoal', 'readingMaterials'));
    }

    public function update(UpdateReadingGoalRequest $request, ReadingGoal $readingGoal)
    {
        $this->authorizeAccess($readingGoal);

        $data = $request->validated();

        $currentValue = min($data['current_value'] ?? 0, $data['target_value']);
        $status = $currentValue >= $data['target_value'] ? 'completed' : 'active';

        $readingGoal->update([
            ...$data,
            'current_value' => $currentValue,
            'status' => $status,
        ]);

        return redirect()->route('reading-goals.index')
            ->with('success', 'Reading goal updated successfully.');
    }

    public function destroy(ReadingGoal $readingGoal)
    {
        $this->authorizeAccess($readingGoal);

        $readingGoal->delete();

        return redirect()->route('reading-goals.index')
            ->with('success', 'Reading goal deleted successfully.');
    }

    private function authorizeAccess(ReadingGoal $readingGoal): void
    {
        if ($readingGoal->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
