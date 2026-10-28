<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->updateTour('safari-blue', [
            'title' => 'Safari Blue Dhow Adventure',
            'excerpt' => 'Sail through Menai Bay by traditional dhow, snorkel in clear water, relax on a sandbank and enjoy a seafood lunch.',
            'description' => "Spend a full day exploring the protected waters of Menai Bay from Fumba. The experience combines traditional dhow sailing, guided snorkeling, a sandbank stop and time to swim or relax.\n\nA seafood lunch and tropical fruit are part of the day. Your exact pickup time depends on your hotel, and the final route follows sea and weather conditions for a comfortable experience.",
            'duration' => 'Full Day with Lunch',
            'location' => 'Fumba & Menai Bay',
            'meta_title' => 'Safari Blue Zanzibar Dhow Tour | Coast Rider Tour',
            'meta_description' => 'Book a full-day Safari Blue dhow adventure in Zanzibar with snorkeling, sandbank time, seafood lunch and private hotel transport.',
        ], [
            'Traditional dhow sailing in Menai Bay',
            'Guided snorkeling in clear coastal water',
            'Sandbank swimming and relaxation',
            'Seafood lunch and tropical fruit',
        ], [
            'Private return transport from covered South and South-East Coast hotels',
            'Safari Blue boat and dhow experience',
            'Professional local guide and boat crew',
            'Snorkeling equipment',
            'Seafood lunch, tropical fruit and drinking water',
            'Applicable conservation and activity fees',
        ], [
            'Tips and gratuities',
            'Personal purchases and alcoholic drinks',
            'Pickup supplements outside the covered hotel zone',
        ], [
            ['Hotel pickup', 'Meet your driver at the confirmed hotel reception and travel to Fumba.'],
            ['Fumba departure', 'Meet the boat crew, receive a short briefing and begin the Menai Bay journey.'],
            ['Snorkeling and sandbank', 'Stop according to tide and sea conditions for guided snorkeling, swimming and sandbank time.'],
            ['Island lunch', 'Enjoy a seafood lunch with tropical fruit and time to relax.'],
            ['Return journey', 'Sail back to Fumba and meet your driver for the confirmed hotel drop-off.'],
        ], [
            ['How long is Safari Blue?', 'Safari Blue is a full-day experience. The total time depends on your hotel location, tide, sea conditions and the day’s route.'],
            ['Is lunch included?', 'Yes. The tour includes a seafood lunch and tropical fruit. Tell us in advance about allergies or dietary requirements.'],
            ['Is snorkeling equipment provided?', 'Yes. Standard snorkeling equipment is included. Please tell us before travel if you need a specific size or have any swimming concerns.'],
            ['Can children join?', 'Children can join with a responsible adult. Share their ages when booking so we can confirm the appropriate rate and arrangements.'],
            ['What should I bring?', 'Bring swimwear, a towel, sun protection, suitable footwear and a waterproof bag for personal items.'],
            ['Is the itinerary guaranteed?', 'The core experience remains the same, but stops and timing may change with weather, tide and safety conditions.'],
        ]);

        $this->updateTour('mnemba-island', [
            'title' => 'Mnemba Atoll Snorkeling Experience',
            'excerpt' => 'Take a guided boat trip to the waters around Mnemba Atoll for snorkeling, reef scenery and a relaxed ocean experience.',
            'description' => "Travel to Zanzibar’s north-east coast and join a guided boat journey to the snorkeling areas around Mnemba Atoll. Swim above coral reefs, look for tropical marine life and enjoy time on the water with a local crew.\n\nMnemba Island itself is private and landing is not included. Wild dolphin sightings can happen on the journey, but they are never guaranteed and should not involve chasing or disturbing the animals.",
            'duration' => 'Half Day',
            'location' => 'Matemwe & Mnemba Atoll',
            'price' => 0,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'quote_only',
            'pricing_note' => 'Price depends on pickup area, group size and boat arrangement. We confirm the final total and inclusions before payment.',
            'meta_title' => 'Mnemba Atoll Snorkeling Tour Zanzibar | Coast Rider Tour',
            'meta_description' => 'Plan a guided Mnemba Atoll snorkeling trip from Zanzibar with boat crew, reef snorkeling and pickup arranged around your hotel.',
        ], [
            'Guided snorkeling around Mnemba Atoll',
            'Coral reef and tropical marine life',
            'Scenic boat journey from the north-east coast',
            'Private arrangements available',
        ], [
            'Boat trip with an experienced local crew',
            'Snorkeling equipment',
            'Drinking water and seasonal fruit',
            'Hotel transport when stated in your confirmed quote',
        ], [
            'Landing on private Mnemba Island',
            'Guaranteed dolphin sightings or interaction',
            'Tips, personal purchases and services not listed in your quote',
        ], [
            ['Hotel pickup', 'Travel from your confirmed pickup point to the north-east coast departure area.'],
            ['Boat briefing', 'Meet the crew, check equipment and receive water-safety guidance.'],
            ['Mnemba snorkeling', 'Visit suitable reef areas selected according to visibility, tide and sea conditions.'],
            ['Refreshment stop', 'Enjoy seasonal fruit and time to relax before returning to shore.'],
            ['Hotel return', 'Meet your driver for the arranged return transfer when transport is included.'],
        ], [
            ['Can we land on Mnemba Island?', 'No. Mnemba Island is private. The experience takes place in the surrounding snorkeling waters.'],
            ['Are dolphins guaranteed?', 'No. Dolphins are wild animals and sightings cannot be guaranteed. The crew should keep a respectful distance and avoid chasing them.'],
            ['Do I need to be an experienced swimmer?', 'Basic confidence in the water is helpful. Tell us about non-swimmers or children so we can confirm suitable support before booking.'],
            ['What affects the final price?', 'The final quote depends on your hotel location, group size, transfer needs and whether the boat is shared or private.'],
            ['What should I bring?', 'Bring swimwear, a towel, sun protection and a waterproof bag for phones and valuables.'],
        ]);
    }

    private function updateTour(string $slug, array $details, array $highlights, array $included, array $excluded, array $itinerary, array $faqs): void
    {
        $tour = DB::table('tours')->where('slug', $slug)->first();
        if (! $tour) return;

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
