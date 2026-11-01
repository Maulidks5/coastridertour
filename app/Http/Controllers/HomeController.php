<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use App\Models\HomeContentItem;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\Tour;
use App\Models\TourCategory;
use App\Models\TourImage;
use App\Services\InstagramFeed;
use App\Support\TourPricing;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(InstagramFeed $instagramFeed): Response
    {
        $settings = SiteSetting::publicValues();
        $featuredTour = Tour::query()
            ->published()
            ->where('experience_type', '!=', 'safari')
            ->with('category:id,name,slug')
            ->orderByDesc('is_featured')
            ->orderByDesc('is_popular')
            ->orderBy('sort_order')
            ->first();

        $tours = Tour::query()
            ->published()
            ->where('experience_type', '!=', 'safari')
            ->when($featuredTour, fn ($query) => $query->whereKeyNot($featuredTour->id))
            ->with('category:id,name,slug')
            ->orderByDesc('is_popular')
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        $safaris = Tour::query()
            ->published()
            ->where('experience_type', 'safari')
            ->with('category:id,name,slug')
            ->orderByDesc('is_popular')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        return Inertia::render('Home', [
            'tours' => $tours->map(fn (Tour $tour): array => $this->tourCard($tour)),
            'safaris' => $safaris->map(fn (Tour $tour): array => $this->tourCard($tour)),
            'categories' => TourCategory::query()
                ->where('is_active', true)
                ->whereHas('tours', fn ($query) => $query->published())
                ->withCount(['tours' => fn ($query) => $query->published()])
                ->orderBy('sort_order')
                ->get()
                ->map(fn (TourCategory $category): array => [
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description,
                    'image' => $category->image_path ?: '/images/coast-rider-placeholder.jpg',
                    'toursCount' => $category->tours_count,
                ]),
            'featuredTour' => optional(
                $featuredTour,
                fn (Tour $tour): array => $this->tourCard($tour),
            ),
            'featuredHighlights' => optional(
                $featuredTour,
                fn (Tour $tour) => $tour->highlights()->orderBy('sort_order')->limit(5)->pluck('label'),
            ) ?? [],
            'homeItems' => HomeContentItem::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(['section', 'title', 'description', 'meta', 'icon'])
                ->groupBy('section')
                ->map(fn ($items) => $items->values()),
            'testimonials' => Testimonial::query()
                ->where('status', 'approved')
                ->orderByDesc('is_featured')
                ->orderByDesc('approved_at')
                ->limit(6)
                ->get(['name', 'country', 'tour_name', 'rating', 'content'])
                ->map(fn (Testimonial $item): array => [
                    'name' => $item->name,
                    'country' => $item->country,
                    'tourName' => $item->tour_name,
                    'rating' => $item->rating,
                    'content' => $item->content,
                ]),
            'galleryImages' => TourImage::query()
                ->orderByDesc('id')
                ->limit(6)
                ->get(['path', 'alt_text'])
                ->map(fn (TourImage $image): array => ['path' => $image->path, 'alt' => $image->alt_text ?? 'Zanzibar experience']),
            'instagramPosts' => $instagramFeed->latest(6),
            'heroSlides' => HeroSlide::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get()
                ->map(fn (HeroSlide $slide): array => [
                    'id' => $slide->id,
                    'eyebrow' => $slide->eyebrow,
                    'title' => $slide->title,
                    'description' => $slide->description,
                    'image' => $slide->image_path,
                    'imageAlt' => $slide->image_alt ?: $slide->title,
                    'imagePosition' => $slide->image_position,
                    'primaryLabel' => $slide->primary_label,
                    'primaryUrl' => $slide->primary_url,
                    'secondaryLabel' => $slide->secondary_label,
                    'secondaryUrl' => $slide->secondary_url,
                ]),
            'featuredVideo' => [
                'enabled' => ($settings['homepage_video_enabled'] ?? '1') === '1',
                'path' => $settings['homepage_video_path'] ?? '/videos/ngorongoro-elephants-hero.webm',
                'posterPath' => $settings['homepage_video_poster_path'] ?? '/images/experiences/hero-safari.webp',
                'title' => $settings['homepage_video_title'] ?? 'A glimpse of the wild, before you arrive',
                'description' => $settings['homepage_video_description'] ?? 'From Zanzibar\'s coast to Tanzania\'s iconic parks, every journey is planned with local care and private comfort.',
                'primaryLabel' => $settings['homepage_video_primary_label'] ?? 'Explore Tanzania safaris',
                'primaryUrl' => $settings['homepage_video_primary_url'] ?? '/safaris',
                'secondaryLabel' => $settings['homepage_video_secondary_label'] ?? 'Explore Zanzibar tours',
                'secondaryUrl' => $settings['homepage_video_secondary_url'] ?? '/tours',
            ],
            'content' => collect($settings)
                ->filter(fn ($value, $key) => str_starts_with($key, 'hero_')
                    || str_starts_with($key, 'popular_')
                    || str_starts_with($key, 'categories_')
                    || str_starts_with($key, 'why_')
                    || str_starts_with($key, 'testimonials_')
                    || str_starts_with($key, 'gallery_')
                    || str_starts_with($key, 'cta_')
                    || str_starts_with($key, 'contact_'))
                ->all(),
        ]);
    }

    private function tourCard(Tour $tour): array
    {
        return [
            'slug' => $tour->slug,
            'title' => $tour->title,
            'description' => $tour->excerpt,
            'duration' => $tour->duration,
            'location' => $tour->location,
            'rating' => (float) $tour->rating,
            'reviewsCount' => $tour->reviews_count,
            'price' => (float) $tour->price,
            'currency' => $tour->currency,
            'image' => $tour->cover_image_path ?: '/images/coast-rider-placeholder.jpg',
            'category' => $tour->category?->name,
            'experienceType' => $tour->experience_type,
            'minimumAge' => $tour->minimum_age,
            'difficulty' => $tour->difficulty,
            'pricing' => TourPricing::payload($tour),
        ];
    }
}
