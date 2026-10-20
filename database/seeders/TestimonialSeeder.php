<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = [
            ['Amina K.', 'Kenya', 'Safari Blue', 5, 'The day was well organised from pickup to the boat trip. The team was friendly, patient and made the whole experience feel relaxed.', true],
            ['Daniel M.', 'United Kingdom', 'Mnemba Island', 5, 'Communication was clear before the tour and the snorkelling experience was beautiful. Our guide took great care of the whole group.', true],
            ['Sofia R.', 'Italy', 'Spice Tour', 5, 'A wonderful way to learn more about Zanzibar. The guide was knowledgeable and explained everything in an engaging and friendly way.', false],
            ['James T.', 'South Africa', 'Prison Island', 4, 'Easy booking, punctual pickup and a helpful local guide. We enjoyed the history, the boat ride and the time with the tortoises.', false],
            ['Lina A.', 'Germany', 'Nakupenda Beach', 5, 'The sandbank was stunning and lunch was excellent. Everything felt personal and never rushed, which made the day very special.', false],
            ['Michael P.', 'United States', 'Tanzania Safari', 5, 'The team helped us plan the safari around our dates and preferences. The communication and attention to detail gave us confidence throughout.', false],
        ];

        foreach ($reviews as $index => [$name, $country, $tour, $rating, $content, $featured]) {
            Testimonial::query()->updateOrCreate(
                ['reference' => 'DEMO-TESTIMONIAL-'.str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)],
                [
                    'name' => $name,
                    'email' => 'demo'.($index + 1).'@example.invalid',
                    'country' => $country,
                    'tour_name' => $tour,
                    'rating' => $rating,
                    'content' => $content,
                    'status' => 'approved',
                    'is_featured' => $featured,
                    'approved_at' => now()->subDays($index),
                ],
            );
        }
    }
}
