<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->updateTour('private-sunset-dinner-cruise', [
            'title' => 'Private Zanzibar Sunset Dinner Cruise',
            'excerpt' => 'Enjoy a private evening cruise with sunset views, attentive local service and dinner arranged exclusively for your group.',
            'description' => "Mark a special evening with a private cruise along Zanzibar’s coast. Board your vessel with a local crew, settle in for sunset views and enjoy dinner in a relaxed setting reserved for your group.\n\nThe departure point, sailing route, menu and vessel are confirmed before payment. Sunset visibility and the exact route depend on weather and sea conditions, while dietary requirements should be shared when booking.",
            'duration' => 'Half Day (Evening)',
            'location' => 'Zanzibar Coast',
            'meta_title' => 'Private Zanzibar Sunset Dinner Cruise | Coast Rider',
            'meta_description' => 'Book a private Zanzibar sunset dinner cruise with hotel transport, local boat crew and an evening meal arranged for your group.',
        ], [
            'Private evening cruise reserved for your group',
            'Zanzibar coastal views around sunset',
            'Dinner served during the private experience',
            'Attentive local boat crew and service',
        ], [
            'Private return transport from covered South and South-East Coast hotels',
            'Private cruise and experienced local boat crew',
            'Dinner according to the confirmed menu',
            'Drinking water and confirmed non-alcoholic refreshments',
            'Applicable activity fees, taxes and service charges',
            'Standard boat safety equipment',
        ], [
            'Alcoholic drinks unless listed in writing',
            'Special decorations or premium menu additions unless confirmed',
            'Tips and gratuities',
            'Pickup supplements outside the covered hotel zone',
        ], [
            ['Hotel pickup', 'Meet your driver at the confirmed hotel reception and travel privately to the departure point.'],
            ['Welcome and safety briefing', 'Meet the crew, review the evening plan and receive the required boat-safety guidance.'],
            ['Private sunset cruise', 'Sail along the selected coastal route while enjoying the changing evening light.'],
            ['Dinner service', 'Enjoy the menu confirmed for your group, with dietary requirements arranged in advance.'],
            ['Return to shore', 'Cruise back to the departure point according to the agreed timing and sea conditions.'],
            ['Hotel drop-off', 'Meet your driver for the private evening return transfer.'],
        ], [
            ['How is the private cruise price calculated?', 'The displayed adult rate is per person and changes with the number of adults: one, two, three, or four and above. A separate child rate is available.'],
            ['Is dinner included?', 'Yes. Dinner is included according to the menu confirmed before payment. Share allergies, dietary requirements and celebration requests early.'],
            ['Is the boat exclusive to our group?', 'Yes. This is sold as a private cruise. The vessel type and maximum group size are confirmed with your booking.'],
            ['Are alcoholic drinks included?', 'Only drinks listed in your confirmation are included. Alcoholic drinks and premium additions may be arranged separately where available.'],
            ['Is a clear sunset guaranteed?', 'No. The cruise is scheduled around sunset, but colours and visibility depend on the day’s weather and cloud cover.'],
            ['What happens if the sea is unsafe?', 'The operator may adjust, postpone or cancel the cruise for safety. We will explain the available alternative or rescheduling arrangement.'],
        ]);

        $this->updateTour('design-your-day-private-tour', [
            'title' => 'Design Your Day Private Zanzibar Tour',
            'excerpt' => 'Build a flexible full-day private Zanzibar itinerary around your group’s interests, with one clear group price for up to six guests.',
            'description' => "Create a private day that reflects what your group wants to experience. Choose from suitable cultural, nature, village, coastal or scenic stops, then let our team organise a practical route around your hotel, available time and local conditions.\n\nThe listed USD 239 price covers one private group of up to six guests. Before payment, we confirm the final route, transport, guide and the entrance fees included in your tailored itinerary. Premium boat trips, meals or special activities may require an adjusted quotation.",
            'duration' => 'Full Day',
            'location' => 'Zanzibar',
            'meta_title' => 'Design Your Day Private Zanzibar Tour | Coast Rider',
            'meta_description' => 'Design a flexible full-day private Zanzibar tour for up to six guests with transport, local guide and agreed entrance fees included.',
        ], [
            'Flexible private itinerary for up to six guests',
            'Choose experiences that match your interests',
            'Practical route planned around your hotel',
            'One clear group price with inclusions confirmed',
        ], [
            'Private return transport from covered South and South-East Coast hotels',
            'Professional local tour guide',
            'Entrance fees specifically listed in the confirmed itinerary',
            'Route planning and booking coordination',
            'Government taxes and service charges',
        ], [
            'Lunch, food and drinks unless confirmed in writing',
            'Premium boat trips and special activities unless listed',
            'Tips and gratuities',
            'Personal purchases',
            'Pickup supplements outside the covered hotel zone',
        ], [
            ['Share your interests', 'Tell us your date, hotel, group size and the types of places or activities you would most like to experience.'],
            ['Receive a practical plan', 'We propose a route that fits a full day while considering distance, opening hours and local conditions.'],
            ['Confirm inclusions', 'Review the exact stops, entrance fees, pickup time and any optional supplements before payment.'],
            ['Private hotel pickup', 'Meet your driver and guide at the confirmed hotel reception on the tour date.'],
            ['Enjoy your tailored day', 'Follow the agreed itinerary at a comfortable pace, with practical adjustments made when necessary.'],
            ['Hotel drop-off', 'Return privately to your hotel after the final confirmed stop.'],
        ], [
            ['What does the USD 239 price cover?', 'It covers one private group of up to six guests, including transport, a professional guide and the entrance fees specifically listed in the confirmed itinerary.'],
            ['Can we choose any Zanzibar activity?', 'You can share your preferences, but the final plan must fit the available time, distance, opening hours and budget. Premium boat trips or special activities may cost extra.'],
            ['Is the price per person?', 'No. USD 239 is the total base price for one private group of up to six guests, subject to the final agreed itinerary and covered pickup area.'],
            ['Is lunch included?', 'Lunch is not included unless it is specifically added and written into your confirmation.'],
            ['Can more than six guests join?', 'Yes, but groups above six require a custom quotation so we can arrange the right vehicle, guide and activity capacity.'],
            ['When is the itinerary final?', 'The itinerary and full list of inclusions are confirmed before payment, after we receive your date, hotel, group details and interests.'],
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
