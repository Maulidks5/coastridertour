import { Link } from "./AppLink";
import { Clock, MapPin, Star } from "lucide-react";
import type { TourCardData } from "@/types/tour";
import { tourPriceSummary } from "@/lib/tourPricing";

export function TourCard({ tour }: { tour: TourCardData }) {
    const price = tourPriceSummary(tour.price, tour.currency, tour.pricing);
    return (
        <article className="group flex h-full flex-col overflow-hidden rounded-2xl bg-card shadow-[var(--shadow-card)] border border-border/50 transition-all hover:-translate-y-1 hover:shadow-[var(--shadow-soft)]">
            <div className="relative aspect-[4/3] overflow-hidden">
                <img
                    src={tour.image}
                    alt={tour.title}
                    loading="lazy"
                    decoding="async"
                    className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                />
                <div className="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[#071d2b]/90 to-transparent px-4 pb-4 pt-12 text-white">
                    <p className="text-[10px] font-bold uppercase tracking-[0.16em] text-white/75">
                        {price.caption}
                    </p>
                    <p className="mt-0.5 font-display text-xl font-extrabold">
                        {price.label}
                    </p>
                </div>
                {tour.reviewsCount > 0 && (
                    <span className="absolute top-3 right-3 flex items-center gap-1 rounded-full bg-[var(--gold)] px-2.5 py-1 text-xs font-bold text-foreground">
                        <Star className="h-3 w-3 fill-current" /> {tour.rating}
                    </span>
                )}
            </div>
            <div className="flex flex-1 flex-col p-5">
                <h3 className="font-display text-xl font-bold text-foreground">
                    {tour.title}
                </h3>
                <p className="mt-2 text-sm text-muted-foreground line-clamp-2">
                    {tour.description}
                </p>
                <div className="mt-4 flex min-w-0 items-center gap-4 text-xs text-muted-foreground">
                    <span className="inline-flex items-center gap-1">
                        <Clock className="h-3.5 w-3.5" /> {tour.duration}
                    </span>
                    <span className="inline-flex min-w-0 items-center gap-1 truncate">
                        <MapPin className="h-3.5 w-3.5 shrink-0" />{" "}
                        <span className="truncate">{tour.location}</span>
                    </span>
                </div>
                <div className="mt-5 flex gap-2 pt-4 border-t border-border/60">
                    <Link
                        to={`/tours/${tour.slug}`}
                        className="flex-1 inline-flex items-center justify-center rounded-full border border-[var(--ocean)] text-[var(--ocean)] text-sm font-semibold py-2 hover:bg-[var(--ocean)] hover:text-white transition"
                    >
                        View Details
                    </Link>
                    <a
                        href={`/book/${tour.slug}`}
                        className="flex-1 inline-flex items-center justify-center rounded-full bg-[var(--gold)] px-2 text-center text-foreground text-sm font-semibold py-2 hover:brightness-95 transition"
                    >
                        {tour.price > 0 ? "Book Now" : "Request a Quote"}
                    </a>
                </div>
            </div>
        </article>
    );
}
