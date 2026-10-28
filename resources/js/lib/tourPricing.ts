import type { TourPricing } from "@/types/tour";

export function tourPriceSummary(price: number, currency: string, pricing?: TourPricing) {
    const money = (value: number) => new Intl.NumberFormat("en-US", { style: "currency", currency, maximumFractionDigits: 0 }).format(value);
    if (!pricing) return price > 0 ? { label: money(price), caption: "Starting price" } : { label: "Request a quote", caption: "Custom pricing" };
    if (pricing.sharedAvailable && pricing.sharedAdultPrice !== null) return { label: money(pricing.sharedAdultPrice), caption: "From per adult" };
    if (pricing.privateAvailable && pricing.privatePricingModel === "per_person_tiered") {
        const tiers = pricing.privatePerPersonTiers.filter((tier) => tier.price !== null);
        const lowest = tiers.reduce<typeof tiers[number] | undefined>((best, tier) => !best || (tier.price ?? Infinity) < (best.price ?? Infinity) ? tier : best, undefined);
        if (lowest?.price !== null && lowest) return { label: money(lowest.price), caption: lowest.min >= 4 ? `Per person · ${lowest.min}+ adults` : "From per person" };
    }
    if (pricing.privateAvailable && pricing.privatePricingModel === "group_total") {
        const first = pricing.privateTiers.find((tier) => tier.price !== null);
        if (first?.price !== null && first) return { label: money(first.price), caption: "From per private group" };
    }
    return price > 0
        ? { label: money(price), caption: "From per person" }
        : { label: "Request a quote", caption: "Custom pricing" };
}

export function preferredBookingType(pricing: TourPricing): "shared" | "private" {
    return pricing.privateAvailable ? "private" : "shared";
}

export function calculateTourTotal(
    pricing: TourPricing,
    adults: number,
    children: number,
    infants: number,
    bookingType: "shared" | "private" = preferredBookingType(pricing),
): number | null {
    if (bookingType === "private" && pricing.privateAvailable) {
        if (pricing.privatePricingModel === "quote_only") return null;

        if (pricing.privatePricingModel === "per_person_tiered") {
            const totalGuests = adults + children + infants;
            if (totalGuests > pricing.privateMaxOnlineGuests) return null;
            const tier = pricing.privatePerPersonTiers.find(
                (item) => adults >= item.min && adults <= item.max,
            );
            if (!tier || tier.price === null || (children > 0 && pricing.privateChildPrice === null) || (infants > 0 && pricing.privateInfantPrice === null)) return null;
            return tier.price * adults + (pricing.privateChildPrice ?? 0) * children + (pricing.privateInfantPrice ?? 0) * infants;
        }

        const guests = adults + children + infants;
        const tier = pricing.privateTiers.find((item) => guests >= item.min && guests <= item.max);
        if (!tier || tier.price === null) return null;
        return tier.price + (pricing.privateChildSupplement ?? 0) * (children + infants);
    }

    if (bookingType !== "shared" || !pricing.sharedAvailable || pricing.sharedAdultPrice === null || (children > 0 && pricing.sharedChildPrice === null) || (infants > 0 && pricing.sharedInfantPrice === null)) return null;
    return pricing.sharedAdultPrice * adults + (pricing.sharedChildPrice ?? 0) * children + (pricing.sharedInfantPrice ?? 0) * infants;
}
