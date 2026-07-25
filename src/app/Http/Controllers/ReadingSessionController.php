<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReadingSessionRequest;
use App\Http\Requests\UpdateReadingSessionRequest;
use App\Models\ReadingMaterial;
use App\Models\ReadingSession;

class ReadingSessionController extends Controller
{
    public function index()
    {
        $readingSessions = ReadingSession::where('user_id', auth()->id())
            ->with('readingMaterial')
            ->latest()
            ->paginate(12);

        return view('reading-sessions.index', compact('readingSessions'));
    }

    public function create()
    {
        $readingMaterials = ReadingMaterial::where('user_id', auth()->id())
            ->orderBy('title')
            ->get();

        return view('reading-sessions.create', compact('readingMaterials'));
    }

    public function store(StoreReadingSessionRequest $request)
    {
        ReadingSession::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('reading-sessions.index')
            ->with('success', 'Reading session created successfully.');
    }

    public function edit(ReadingSession $readingSession)
    {
        $this->authorizeAccess($readingSession);

        $readingMaterials = ReadingMaterial::where('user_id', auth()->id())
            ->orderBy('title')
            ->get();

        return view('reading-sessions.edit', compact('readingSession', 'readingMaterials'));
    }

    public function update(UpdateReadingSessionRequest $request, ReadingSession $readingSession)
    {
        $this->authorizeAccess($readingSession);

        $readingSession->update($request->validated());

        return redirect()->route('reading-sessions.index')
            ->with('success', 'Reading session updated successfully.');
    }

    public function destroy(ReadingSession $readingSession)
    {
        $this->authorizeAccess($readingSession);

        $readingSession->delete();

        return redirect()->route('reading-sessions.index')
            ->with('success', 'Reading session deleted successfully.');
    }

    private function authorizeAccess(ReadingSession $readingSession): void
    {
        if ($readingSession->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
