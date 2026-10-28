<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->updateTour('jozani-forest', [
            'title' => 'Jozani Forest & Red Colobus Tour',
            'excerpt' => 'Explore Jozani’s forest trails and mangrove habitat with a local guide while looking for Zanzibar’s endemic red colobus monkeys.',
            'description' => "Visit Jozani Chwaka Bay National Park for a guided nature walk through one of Zanzibar’s most important protected habitats. Learn about the forest ecosystem, medicinal plants and conservation while observing red colobus monkeys from a respectful distance when they are active nearby.\n\nThe visit may also include the mangrove boardwalk when access and conditions allow. Wildlife is free-ranging, so sightings and the exact route cannot be guaranteed.",
            'duration' => 'Half Day',
            'location' => 'Jozani Chwaka Bay, Zanzibar',
            'meta_title' => 'Jozani Forest & Red Colobus Tour Zanzibar | Coast Rider',
            'meta_description' => 'Book a private guided Jozani Forest tour with hotel transport, park fees and a respectful red colobus wildlife experience.',
        ], [
            'Guided walk through Jozani Forest',
            'Look for endemic Zanzibar red colobus monkeys',
            'Learn about native plants and forest conservation',
            'Visit the mangrove habitat when conditions allow',
        ], [
            'Private return transport from covered South and South-East Coast hotels',
            'Professional local guide',
            'Jozani Chwaka Bay National Park entrance fee',
            'Government taxes and service charges',
        ], [
            'Food and drinks unless confirmed in writing',
            'Tips and gratuities',
            'Personal purchases',
            'Pickup supplements outside the covered hotel zone',
        ], [
            ['Hotel pickup', 'Meet your driver at the confirmed hotel reception and travel privately to Jozani Forest.'],
            ['Forest briefing', 'Meet the guide and receive guidance on the route, wildlife safety and respectful viewing.'],
            ['Guided nature walk', 'Explore suitable forest trails while learning about plants, habitats and conservation.'],
            ['Red colobus viewing', 'Observe free-ranging red colobus monkeys from a respectful distance when they are present.'],
            ['Mangrove habitat', 'Visit the mangrove boardwalk when access, timing and local conditions allow.'],
            ['Hotel return', 'Meet your driver for the arranged return journey after the park visit.'],
        ], [
            ['How long is the Jozani Forest tour?', 'It is a half-day experience. Total time varies with your hotel location, traffic, walking pace and conditions inside the park.'],
            ['Are monkey sightings guaranteed?', 'No. The red colobus monkeys are free-ranging wildlife. They are commonly seen, but nature sightings can never be guaranteed.'],
            ['Is the national park fee included?', 'Yes. The applicable Jozani Chwaka Bay National Park entrance fee is included in the listed private rate.'],
            ['Is the tour suitable for children?', 'Families can join, but children must remain with a responsible adult and follow the guide’s instructions around wildlife and forest paths.'],
            ['What should I bring?', 'Wear comfortable walking shoes and bring insect repellent, sun protection and drinking water. Avoid feeding or touching wildlife.'],
            ['Is the mangrove boardwalk always visited?', 'It is included when access, timing and local conditions allow. The guide may adapt the route for safety or conservation reasons.'],
        ]);

        $this->updateTour('prison-island-nakupenda', [
            'title' => 'Prison Island & Nakupenda Sandbank',
            'excerpt' => 'Combine Prison Island’s giant tortoises with swimming and relaxation on Nakupenda Sandbank during a private full-day boat experience.',
            'description' => "Depart from the Stone Town coast by boat for a combined island and sandbank experience. Visit Prison Island with a local guide, learn about its history and see the giant tortoises before continuing towards Nakupenda Sandbank.\n\nAt the sandbank, enjoy time to swim, relax and take in the turquoise coastal scenery. Timing and sandbank access depend on tide, sea and weather conditions, and the final meal and transfer arrangements are confirmed in your personal quotation.",
            'duration' => 'Full Day',
            'location' => 'Prison Island & Nakupenda Sandbank',
            'price' => 0,
            'shared_available' => false,
            'private_available' => true,
            'private_pricing_model' => 'quote_only',
            'pricing_note' => 'Private all-inclusive quotation based on pickup area, group size, boat arrangement and meal requirements. We confirm the final total and inclusions before payment.',
            'meta_title' => 'Prison Island & Nakupenda Sandbank Tour | Coast Rider',
            'meta_description' => 'Request a private Prison Island and Nakupenda Sandbank tour with boat, guide, entrance fees and hotel transfers arranged for your group.',
        ], [
            'Private boat journey from the Stone Town coast',
            'Guided visit to Prison Island',
            'See the island’s giant tortoises',
            'Swim and relax at Nakupenda Sandbank',
        ], [
            'Private return transport when listed in the confirmed quote',
            'Boat transport and experienced local crew',
            'Professional local guide',
            'Applicable Prison Island entrance fees',
            'Sandbank meal and refreshments when listed in the confirmed quote',
        ], [
            'Tips and gratuities',
            'Personal purchases and alcoholic drinks',
            'Services not listed in the confirmed quotation',
        ], [
            ['Hotel pickup', 'Travel from your confirmed hotel or pickup point to the Stone Town boat departure area.'],
            ['Prison Island boat trip', 'Cross to Prison Island with an experienced local boat crew.'],
            ['Guided island visit', 'Learn about the island’s history and visit the giant tortoise area with your guide.'],
            ['Nakupenda Sandbank', 'Continue to the sandbank for swimming, relaxation and coastal views according to tide and sea conditions.'],
            ['Meal and refreshments', 'Enjoy the meal and refreshments described in your confirmed quotation.'],
            ['Return transfer', 'Travel by boat back to Stone Town and meet your driver when hotel transport is included.'],
        ], [
            ['Why is this tour priced by quotation?', 'The final price depends on your pickup area, group size, boat arrangement and meal requirements. You receive the complete total before making payment.'],
            ['Are entrance fees included?', 'Applicable Prison Island entrance fees are included in the confirmed all-inclusive quotation.'],
            ['Is food included?', 'A sandbank meal can be included. Its menu and any dietary requirements will be written clearly in your final quotation.'],
            ['Can we visit Nakupenda at any time?', 'The itinerary depends on tide, weather and sea conditions. The crew selects safe and practical timing for the sandbank visit.'],
            ['Is snorkeling equipment included?', 'Availability varies by boat arrangement. Ask for snorkeling when requesting the tour so it can be included and confirmed in writing.'],
            ['What should I bring?', 'Bring swimwear, a towel, sun protection, suitable water footwear and a waterproof bag for personal items.'],
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
