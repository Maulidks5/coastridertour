<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->updateTour('stone-town-tour', [
            'title' => 'Historic Stone Town Walking Tour',
            'excerpt' => 'Explore Stone Town with a local guide through historic streets, lively markets and Zanzibar’s distinctive cultural landmarks.',
            'description' => "Discover the stories, architecture and everyday life of Stone Town on a privately guided walking tour. Follow its narrow streets past carved doors, historic buildings, market scenes and places connected to Zanzibar’s trading and cultural heritage.\n\nYour guide adapts the pace to your group and explains the context behind the places visited. The exact walking route may change with opening hours, local conditions and your confirmed interests.",
            'duration' => 'Half Day',
            'location' => 'Stone Town, Zanzibar',
            'meta_title' => 'Historic Stone Town Walking Tour Zanzibar | Coast Rider Tour',
            'meta_description' => 'Book a private guided Stone Town walking tour with hotel transport, a professional local guide and applicable entrance fees included.',
        ], [
            'Walk through Stone Town’s historic narrow streets',
            'See distinctive Zanzibar doors and architecture',
            'Discover local markets and daily life',
            'Learn about Zanzibar’s multicultural heritage',
        ], [
            'Private return transport from covered South and South-East Coast hotels',
            'Professional local Stone Town guide',
            'Applicable entrance fees on the confirmed route',
            'Government taxes and service charges',
        ], [
            'Food and drinks unless confirmed in writing',
            'Tips and gratuities',
            'Personal purchases',
            'Pickup supplements outside the covered hotel zone',
        ], [
            ['Hotel pickup', 'Meet your driver at the confirmed hotel reception and travel privately to Stone Town.'],
            ['Guided heritage walk', 'Explore historic streets, architecture and selected landmarks while your guide explains their cultural context.'],
            ['Market and local life', 'Experience the atmosphere of Stone Town’s commercial streets and market area where practical.'],
            ['Free time', 'Pause for photographs or agreed personal shopping before meeting your driver.'],
            ['Hotel return', 'Travel back to your hotel after the guided experience.'],
        ], [
            ['How long is the Stone Town tour?', 'It is a half-day experience. Total time varies with your hotel location, traffic, walking pace and the confirmed route.'],
            ['Is this a private tour?', 'Yes. The listed rates are for a privately arranged tour with transport and a professional local guide.'],
            ['Are entrance fees included?', 'Applicable entrance fees on the confirmed itinerary are included. Optional museums or activities not agreed in advance may cost extra.'],
            ['How much walking is involved?', 'The old town is mainly explored on foot over narrow and sometimes uneven streets. Tell us about mobility needs before booking so we can adapt the route where possible.'],
            ['What should I wear?', 'Wear comfortable walking shoes and light, respectful clothing suitable for Zanzibar’s cultural setting. Sun protection and drinking water are also useful.'],
            ['Where is pickup included?', 'Return transport is included for the covered South and South-East Coast hotels. Share another pickup location so we can confirm any supplement before payment.'],
        ]);

        $this->updateTour('spice-tour', [
            'title' => 'Zanzibar Spice Farm Tour',
            'excerpt' => 'Walk through a working spice farm with a local guide and discover the plants, aromas and tropical flavours behind Zanzibar’s Spice Island story.',
            'description' => "Visit a Zanzibar spice farm and learn how familiar spices, fruits and useful tropical plants grow. A local guide introduces seasonal plants through their appearance, scent and traditional uses while sharing the island’s connection to the spice trade.\n\nWhat is available to see or taste changes naturally with the season. The experience is relaxed and interactive, with time for questions and an opportunity to buy local products without obligation.",
            'duration' => 'Half Day',
            'location' => 'Zanzibar Spice Farms',
            'meta_title' => 'Zanzibar Spice Farm Tour | Coast Rider Tour',
            'meta_description' => 'Book a private Zanzibar spice farm tour with hotel transport, local guide, seasonal spice discovery and applicable entrance fees included.',
        ], [
            'Guided walk through a Zanzibar spice farm',
            'See, smell and learn about seasonal spices',
            'Discover tropical fruits and useful local plants',
            'Hear the story of Zanzibar’s spice heritage',
        ], [
            'Private return transport from covered South and South-East Coast hotels',
            'Professional local guide and spice-farm host',
            'Applicable spice-farm entrance fees',
            'Seasonal spice or fruit tasting when available',
            'Government taxes and service charges',
        ], [
            'Lunch and additional drinks unless confirmed in writing',
            'Spices, soaps and other personal purchases',
            'Tips and gratuities',
            'Pickup supplements outside the covered hotel zone',
        ], [
            ['Hotel pickup', 'Meet your driver at the confirmed hotel reception and travel privately to the selected spice farm.'],
            ['Farm introduction', 'Meet the local host and receive an introduction to the farm and Zanzibar’s spice history.'],
            ['Guided spice walk', 'See and smell seasonal spices, fruits and tropical plants while learning how they grow and are traditionally used.'],
            ['Tasting and questions', 'Taste available seasonal produce and ask the guide about the plants and local products.'],
            ['Hotel return', 'After the visit, meet your driver for the return journey to your hotel.'],
        ], [
            ['How long is the Spice Farm tour?', 'It is a half-day experience. Total time depends on your pickup location, traffic and the pace of the farm visit.'],
            ['Is this a private tour?', 'Yes. The listed rates are for a privately arranged experience with transport, a guide and applicable entrance fees.'],
            ['Are tastings included?', 'Seasonal spice or fruit tasting is included when available. The exact selection depends on what is growing at the time of your visit.'],
            ['Is lunch included?', 'No. Lunch is not included in the standard half-day rate unless it is specifically added to your confirmed itinerary.'],
            ['Do I have to buy spices?', 'No. You may have an opportunity to buy local products, but purchasing is entirely optional.'],
            ['What should I bring?', 'Wear comfortable closed or sturdy shoes and bring sun protection, insect repellent and drinking water.'],
        ]);
    }

    private function updateTour(string $slug, array $details, array $highlights, array $included, array $excluded, array $itinerary, array $faqs): void
    {
        $tour = DB::table('tours')->where('slug', $slug)->first();
        if (! $tour) {
            return;
        }

        DB::table('tours')->where('id', $tour->id)->update([...$details, 'updated_at' => now()]);

        DB::table('tour_highlights')->where('tour_id', $tour->id)->delete();
        foreach ($highlights as $index => $label) {
            DB::table('tour_highlights')->insert(['tour_id' => $tour->id, 'label' => $label, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }

        DB::table('tour_inclusions')->where('tour_id', $tour->id)->delete();
        foreach ($included as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => true, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
        foreach ($excluded as $index => $label) {
            DB::table('tour_inclusions')->insert(['tour_id' => $tour->id, 'label' => $label, 'is_included' => false, 'sort_order' => count($included) + $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }

        DB::table('tour_itinerary_items')->where('tour_id', $tour->id)->delete();
        foreach ($itinerary as $index => [$title, $description]) {
            DB::table('tour_itinerary_items')->insert(['tour_id' => $tour->id, 'time_label' => null, 'title' => $title, 'description' => $description, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }

        DB::table('tour_faqs')->where('tour_id', $tour->id)->delete();
        foreach ($faqs as $index => [$question, $answer]) {
            DB::table('tour_faqs')->insert(['tour_id' => $tour->id, 'question' => $question, 'answer' => $answer, 'sort_order' => $index + 1, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    public function down(): void
    {
        // Content is editorial data and may be changed later from the admin area.
    }
};
