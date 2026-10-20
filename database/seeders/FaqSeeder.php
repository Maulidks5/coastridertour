<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['Booking', 'How do I book a tour?', 'Choose an experience and send a booking request with your preferred date and group details. Our team will confirm availability, pickup and the final arrangement with you.', 1],
            ['Booking', 'Is my booking confirmed immediately?', 'No. The website sends a booking request first. Your booking is confirmed after our team checks availability and sends you the final details.', 2],
            ['Payments', 'Do I need to pay online?', 'No online payment is required when sending a request. Our team will explain the available payment options when confirming your booking.', 3],
            ['Pickup', 'Do you provide hotel pickup?', 'Pickup depends on the tour and your hotel location. Add your hotel or area to the booking request and we will confirm the pickup point and time.', 4],
            ['Travel', 'Are tours suitable for children?', 'Many experiences are family-friendly, but suitability can depend on age, sea conditions and the activity. Tell us the children’s ages so we can recommend the right option.', 5],
            ['Changes & cancellation', 'Can I change my travel date?', 'Contact us as early as possible. Date changes depend on availability, but our team will always try to help you find a suitable alternative.', 6],
            ['Weather', 'What happens if the weather is not suitable?', 'Safety comes first. If conditions affect an ocean or outdoor experience, our team will discuss rescheduling or another suitable arrangement with you.', 7],
            ['Safaris', 'Can you customise a Tanzania safari?', 'Yes. Share your dates, number of travellers, preferred parks and travel style. We can help shape a safari itinerary around your plans.', 8],
        ];

        foreach ($items as [$category, $question, $answer, $order]) {
            Faq::query()->updateOrCreate(
                ['question' => $question],
                ['category' => $category, 'answer' => $answer, 'sort_order' => $order, 'is_active' => true],
            );
        }
    }
}
