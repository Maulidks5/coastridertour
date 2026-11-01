import { Link } from "./AppLink";
import { ArrowRight, Clock, MapPin } from "lucide-react";
import type { TourCardData } from "@/types/tour";
import { tourPriceSummary } from "@/lib/tourPricing";

export function TourCard({ tour }: { tour: TourCardData; compact?: boolean }) {
    const price = tourPriceSummary(tour.price, tour.currency, tour.pricing);
    const isTailored = tour.experienceType === "safari" || tour.experienceType === "kilimanjaro";
    const actionLabel = price.label === "Request a quote"
        ? "View & request quote"
        : isTailored
          ? "Request exact quote"
          : "Check exact price";

    return (
        <Link
            to={`/tours/${tour.slug}`}
            aria-label={`View ${tour.title}`}
            className="group flex h-full min-w-0 flex-col overflow-hidden rounded-2xl border border-border/70 bg-card shadow-sm transition duration-300 hover:-translate-y-1 hover:border-[var(--gold)]/50 hover:shadow-[var(--shadow-card)]"
        >
            <div className="relative aspect-[16/11] overflow-hidden bg-muted">
                <img
                    src={tour.image}
                    alt={tour.title}
                    loading="lazy"
                    decoding="async"
                    className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                />
                {tour.category && (
                    <span className="absolute left-3 top-3 max-w-[75%] truncate rounded-full bg-[#071d2b]/75 px-3 py-1.5 text-[9px] font-bold uppercase tracking-wide text-white backdrop-blur-sm">
                        {tour.category}
                    </span>
                )}
            </div>

            <div className="flex flex-1 flex-col p-4">
                <h3 className="line-clamp-2 min-h-12 font-display text-lg font-bold leading-6 text-foreground transition group-hover:text-[var(--ocean)]">
                    {tour.title}
                </h3>

                <div className="mt-2 flex min-w-0 flex-wrap items-center gap-x-3 gap-y-1.5 text-[11px] font-medium text-muted-foreground">
                    <span className="inline-flex items-center gap-1">
                        <Clock className="h-3.5 w-3.5 text-[var(--turquoise)]" /> {tour.duration}
                    </span>
                    <span className="inline-flex min-w-0 items-center gap-1 truncate">
                        <MapPin className="h-3.5 w-3.5 shrink-0 text-[var(--turquoise)]" />
                        <span className="truncate">{tour.location}</span>
                    </span>
                </div>

                {tour.pricing && <CardPricingPreview price={price} />}

                <div className="mt-auto pt-4">
                    <span className="inline-flex items-center gap-2 text-sm font-bold text-[var(--ocean)]">
                        {actionLabel} <ArrowRight className="h-4 w-4 shrink-0 transition group-hover:translate-x-1" />
                    </span>
                </div>
            </div>
        </Link>
    );
}

function CardPricingPreview({ price }: { price: ReturnType<typeof tourPriceSummary> }) {
    if (price.label === "Request a quote") {
        return <div className="mt-4 border-t border-border/80 pt-3"><p className="text-[9px] font-bold uppercase tracking-[0.14em] text-muted-foreground">Tailor-made pricing</p><p className="mt-0.5 text-sm font-bold text-[var(--ocean)]">Request a custom quote</p></div>;
    }

    const unit = price.caption.replace(/^From\s+/i, "").replace(/^Per\s+/i, "per ");
    return (
        <div className="mt-4 border-t border-border/80 pt-3">
            <p className="text-[9px] font-bold uppercase tracking-[0.14em] text-muted-foreground">Starting price</p>
            <p className="mt-0.5 font-display text-lg font-extrabold text-[var(--gold)]">
                From {price.label} <span className="font-sans text-[10px] font-semibold text-muted-foreground">{unit}</span>
            </p>
        </div>
    );
}
