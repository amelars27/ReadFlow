<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReadingNoteRequest;
use App\Http\Requests\UpdateReadingNoteRequest;
use App\Models\ReadingMaterial;
use App\Models\ReadingNote;

class ReadingNoteController extends Controller
{
    public function index()
    {
        $readingNotes = ReadingNote::where('user_id', auth()->id())
    ->with([
        'readingMaterial.author',
        'readingMaterial.category',
    ])
    ->latest()
    ->paginate(9);
        return view('reading-notes.index', compact('readingNotes'));
    }

    public function show(ReadingNote $readingNote)
{
    $this->authorizeAccess($readingNote);

    $readingNote->load([
        'readingMaterial.author',
        'readingMaterial.category',
    ]);

    return view('reading-notes.show', compact('readingNote'));
}

    public function create()
    {
        $readingMaterials = ReadingMaterial::where('user_id', auth()->id())
            ->orderBy('title')
            ->get();

        return view('reading-notes.create', compact('readingMaterials'));
    }

    public function store(StoreReadingNoteRequest $request)
    {
        ReadingNote::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('reading-notes.index')
            ->with('success', 'Reading note created successfully.');
    }

    public function edit(ReadingNote $readingNote)
    {
        $this->authorizeAccess($readingNote);

        $readingMaterials = ReadingMaterial::where('user_id', auth()->id())
            ->orderBy('title')
            ->get();

        return view('reading-notes.edit', compact('readingNote', 'readingMaterials'));
    }

    public function update(UpdateReadingNoteRequest $request, ReadingNote $readingNote)
    {
        $this->authorizeAccess($readingNote);

        $readingNote->update($request->validated());

        return redirect()->route('reading-notes.index')
            ->with('success', 'Reading note updated successfully.');
    }

    public function destroy(ReadingNote $readingNote)
    {
        $this->authorizeAccess($readingNote);

        $readingNote->delete();

        return redirect()->route('reading-notes.index')
            ->with('success', 'Reading note deleted successfully.');
    }

    private function authorizeAccess(ReadingNote $readingNote): void
    {
        if ($readingNote->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
