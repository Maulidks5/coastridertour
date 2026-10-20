<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_faqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->string('question');
            $table->text('answer');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $categories = DB::table('tour_categories')->pluck('id', 'slug');
        $packages = [
            ['spice-prison-stone-town', 'Spice Farm, Prison Island & Stone Town', 'culture-local-life', 'Full Day', 'Stone Town & Zanzibar', '/images/experiences/spice-tour.webp', 'Discover Zanzibar’s spices, island history and Stone Town heritage in one thoughtfully planned private day.', 'zanzibar_tour'],
            ['prison-island-nakupenda', 'Prison Island & Nakupenda Sandbank', 'island-ocean-tours', 'Full Day', 'Stone Town Coast', '/images/experiences/prison-island.webp', 'Meet the giant tortoises before relaxing, swimming and enjoying the turquoise water at Nakupenda Sandbank.', 'zanzibar_tour'],
            ['nungwi-turtles-sunset', 'Nungwi Turtles & Sunset Dhow', 'beaches-coastal', 'Full Day', 'North Zanzibar', '/images/experiences/nungwi-aquarium.webp', 'Combine Nungwi’s turtle sanctuary and northern beaches with a memorable sunset dhow experience.', 'zanzibar_tour'],
            ['safari-zanzibar-beach-escape', 'Tanzania Safari & Zanzibar Beach Escape', 'tanzania-safaris', '7+ Days', 'Mainland Tanzania & Zanzibar', '/images/experiences/tanzania-safaris.webp', 'Pair a tailor-made mainland wildlife safari with relaxed beach days in Zanzibar, planned as one seamless journey.', 'safari'],
        ];
        $sort = (int) DB::table('tours')->max('sort_order');
        foreach ($packages as $index => [$slug, $title, $category, $duration, $location, $image, $excerpt, $type]) {
            if (! isset($categories[$category])) continue;
            DB::table('tours')->insert([
                'tour_category_id' => $categories[$category], 'experience_type' => $type, 'title' => $title, 'slug' => $slug,
                'excerpt' => $excerpt, 'description' => $excerpt.' Share your dates, hotel and group size so our team can tailor the route, timing and final inclusions.',
                'duration' => $duration, 'location' => $location, 'price' => 0, 'currency' => 'USD', 'rating' => 0, 'reviews_count' => 0,
                'cover_image_path' => $image, 'pickup_details' => 'Pickup, transfers and the most practical starting time are confirmed in your personal quote.',
                'minimum_age' => null, 'difficulty' => null, 'is_featured' => true, 'is_popular' => true, 'is_published' => true,
                'sort_order' => $sort + $index + 1, 'meta_title' => $title.' | Coast Rider Tour', 'meta_description' => $excerpt,
                'created_at' => now(), 'updated_at' => now(),
            ]);
            $tourId = DB::table('tours')->where('slug', $slug)->value('id');
            $highlights = match ($slug) {
                'spice-prison-stone-town' => ['Guided spice farm visit', 'Giant tortoises on Prison Island', 'Stone Town heritage walk'],
                'prison-island-nakupenda' => ['Prison Island boat trip', 'Giant tortoise sanctuary', 'Nakupenda swimming and sandbank time'],
                'nungwi-turtles-sunset' => ['Turtle sanctuary visit', 'Time on Zanzibar’s north coast', 'Traditional sunset dhow experience'],
                default => ['Tailor-made Tanzania safari', 'Zanzibar beach stay', 'Coordinated transfers and itinerary'],
            };
            foreach ($highlights as $position => $label) DB::table('tour_highlights')->insert(['tour_id'=>$tourId,'label'=>$label,'sort_order'=>$position+1,'created_at'=>now(),'updated_at'=>now()]);
            foreach ([
                ['What information is needed for an accurate quote?', 'Share your travel dates, number of adults and children, hotel or pickup area, and whether you prefer a private or shared experience.'],
                ['Can this package be customised?', 'Yes. The order, timing, pickup arrangements and selected activities can be adjusted around your group and availability.'],
                ['Is payment required when I send a request?', 'No. Sending a request does not require payment. We first confirm availability, the final itinerary, inclusions and price.'],
            ] as $position => [$question, $answer]) DB::table('tour_faqs')->insert(['tour_id'=>$tourId,'question'=>$question,'answer'=>$answer,'sort_order'=>$position+1,'created_at'=>now(),'updated_at'=>now()]);
        }

        foreach (DB::table('tours')->pluck('id') as $tourId) {
            if (DB::table('tour_faqs')->where('tour_id', $tourId)->exists()) continue;
            foreach ([
                ['What information is needed for an accurate quote?', 'Share your travel dates, group size, hotel or pickup area, and whether you prefer a private or shared experience.'],
                ['Can this experience be customised?', 'Yes. Timing, pickup arrangements and selected activities can be adjusted around your group and availability.'],
                ['Is payment required when I send a request?', 'No. We first confirm availability, final inclusions and price before any payment is requested.'],
            ] as $position => [$question, $answer]) {
                DB::table('tour_faqs')->insert(['tour_id'=>$tourId,'question'=>$question,'answer'=>$answer,'sort_order'=>$position + 1,'created_at'=>now(),'updated_at'=>now()]);
            }
        }
    }

    public function down(): void
    {
        DB::table('tours')->whereIn('slug', ['spice-prison-stone-town', 'prison-island-nakupenda', 'nungwi-turtles-sunset', 'safari-zanzibar-beach-escape'])->delete();
        Schema::dropIfExists('tour_faqs');
    }
};
