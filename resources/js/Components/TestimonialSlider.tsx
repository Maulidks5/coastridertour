import { useEffect, useState } from "react";
import { ChevronLeft, ChevronRight, Quote, Star } from "lucide-react";

export type PublicTestimonial = { name: string; country: string | null; tourName: string | null; rating: number; content: string };

export function TestimonialSlider({ items }: { items: PublicTestimonial[] }) {
  const [active, setActive] = useState(0);

  useEffect(() => {
    if (items.length < 2) return;
    const timer = window.setInterval(() => setActive((current) => (current + 1) % items.length), 6000);
    return () => window.clearInterval(timer);
  }, [items.length]);

  if (!items.length) return null;
  const item = items[active];
  const initials = item.name.split(" ").map((part) => part[0]).join("").slice(0, 2).toUpperCase();

  return (
    <div className="mx-auto max-w-3xl">
      <article className="rounded-2xl border border-border/80 bg-white p-6 shadow-sm sm:p-8">
        <div className="flex items-center justify-between gap-4">
          <div className="flex gap-1 text-[var(--gold)]">{Array.from({ length: 5 }).map((_, index) => <Star key={index} className={`h-4 w-4 ${index < item.rating ? "fill-current" : "text-border"}`} />)}</div>
          <Quote className="h-7 w-7 text-[var(--turquoise)]/50" />
        </div>
        <blockquote className="mt-5 font-display text-lg font-semibold leading-8 text-foreground sm:text-xl">“{item.content}”</blockquote>
        <div className="mt-6 flex items-center gap-3 border-t border-border/70 pt-5">
          <span className="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-[var(--ocean)] text-sm font-bold text-white">{initials}</span>
          <div><p className="text-sm font-bold">{item.name}</p><p className="text-xs text-muted-foreground">{[item.country, item.tourName].filter(Boolean).join(" · ")}</p></div>
        </div>
      </article>
      {items.length > 1 && <div className="mt-4 flex items-center justify-center gap-3">
        <button type="button" onClick={() => setActive((active - 1 + items.length) % items.length)} aria-label="Previous testimonial" className="grid h-9 w-9 place-items-center rounded-full border border-border bg-white transition hover:border-[var(--ocean)] hover:text-[var(--ocean)]"><ChevronLeft className="h-4 w-4" /></button>
        <div className="flex gap-1.5">{items.map((_, index) => <button key={index} type="button" onClick={() => setActive(index)} aria-label={`Show testimonial ${index + 1}`} className={`h-1.5 rounded-full transition-all ${active === index ? "w-6 bg-[var(--ocean)]" : "w-1.5 bg-border"}`} />)}</div>
        <button type="button" onClick={() => setActive((active + 1) % items.length)} aria-label="Next testimonial" className="grid h-9 w-9 place-items-center rounded-full border border-border bg-white transition hover:border-[var(--ocean)] hover:text-[var(--ocean)]"><ChevronRight className="h-4 w-4" /></button>
      </div>}
    </div>
  );
}
