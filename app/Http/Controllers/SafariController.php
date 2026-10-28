<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Models\Tour;
use App\Support\TourPricing;
use Inertia\Inertia;
use Inertia\Response;

class SafariController extends Controller
{
    public function __invoke(): Response
    {
        $settings = SiteSetting::publicValues();

        return Inertia::render('Safaris', [
            'pageTitle' => $settings['safaris_title'] ?? 'Safari journeys planned around you',
            'pageDescription' => $settings['safaris_description'] ?? 'Tell us your dates, preferred parks and travel style.',
            'ideas' => Tour::query()
                ->published()
                ->where('experience_type', 'safari')
                ->where('is_featured', true)
                ->with('highlights:id,tour_id,label,sort_order')
                ->orderBy('sort_order')
                ->get()
                ->map(function (Tour $tour): array {
                    $duration = strtolower($tour->duration);
                    $kind = match (true) {
                        $tour->slug === 'custom-tanzania-safari', str_contains($duration, 'choice'), str_contains($duration, 'multi') => 'custom',
                        preg_match('/(?:[2-9]|\d{2,})\s*[–-]?\s*(?:day|night)/', $duration) === 1,
                        str_contains($duration, '7+ days') => 'overnight',
                        default => 'day_trip',
                    };

                    return [
                    'slug' => $tour->slug,
                    'title' => $tour->title,
                    'description' => $tour->excerpt,
                    'duration' => $tour->duration,
                    'image' => $tour->cover_image_path ?: '/images/coast-rider-placeholder.jpg',
                    'location' => $tour->location,
                    'price' => (float) $tour->price,
                    'currency' => $tour->currency,
                    'kind' => $kind,
                    'pricing' => TourPricing::payload($tour),
                    'highlights' => $tour->highlights->pluck('label')->values(),
                    ];
                }),
        ]);
    }
}
