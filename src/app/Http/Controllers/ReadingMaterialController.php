<?php

namespace App\Http\Controllers;

use App\Enums\ReadingStatus;
use App\Enums\SourceType;
use App\Http\Requests\StoreReadingMaterialRequest;
use App\Http\Requests\UpdateReadingMaterialRequest;
use App\Models\Author;
use App\Models\Bookmark;
use App\Models\Category;
use App\Models\ReadingMaterial;

class ReadingMaterialController extends Controller
{
    public function index()
    {
        $readingMaterials = ReadingMaterial::where('user_id', auth()->id())
            ->with(['author', 'category'])
            ->latest()
            ->paginate(12);

        $bookmarks = Bookmark::where('user_id', auth()->id())
            ->pluck('id', 'reading_material_id');

        return view('reading-materials.index', compact('readingMaterials', 'bookmarks'));
    }

    public function create()
    {
        $authors = Author::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $sourceTypes = SourceType::cases();
        $statuses = ReadingStatus::cases();

        return view('reading-materials.create', compact('authors', 'categories', 'sourceTypes', 'statuses'));
    }

    public function store(StoreReadingMaterialRequest $request)
    {
        ReadingMaterial::create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('reading-materials.index')
            ->with('success', 'Reading material created successfully.');
    }

    public function show(ReadingMaterial $readingMaterial)
    {
        $this->authorizeAccess($readingMaterial);

        $readingMaterial->load(['author', 'category']);

        return view('reading-materials.show', compact('readingMaterial'));
    }

    public function edit(ReadingMaterial $readingMaterial)
    {
        $this->authorizeAccess($readingMaterial);

        $authors = Author::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $sourceTypes = SourceType::cases();
        $statuses = ReadingStatus::cases();

        return view('reading-materials.edit', compact('readingMaterial', 'authors', 'categories', 'sourceTypes', 'statuses'));
    }

    public function update(UpdateReadingMaterialRequest $request, ReadingMaterial $readingMaterial)
    {
        $this->authorizeAccess($readingMaterial);

        $readingMaterial->update($request->validated());

        return redirect()->route('reading-materials.index')
            ->with('success', 'Reading material updated successfully.');
    }

    public function destroy(ReadingMaterial $readingMaterial)
    {
        $this->authorizeAccess($readingMaterial);

        $readingMaterial->delete();

        return redirect()->route('reading-materials.index')
            ->with('success', 'Reading material deleted successfully.');
    }

    private function authorizeAccess(ReadingMaterial $readingMaterial): void
    {
        if ($readingMaterial->user_id !== auth()->id()) {
            abort(403);
        }
    }
}
