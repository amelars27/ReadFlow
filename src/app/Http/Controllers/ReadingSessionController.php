<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReadingSessionRequest;
use App\Http\Requests\UpdateReadingSessionRequest;
use App\Models\ReadingMaterial;
use App\Models\ReadingSession;
use Illuminate\Http\Request;

class ReadingSessionController extends Controller
{
    public function index()
    {
        $activeSession = ReadingSession::where('user_id', auth()->id())
            ->with('readingMaterial.author', 'readingMaterial.category')
            ->active()
            ->latest()
            ->first();

        $recentSessions = ReadingSession::where('user_id', auth()->id())
            ->with('readingMaterial.author', 'readingMaterial.category')
            ->completed()
            ->latest()
            ->paginate(10);

        return view('reading-sessions.index', compact('activeSession', 'recentSessions'));
    }

    public function start(ReadingMaterial $readingMaterial)
    {
        $this->authorizeAccessMaterial($readingMaterial);

        $existing = ReadingSession::where('user_id', auth()->id())
            ->active()
            ->first();

        if ($existing) {
            return redirect()->route('reading-sessions.index')
                ->with('error', 'You already have an active reading session.');
        }

        ReadingSession::create([
            'user_id' => auth()->id(),
            'reading_material_id' => $readingMaterial->id,
            'session_date' => now()->toDateString(),
            'start_time' => now()->format('H:i'),
            'status' => 'Active',
        ]);

        return redirect()->route('reading-sessions.index')
            ->with('success', 'Reading session started successfully.');
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

    private function authorizeAccessMaterial(ReadingMaterial $readingMaterial): void
    {
        if ($readingMaterial->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
