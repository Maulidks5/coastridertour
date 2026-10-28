<x-mail::message>
# Your booking is confirmed

Hello {{ $booking->full_name }},

Your booking for **{{ $booking->tour->title }}** has been confirmed by Coast Rider Tour.

**Booking reference:** {{ $booking->reference }}  
**Confirmation number:** {{ $booking->invoice_number }}  
**Travel date:** {{ $booking->travel_date->format('F j, Y') }}  
**Final total:** {{ $booking->quoted_currency }} {{ number_format((float) $booking->quoted_price, 2) }}

<x-mail::button :url="$confirmationUrl">
View booking confirmation
</x-mail::button>

The confirmation page includes your trip details, payment status and printable proforma invoice.

Thank you,<br>
{{ config('app.name', 'Coast Rider Tour Company Limited') }}
</x-mail::message>
