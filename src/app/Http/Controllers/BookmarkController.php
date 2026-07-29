<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookmarkRequest;
use App\Models\Bookmark;

class BookmarkController extends Controller
{
    public function index()
    {
        $bookmarks = Bookmark::where('user_id', auth()->id())
            ->with('readingMaterial.author', 'readingMaterial.category')
            ->latest('created_at')
            ->paginate(10);

        return view('bookmarks.index', compact('bookmarks'));
    }

   public function store(StoreBookmarkRequest $request)
{
    Bookmark::firstOrCreate(
        [
            'reading_material_id' => $request->reading_material_id,
            'user_id' => auth()->id(),
        ]
    );

    return redirect()->back()->with('success', 'Bookmark added successfully.');
}
    public function destroy(Bookmark $bookmark)
    {
        if ($bookmark->user_id !== auth()->id()) {
            abort(403);
        }

        $bookmark->delete();

        return redirect()->back()->with('success', 'Bookmark removed successfully.');
    }
}
