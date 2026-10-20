<?php

namespace Database\Seeders;

use App\Models\HomeContentItem;
use Illuminate\Database\Seeder;

class HomeContentSeeder extends Seeder
{
    public function run(): void
    {
        if (HomeContentItem::query()->exists()) {
            return;
        }

        $items = [
            ['trust', 'Local Zanzibar Guides', null, null, 'users'],
            ['trust', 'Private Transport Available', null, null, 'car'],
            ['trust', 'Flexible Booking', null, null, 'calendar'],
            ['trust', 'Fast WhatsApp Support', null, null, 'message'],
            ['why', 'Experienced Local Guides', 'Friendly local guides who know these islands.', null, 'award'],
            ['why', 'Reliable Private Transport', 'Comfortable, on-time airport, hotel and destination transfers.', null, 'car'],
            ['why', 'Competitive Prices', 'Clear local pricing confirmed before your trip.', null, 'sparkles'],
            ['why', 'Personalized Experiences', 'Tours tailored to families, couples and groups.', null, 'heart'],
            ['why', 'Fast WhatsApp Support', 'Direct WhatsApp support from our local team.', null, 'message'],
            ['why', 'Safe Journeys', 'Safety details are confirmed for each selected experience.', null, 'shield'],
            ['testimonial', 'Sophie Laurent', 'Safari Blue was the highlight of our honeymoon. The team took care of every detail — pure magic on the ocean.', 'France', null],
            ['testimonial', 'James Miller', 'Professional, warm and reliable. WhatsApp booking was instant and pickup was exactly on time.', 'United Kingdom', null],
            ['testimonial', 'Amara Okafor', 'The guides made Zanzibar feel like home. We saw dolphins at Mnemba and loved every minute.', 'Nigeria', null],
        ];

        foreach ($items as $index => [$section, $title, $description, $meta, $icon]) {
            HomeContentItem::query()->create(compact('section', 'title', 'description', 'meta', 'icon') + [
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }
    }
}
