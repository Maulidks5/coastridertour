<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Tour;
use App\Mail\NewWebsiteSubmission;
use App\Support\SendsSubmissionEmail;
use App\Support\TourPricing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    use SendsSubmissionEmail;
    public function create(Request $request, Tour $tour): Response
    {
        abort_unless($tour->is_published, 404);
        $infants = min(20, max(0, $request->integer('infants')));
        $children = min(20 - $infants, max(0, $request->integer('children')));

        return Inertia::render('Bookings/Create', [
            'selection' => [
                'fromCalculator' => $request->hasAny(['type', 'adults', 'children', 'infants']),
                'bookingType' => in_array($request->string('type')->toString(), ['shared', 'private'], true) ? $request->string('type')->toString() : null,
                'adults' => min(30, max(1, $request->integer('adults', 2))),
                'children' => $children,
                'infants' => $infants,
            ],
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
                'Booking style' => $tour->booking_style_label ?: str($booking->booking_type)->title()->toString(),
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
        $booking->load('tour:id,title,slug,booking_style_label');

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
                'bookingStyleLabel' => $booking->tour->booking_style_label,
                'pickupZone' => $booking->pickup_zone,
                'estimatedPrice' => $booking->estimated_price ? (float) $booking->estimated_price : null,
                'estimatedCurrency' => $booking->estimated_currency,
                'quotedPrice' => $booking->quoted_price ? (float) $booking->quoted_price : null,
                'quotedCurrency' => $booking->quoted_currency,
            ],
        ]);
    }

    public function confirmation(Booking $booking): Response
    {
        abort_unless($booking->status === 'confirmed' && $booking->invoice_number, 404);
        $booking->load('tour:id,title,slug,booking_style_label');

        $total = (float) $booking->quoted_price;
        $paid = (float) $booking->amount_paid;

        return Inertia::render('Bookings/Confirmation', [
            'booking' => [
                'reference' => $booking->reference,
                'invoiceNumber' => $booking->invoice_number,
                'fullName' => $booking->full_name,
                'email' => $booking->email,
                'whatsapp' => $booking->whatsapp,
                'tourTitle' => $booking->tour->title,
                'tourSlug' => $booking->tour->slug,
                'travelDate' => $booking->travel_date->format('F j, Y'),
                'bookingType' => $booking->booking_type,
                'bookingStyleLabel' => $booking->tour->booking_style_label,
                'adults' => $booking->adults,
                'children' => $booking->children,
                'childrenAges' => $booking->children_ages ?? [],
                'pickupZone' => $booking->pickup_zone,
                'hotel' => $booking->hotel,
                'specialRequests' => $booking->special_requests,
                'currency' => $booking->quoted_currency,
                'total' => $total,
                'deposit' => $booking->deposit_amount !== null ? (float) $booking->deposit_amount : null,
                'amountPaid' => $paid,
                'balance' => max(0, $total - $paid),
                'paymentStatus' => $booking->payment_status,
                'confirmedAt' => $booking->confirmed_at?->format('F j, Y'),
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
