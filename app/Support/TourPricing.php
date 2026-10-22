<?php

namespace App\Support;

use App\Models\Tour;

final class TourPricing
{
    public static function payload(Tour $tour): array
    {
        return [
            'sharedAvailable' => (bool) $tour->shared_available,
            'sharedAdultPrice' => self::number($tour->shared_adult_price ?? ($tour->price > 0 ? $tour->price : null)),
            'sharedChildPrice' => self::number($tour->shared_child_price),
            'sharedInfantPrice' => self::number($tour->shared_infant_price),
            'privateAvailable' => (bool) $tour->private_available,
            'privatePricingModel' => $tour->private_pricing_model ?: 'group_total',
            'privateTiers' => [
                ['min' => 1, 'max' => 2, 'price' => self::number($tour->private_1_2_price)],
                ['min' => 3, 'max' => 4, 'price' => self::number($tour->private_3_4_price)],
                ['min' => 5, 'max' => 6, 'price' => self::number($tour->private_5_6_price)],
                ['min' => 7, 'max' => 10, 'price' => self::number($tour->private_7_10_price)],
            ],
            'privatePerPersonTiers' => [
                ['min' => 1, 'max' => 1, 'price' => self::number($tour->private_1_person_price)],
                ['min' => 2, 'max' => 2, 'price' => self::number($tour->private_2_person_price)],
                ['min' => 3, 'max' => 3, 'price' => self::number($tour->private_3_person_price)],
                ['min' => 4, 'max' => (int) ($tour->private_max_online_guests ?: 10), 'price' => self::number($tour->private_4_plus_person_price)],
            ],
            'privateChildPrice' => self::number($tour->private_child_price),
            'privateInfantPrice' => self::number($tour->private_infant_price),
            'privateChildSupplement' => self::number($tour->private_child_supplement),
            'privateMaxOnlineGuests' => (int) ($tour->private_max_online_guests ?: 10),
            'largeGroupQuote' => (bool) $tour->large_group_quote,
            'pricingNote' => $tour->pricing_note,
        ];
    }

    public static function estimate(Tour $tour, string $type, int $adults, array $ages): ?float
    {
        $infants = count(array_filter($ages, fn ($age): bool => (int) $age <= 2));
        $children = count($ages) - $infants;

        if ($type === 'shared') {
            $adult = $tour->shared_adult_price ?? ($tour->price > 0 ? $tour->price : null);
            if ($adult === null || ($children && $tour->shared_child_price === null) || ($infants && $tour->shared_infant_price === null)) return null;
            return (float) $adult * $adults + (float) ($tour->shared_child_price ?? 0) * $children + (float) ($tour->shared_infant_price ?? 0) * $infants;
        }

        $model = $tour->private_pricing_model ?: 'group_total';
        if ($model === 'quote_only') return null;
        if ($model === 'per_person_tiered') {
            if ($adults > (int) ($tour->private_max_online_guests ?: 10)) return null;
            $adultRate = match (true) {
                $adults === 1 => $tour->private_1_person_price,
                $adults === 2 => $tour->private_2_person_price,
                $adults === 3 => $tour->private_3_person_price,
                $adults >= 4 => $tour->private_4_plus_person_price,
                default => null,
            };
            if ($adultRate === null || ($children && $tour->private_child_price === null) || ($infants && $tour->private_infant_price === null)) return null;
            return (float) $adultRate * $adults + (float) ($tour->private_child_price ?? 0) * $children + (float) ($tour->private_infant_price ?? 0) * $infants;
        }

        $guests = $adults + count($ages);
        $base = match (true) {
            $guests <= 2 => $tour->private_1_2_price,
            $guests <= 4 => $tour->private_3_4_price,
            $guests <= 6 => $tour->private_5_6_price,
            $guests <= 10 => $tour->private_7_10_price,
            default => null,
        };
        return $base === null ? null : (float) $base + (float) ($tour->private_child_supplement ?? 0) * count($ages);
    }

    private static function number(mixed $value): ?float
    {
        return $value === null ? null : (float) $value;
    }
}
