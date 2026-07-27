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
        $currentSession = ReadingSession::where('user_id', auth()->id())
            ->with('readingMaterial')
            ->inProgress()
            ->latest()
            ->first();

        $recentSessions = ReadingSession::where('user_id', auth()->id())
            ->with('readingMaterial')
            ->completed()
            ->latest()
            ->paginate(10);

        return view('reading-sessions.index', compact(
            'currentSession',
            'recentSessions'
        ));
    }

    public function start(ReadingMaterial $readingMaterial)
    {
        $this->authorizeAccessMaterial($readingMaterial);

        $existing = ReadingSession::where('user_id', auth()->id())
            ->inProgress()
            ->first();

        if ($existing) {
            return redirect()
                ->route('reading-sessions.index')
                ->with('error', 'You already have an active reading session.');
        }

        ReadingSession::create([
            'user_id' => auth()->id(),
            'reading_material_id' => $readingMaterial->id,
            'session_date' => now()->toDateString(),
            'start_time' => now()->format('H:i:s'),
            'status' => 'Active',
            'total_seconds' => 0,
        ]);

        return redirect()
            ->route('reading-sessions.index')
            ->with('success', 'Reading session started successfully.');
    }

    public function pause(Request $request, ReadingSession $readingSession)
    {
        $this->authorizeAccess($readingSession);

        if ($readingSession->status !== 'Active') {
            return redirect()->route('reading-sessions.index');
        }

        $totalSeconds = (int) $request->input('elapsed_seconds', 0);
        if ($totalSeconds <= 0) {
            $totalSeconds = $readingSession->total_seconds + now()->diffInSeconds($readingSession->updated_at);
        }

        $readingSession->update([
            'total_seconds' => $totalSeconds,
            'end_time' => now()->format('H:i:s'),
            'status' => 'Paused',
        ]);

        return redirect()->route('reading-sessions.index');
    }

    public function resume(ReadingSession $readingSession)
    {
        $this->authorizeAccess($readingSession);

        if ($readingSession->status !== 'Paused') {
            return redirect()->route('reading-sessions.index');
        }

        $readingSession->update([
            'end_time' => null,
            'status' => 'Active',
        ]);

        return redirect()->route('reading-sessions.index');
    }

    public function finish(Request $request, ReadingSession $readingSession)
    {
        $this->authorizeAccess($readingSession);

        if (!in_array($readingSession->status, ['Active', 'Paused'])) {
            return redirect()->route('reading-sessions.index');
        }

        $endTime = now();

        if ($readingSession->status === 'Active') {
            $totalSeconds = (int) $request->input('elapsed_seconds', 0);
            if ($totalSeconds > 0) {
                $readingSession->total_seconds = $totalSeconds;
            } else {
                $readingSession->total_seconds += now()->diffInSeconds($readingSession->updated_at);
            }
        }

        $durationMinutes = (int) round($readingSession->total_seconds / 60);

        $readingSession->update([
            'total_seconds' => $readingSession->total_seconds,
            'end_time' => $endTime->format('H:i:s'),
            'duration_minutes' => max($durationMinutes, 0),
            'status' => 'Completed',
        ]);

        return redirect()
            ->route('reading-sessions.index')
            ->with('success', 'Reading session completed.');
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

        return redirect()
            ->route('reading-sessions.index')
            ->with('success', 'Reading session created successfully.');
    }

    public function edit(ReadingSession $readingSession)
    {
        $this->authorizeAccess($readingSession);

        $readingMaterials = ReadingMaterial::where('user_id', auth()->id())
            ->orderBy('title')
            ->get();

        return view('reading-sessions.edit', compact(
            'readingSession',
            'readingMaterials'
        ));
    }

    public function update(UpdateReadingSessionRequest $request, ReadingSession $readingSession)
    {
        $this->authorizeAccess($readingSession);

        $readingSession->update($request->validated());

        return redirect()
            ->route('reading-sessions.index')
            ->with('success', 'Reading session updated successfully.');
    }

    public function destroy(ReadingSession $readingSession)
    {
        $this->authorizeAccess($readingSession);

        $readingSession->delete();

        return redirect()
            ->route('reading-sessions.index')
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