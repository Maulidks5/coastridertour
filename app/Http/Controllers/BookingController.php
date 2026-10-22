<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Tour;
use App\Mail\NewWebsiteSubmission;
use App\Support\SendsSubmissionEmail;
use App\Support\TourPricing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    use SendsSubmissionEmail;
    public function create(Tour $tour): Response
    {
        abort_unless($tour->is_published, 404);

        return Inertia::render('Bookings/Create', [
            'tour' => [
                'id' => $tour->id,
                'slug' => $tour->slug,
                'title' => $tour->title,
                'description' => $tour->excerpt,
                'duration' => $tour->duration,
                'location' => $tour->location,
                'price' => (float) $tour->price,
                'currency' => $tour->currency,
                'pricing' => TourPricing::payload($tour),
                'image' => $tour->cover_image_path ?: '/images/coast-rider-placeholder.jpg',
            ],
        ]);
    }

    public function store(StoreBookingRequest $request): RedirectResponse
    {
        $data = collect($request->validated())->except(['consent', 'website'])->all();
        $tour = Tour::query()->published()->findOrFail($data['tour_id']);

        $available = $data['booking_type'] === 'shared' ? $tour->shared_available : $tour->private_available;
        if (! $available) {
            throw ValidationException::withMessages(['booking_type' => 'This tour style is not currently available.']);
        }

        $estimate = TourPricing::estimate($tour, $data['booking_type'], (int) $data['adults'], $data['children_ages'] ?? []);
        $booking = $tour->bookings()->create([
            ...$data,
            'estimated_price' => $estimate,
            'estimated_currency' => $estimate !== null ? $tour->currency : null,
            'quoted_price' => null,
            'quoted_currency' => null,
            'reference' => $this->newReference(),
            'status' => 'pending',
            'source' => 'website',
        ]);

        $this->sendSubmissionEmail(new NewWebsiteSubmission(
            heading: 'New Tour Booking',
            reference: $booking->reference,
            details: [
                'Tour' => $tour->title,
                'Booking style' => str($booking->booking_type)->title()->toString(),
                'Customer' => $booking->full_name,
                'Email' => $booking->email,
                'WhatsApp' => $booking->whatsapp,
                'Travel date' => $booking->travel_date->format('F j, Y'),
                'Guests' => "{$booking->adults} adult(s), {$booking->children} child(ren)",
                'Children ages' => $booking->children_ages ?: 'None',
                'Pickup zone' => $booking->pickup_zone,
                'Hotel' => $booking->hotel,
                'Estimated price' => $booking->estimated_price ? "{$booking->estimated_currency} {$booking->estimated_price}" : 'Custom quote required',
                'Special requests' => $booking->special_requests,
            ],
            replyToAddress: $booking->email,
            replyToName: $booking->full_name,
        ));

        return redirect()->to(URL::temporarySignedRoute('bookings.success', now()->addDays(7), ['booking' => $booking]));
    }

    public function success(Booking $booking): Response
    {
        $booking->load('tour:id,title,slug');

        return Inertia::render('Bookings/Success', [
            'booking' => [
                'reference' => $booking->reference,
                'tourTitle' => $booking->tour->title,
                'tourSlug' => $booking->tour->slug,
                'travelDate' => $booking->travel_date->format('F j, Y'),
                'adults' => $booking->adults,
                'children' => $booking->children,
                'childrenAges' => $booking->children_ages ?? [],
                'bookingType' => $booking->booking_type,
                'pickupZone' => $booking->pickup_zone,
                'estimatedPrice' => $booking->estimated_price ? (float) $booking->estimated_price : null,
                'estimatedCurrency' => $booking->estimated_currency,
                'quotedPrice' => $booking->quoted_price ? (float) $booking->quoted_price : null,
                'quotedCurrency' => $booking->quoted_currency,
            ],
        ]);
    }


    private function newReference(): string
    {
        do {
            $reference = 'CRT-'.now()->format('ymd').'-'.Str::upper(Str::random(6));
        } while (Booking::query()->where('reference', $reference)->exists());

        return $reference;
    }
}
