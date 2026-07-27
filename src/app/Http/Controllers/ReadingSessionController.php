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
            ->with(['readingMaterial.author', 'readingMaterial.category'])
            ->inProgress()
            ->latest()
            ->first();

        $recentSessions = ReadingSession::where('user_id', auth()->id())
            ->with(['readingMaterial.author', 'readingMaterial.category'])
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
            ->where('reading_material_id', $readingMaterial->id)
            ->inProgress()
            ->first();

        if ($existing) {
            if ($existing->status === 'Paused') {
                $this->resumeSession($existing);
            }

            return redirect()->route('reading-sessions.index');
        }

        $otherActive = ReadingSession::where('user_id', auth()->id())
            ->inProgress()
            ->first();

        if ($otherActive) {
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
        ]);

        return redirect()
            ->route('reading-sessions.index')
            ->with('success', 'Reading session started successfully.');
    }

    public function pause(ReadingSession $readingSession)
    {
        $this->authorizeAccess($readingSession);

        if ($readingSession->status !== 'Active') {
            return redirect()->route('reading-sessions.index');
        }

        $readingSession->update([
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

        $this->resumeSession($readingSession);

        return redirect()->route('reading-sessions.index');
    }

    public function finish(ReadingSession $readingSession)
    {
        $this->authorizeAccess($readingSession);

        if (!in_array($readingSession->status, ['Active', 'Paused'])) {
            return redirect()->route('reading-sessions.index');
        }

        $endTime = now();

        $durationMinutes = 0;
        if ($readingSession->start_time) {
            $start = $readingSession->start_time->copy();
            $start->setDateFrom($endTime);

            if ($readingSession->status === 'Paused' && $readingSession->end_time) {
                $pausedEnd = $readingSession->end_time->copy();
                $pausedEnd->setDateFrom($endTime);
                $durationMinutes = (int) round($start->diffInMinutes($pausedEnd));
            } else {
                $durationMinutes = (int) round($start->diffInMinutes($endTime, true));
            }
        }

        $readingSession->update([
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

    private function resumeSession(ReadingSession $session): void
    {
        $now = now();

        if ($session->end_time && $session->start_time) {
            $adjustedStart = $session->start_time->copy();
            $adjustedStart->setDateFrom($now);

            $pausedEnd = $session->end_time->copy();
            $pausedEnd->setDateFrom($now);

            $pauseDuration = $pausedEnd->diffInSeconds($now);
            $adjustedStart = $adjustedStart->addSeconds($pauseDuration);

            $session->start_time = $adjustedStart->format('H:i:s');
        }

        $session->end_time = null;
        $session->status = 'Active';
        $session->save();
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