<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeGalleryImage;
use App\Models\Tour;
use App\Support\WebImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class HomeGalleryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Cms/HomeGallery', [
            'images' => HomeGalleryImage::query()
                ->with('tour:id,title,slug')
                ->orderByDesc('id')
                ->get(['id', 'path', 'alt_text', 'tour_id', 'sort_order', 'is_active']),
            'tours' => Tour::query()
                ->published()
                ->orderBy('experience_type')
                ->orderBy('title')
                ->get(['id', 'title', 'slug']),
        ]);
    }

    public function store(Request $request, WebImageStorage $images): RedirectResponse
    {
        $validated = $request->validate([
            'images' => ['required', 'array', 'min:1', 'max:12'],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:15360'],
        ]);

        $nextOrder = ((int) HomeGalleryImage::query()->max('sort_order')) + 1;

        foreach ($validated['images'] as $position => $file) {
            $path = $images->store($file, 'home-gallery', 1800);
            HomeGalleryImage::query()->create([
                'path' => '/storage/'.$path,
                'alt_text' => 'Coast Rider travel experience',
                'sort_order' => $nextOrder + $position,
                'is_active' => true,
            ]);
        }

        return back()->with('success', 'Homepage gallery images uploaded.');
    }

    public function update(Request $request, HomeGalleryImage $image): RedirectResponse
    {
        $validated = $request->validate([
            'alt_text' => ['nullable', 'string', 'max:190'],
            'tour_id' => ['nullable', 'integer', 'exists:tours,id'],
            'is_active' => ['required', 'boolean'],
        ]);

        $image->update($validated);

        return back()->with('success', 'Gallery image updated.');
    }

    public function destroy(HomeGalleryImage $image): RedirectResponse
    {
        if (Str::startsWith($image->path, '/storage/')) {
            Storage::disk('public')->delete(Str::after($image->path, '/storage/'));
        }

        $image->delete();

        return back()->with('success', 'Gallery image removed.');
    }
}
