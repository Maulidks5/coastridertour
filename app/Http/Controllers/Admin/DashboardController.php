<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Tour;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'publishedTours' => Tour::query()->published()->count(),
                'pendingBookings' => Booking::query()->where('status', 'pending')->count(),
                'upcomingBookings' => Booking::query()->whereDate('travel_date', '>=', today())->count(),
                'totalBookings' => Booking::query()->count(),
            ],
            'recentBookings' => Booking::query()
                ->with('tour:id,title')
                ->latest()
                ->limit(6)
                ->get()
                ->map(fn (Booking $booking): array => [
                    'reference' => $booking->reference,
                    'name' => $booking->full_name,
                    'tour' => $booking->tour->title,
                    'travelDate' => $booking->travel_date->format('M j, Y'),
                    'status' => $booking->status,
                ]),
        ]);
    }
}
