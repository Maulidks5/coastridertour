<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Support\WebImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class SiteMediaController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('Admin/Cms/Media', [
            'media' => SiteSetting::query()
                ->where('group', 'media')
                ->pluck('value', 'key'),
        ]);
    }

    public function update(Request $request, WebImageStorage $images): RedirectResponse
    {
        $validated = $request->validate([
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
            'cta_image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:8192'],
            'about_image' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:8192'],
            'homepage_video' => ['nullable', 'file', 'mimes:mp4,webm', 'max:51200'],
            'homepage_video_poster' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:8192'],
            'homepage_video_enabled' => ['required', 'boolean'],
            'homepage_video_title' => ['required', 'string', 'max:160'],
            'homepage_video_description' => ['required', 'string', 'max:1000'],
            'homepage_video_primary_label' => ['required', 'string', 'max:80'],
            'homepage_video_primary_url' => ['required', 'string', 'max:255'],
            'homepage_video_secondary_label' => ['required', 'string', 'max:80'],
            'homepage_video_secondary_url' => ['required', 'string', 'max:255'],
        ]);

        $mapping = [
            'logo' => ['logo_path', 900],
            'cta_image' => ['cta_image_path', 2200],
            'about_image' => ['about_image_path', 1800],
        ];

        foreach ($mapping as $field => [$key, $width]) {
            if (! $request->hasFile($field)) {
                continue;
            }

            $setting = SiteSetting::query()->where('key', $key)->firstOrFail();
            $oldPath = $setting->value;
            $path = $images->store($request->file($field), 'site', $width);
            $setting->update(['value' => '/storage/'.$path]);

            if ($oldPath && str_starts_with($oldPath, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $oldPath));
            }
        }

        if ($request->hasFile('homepage_video_poster')) {
            $setting = SiteSetting::query()->where('key', 'homepage_video_poster_path')->firstOrFail();
            $oldPath = $setting->value;
            $path = $images->store($request->file('homepage_video_poster'), 'site', 2200);
            $setting->update(['value' => '/storage/'.$path]);

            if ($oldPath && str_starts_with($oldPath, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $oldPath));
            }
        }

        if ($request->hasFile('homepage_video')) {
            $setting = SiteSetting::query()->where('key', 'homepage_video_path')->firstOrFail();
            $oldPath = $setting->value;
            $path = Storage::disk('public')->putFile('site-videos', $request->file('homepage_video'));
            $setting->update(['value' => '/storage/'.$path]);

            if ($oldPath && str_starts_with($oldPath, '/storage/')) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $oldPath));
            }
        }

        foreach ([
            'homepage_video_enabled' => $request->boolean('homepage_video_enabled') ? '1' : '0',
            'homepage_video_title' => $validated['homepage_video_title'],
            'homepage_video_description' => $validated['homepage_video_description'],
            'homepage_video_primary_label' => $validated['homepage_video_primary_label'],
            'homepage_video_primary_url' => $validated['homepage_video_primary_url'],
            'homepage_video_secondary_label' => $validated['homepage_video_secondary_label'],
            'homepage_video_secondary_url' => $validated['homepage_video_secondary_url'],
        ] as $key => $value) {
            SiteSetting::query()->where('key', $key)->update(['value' => $value]);
        }

        SiteSetting::forgetCache();

        return back()->with('success', 'Website media and featured video updated.');
    }
}
