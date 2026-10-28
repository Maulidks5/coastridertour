import { Link } from "./AppLink";
import { ArrowRight, Clock, MapPin, Star } from "lucide-react";
import type { TourCardData } from "@/types/tour";
import { tourPriceSummary } from "@/lib/tourPricing";

export function TourCard({ tour, compact = false }: { tour: TourCardData; compact?: boolean }) {
    const price = tourPriceSummary(tour.price, tour.currency, tour.pricing);
    const hasPricingPreview = Boolean(tour.pricing);

    return (
        <Link
            to={`/tours/${tour.slug}`}
            aria-label={`View ${tour.title}`}
            className="group flex h-full min-w-0 flex-col overflow-hidden rounded-2xl border border-border/70 bg-card shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[var(--gold)]/50 hover:shadow-[var(--shadow-card)]"
        >
            <div className="relative aspect-[4/3] overflow-hidden bg-muted">
                <img
                    src={tour.image}
                    alt={tour.title}
                    loading="lazy"
                    decoding="async"
                    className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                />
                {!compact && tour.category && (
                    <span className="absolute left-3 top-3 max-w-[75%] truncate rounded-full bg-[#071d2b]/75 px-3 py-1.5 text-[9px] font-bold uppercase tracking-wide text-white backdrop-blur-sm">
                        {tour.category}
                    </span>
                )}
            </div>

            <div className="flex flex-1 flex-col p-4">
                <h3 className="line-clamp-2 min-h-12 font-display text-lg font-bold leading-6 text-foreground transition group-hover:text-[var(--ocean)]">
                    {tour.title}
                </h3>

                {tour.reviewsCount > 0 && (
                    <p className="mt-1.5 flex items-center gap-1 text-xs font-semibold text-muted-foreground">
                        <Star className="h-3.5 w-3.5 fill-[var(--gold)] text-[var(--gold)]" />
                        {tour.rating} <span className="font-normal">· {tour.reviewsCount} reviews</span>
                    </p>
                )}

                {!compact && (
                    <p className="mt-2 line-clamp-2 min-h-10 text-xs leading-5 text-muted-foreground">
                        {tour.description}
                    </p>
                )}

                <div className="mt-3 flex min-w-0 flex-wrap items-center gap-x-3 gap-y-1.5 text-[11px] font-medium text-muted-foreground">
                    <span className="inline-flex items-center gap-1">
                        <Clock className="h-3.5 w-3.5 text-[var(--turquoise)]" /> {tour.duration}
                    </span>
                    <span className="inline-flex min-w-0 items-center gap-1 truncate">
                        <MapPin className="h-3.5 w-3.5 shrink-0 text-[var(--turquoise)]" />
                        <span className="truncate">{tour.location}</span>
                    </span>
                </div>

                {tour.pricing && <CardPricingPreview currency={tour.currency} pricing={tour.pricing} headlinePrice={tour.price} compact={compact} />}

                {compact ? (
                    <div className="mt-auto pt-4">
                        <span className="flex min-h-10 w-full items-center justify-center gap-2 rounded-xl border border-[var(--ocean)]/20 text-sm font-bold text-[var(--ocean)] transition group-hover:bg-[var(--ocean)] group-hover:text-white">
                            View Tour <ArrowRight className="h-4 w-4 transition group-hover:translate-x-1" />
                        </span>
                    </div>
                ) : (
                <div className="mt-auto flex items-end justify-between gap-3 pt-5">
                    <div className="min-w-0">
                        {hasPricingPreview
                            ? <><p className="text-[10px] font-bold uppercase tracking-wider text-muted-foreground">Tour details</p><p className="mt-0.5 text-sm font-bold text-[var(--ocean)]">See itinerary & inclusions</p></>
                            : <><p className="truncate text-[10px] text-muted-foreground">{price.caption}</p><p className="mt-0.5 truncate font-display text-xl font-extrabold text-[var(--gold)]">{price.label}</p></>}
                    </div>
                    <span className="grid h-9 w-9 shrink-0 place-items-center rounded-full border border-border text-[var(--ocean)] transition group-hover:border-[var(--ocean)] group-hover:bg-[var(--ocean)] group-hover:text-white">
                        <ArrowRight className="h-4 w-4" />
                    </span>
                </div>
                )}
            </div>
        </Link>
    );
}

