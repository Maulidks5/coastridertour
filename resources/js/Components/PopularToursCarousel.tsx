import { TourCard } from "@/Components/TourCard";
import type { TourCardData } from "@/types/tour";

export function PopularToursCarousel({ tours }: { tours: TourCardData[] }) {
    if (!tours.length) return null;

    return (
        <div className="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
            {tours.map((tour) => <TourCard key={tour.slug} tour={tour} compact />)}
        </div>
    );
}
