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
    return { label: "Request a quote", caption: "Custom pricing" };
}
