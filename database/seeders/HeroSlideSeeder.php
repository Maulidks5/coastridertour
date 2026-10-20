<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        if (HeroSlide::query()->exists()) {
            return;
        }

        $slides = [
            ['Zanzibar · Tanzania', 'Your journey, beautifully planned.', 'Discover Zanzibar and Tanzania through thoughtful tours, unforgettable safaris and private transport you can rely on.', '/images/experiences/hero-zanzibar-dhow.webp', 'Find your experience', '/tours'],
            ['Ocean Adventures', 'Sail, Snorkel and Explore', 'Discover Safari Blue, Mnemba reefs and pristine sandbanks with trusted local guides.', '/images/experiences/hero-ocean.webp', 'View Ocean Tours', '/tours'],
            ['Mainland Tanzania', 'Safari Journeys Worth Remembering', 'Plan a personal wildlife adventure through Tanzania’s iconic national parks.', '/images/experiences/hero-safari.webp', 'Explore Safaris', '/safaris'],
        ];

        foreach ($slides as $index => [$eyebrow, $title, $description, $imagePath, $primaryLabel, $primaryUrl]) {
            HeroSlide::query()->create([
                'eyebrow' => $eyebrow,
                'title' => $title,
                'description' => $description,
                'image_path' => $imagePath,
                'image_alt' => $title,
                'image_position' => 'center',
                'primary_label' => $primaryLabel,
                'primary_url' => $primaryUrl,
                'secondary_label' => 'Chat on WhatsApp',
                'secondary_url' => 'whatsapp',
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }
    }
}