function CardPricingPreview({ currency, pricing, headlinePrice, compact = false }: { currency: string; pricing: NonNullable<TourCardData["pricing"]>; headlinePrice: number; compact?: boolean }) {
    const money = (amount: number) => new Intl.NumberFormat("en-US", {
        style: "currency",
        currency,
        maximumFractionDigits: 0,
    }).format(amount);

    if (pricing.privateAvailable && pricing.privatePricingModel === "per_person_tiered") {
        const rates = pricing.privatePerPersonTiers.filter((tier) => tier.price !== null);
        if (!rates.length) return <QuotePreview currency={currency} startingPrice={headlinePrice} />;

        return (
            <div className="mt-4 border-t border-border/80 pt-3" aria-label="Private price per adult by group size">
                <div className="flex items-center justify-between gap-2">
                    <p className="text-[9px] font-bold uppercase tracking-[0.14em] text-muted-foreground">Private · per adult</p>
                    <span className="text-[9px] text-muted-foreground sm:hidden">Swipe rates →</span>
                </div>
                <div className="no-scrollbar -mx-1 mt-2 flex snap-x gap-1.5 overflow-x-auto px-1 pb-1">
                    {rates.map((tier) => (
                        <div key={tier.min} className={`${compact ? "min-w-[3.7rem] px-1" : "min-w-[4.4rem] px-2"} flex-1 snap-start rounded-xl bg-[var(--sand)] py-2 text-center`}>
                            <p className="text-[9px] font-semibold text-muted-foreground">{tier.min >= 4 ? `${tier.min}+ adults` : `${tier.min} ${tier.min === 1 ? "adult" : "adults"}`}</p>
                            <p className="mt-0.5 font-display text-sm font-extrabold text-[var(--gold)]">{money(tier.price!)}</p>
                        </div>
                    ))}
                </div>
                {(pricing.privateChildPrice !== null || pricing.privateInfantPrice !== null) && (
                    <p className="mt-1.5 truncate text-[10px] text-muted-foreground">
                        {pricing.privateChildPrice !== null && `Child ${money(pricing.privateChildPrice)}`}
                        {pricing.privateChildPrice !== null && pricing.privateInfantPrice !== null && " · "}
                        {pricing.privateInfantPrice !== null && `Infant ${pricing.privateInfantPrice === 0 ? "free" : money(pricing.privateInfantPrice)}`}
                    </p>
                )}
            </div>
        );
    }

    if (pricing.privateAvailable && pricing.privatePricingModel === "group_total") {
        const rates = pricing.privateTiers.filter((tier) => tier.price !== null);
        const first = rates[0];
        if (!first) return <QuotePreview currency={currency} startingPrice={headlinePrice} />;
        const sameRate = rates.every((tier) => tier.price === first.price);
        const maximum = rates.reduce((max, tier) => Math.max(max, tier.max), 0);

        if (sameRate) {
            return (
                <div className="mt-4 rounded-xl bg-[var(--sand)] px-3 py-2.5">
                    <p className="text-[9px] font-bold uppercase tracking-[0.14em] text-muted-foreground">One private group price</p>
                    <div className="mt-0.5 flex items-end justify-between gap-3"><strong className="font-display text-lg text-[var(--gold)]">{money(first.price!)}</strong><span className="text-[10px] text-muted-foreground">Up to {maximum} guests</span></div>
                </div>
            );
        }

        return (
            <div className="mt-4 border-t border-border/80 pt-3">
                <p className="text-[9px] font-bold uppercase tracking-[0.14em] text-muted-foreground">Private · total group price</p>
                <div className="no-scrollbar -mx-1 mt-2 flex snap-x gap-1.5 overflow-x-auto px-1 pb-1">
                    {rates.map((tier) => <div key={tier.min} className="min-w-[4.8rem] flex-1 snap-start rounded-xl bg-[var(--sand)] px-2 py-2 text-center"><p className="text-[9px] text-muted-foreground">{tier.min}–{tier.max} guests</p><p className="mt-0.5 font-display text-sm font-extrabold text-[var(--gold)]">{money(tier.price!)}</p></div>)}
                </div>
            </div>
        );
    }

    if (pricing.sharedAvailable && pricing.sharedAdultPrice !== null) {
        return <div className="mt-4 rounded-xl bg-[var(--sand)] px-3 py-2.5"><p className="text-[9px] font-bold uppercase tracking-[0.14em] text-muted-foreground">Shared tour · per person</p><p className="mt-0.5 font-display text-lg font-extrabold text-[var(--gold)]">Adult {money(pricing.sharedAdultPrice)}</p></div>;
    }

    return <QuotePreview currency={currency} startingPrice={headlinePrice} />;
}

function QuotePreview({ currency, startingPrice }: { currency: string; startingPrice: number }) {
    if (startingPrice > 0) {
        const price = new Intl.NumberFormat("en-US", { style: "currency", currency, maximumFractionDigits: 0 }).format(startingPrice);
        return <div className="mt-4 rounded-xl bg-[var(--sand)] px-3 py-2.5"><p className="text-[9px] font-bold uppercase tracking-[0.14em] text-muted-foreground">Starting price · final quote confirmed</p><p className="mt-0.5 font-display text-lg font-extrabold text-[var(--gold)]">From {price} <span className="font-sans text-[10px] font-semibold text-muted-foreground">per person</span></p></div>;
    }

    return <div className="mt-4 rounded-xl bg-[var(--sand)] px-3 py-3 text-sm font-bold text-[var(--ocean)]">Request a custom quote</div>;
}
