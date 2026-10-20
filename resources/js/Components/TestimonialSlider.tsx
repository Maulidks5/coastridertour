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
    <div className="mx-auto max-w-4xl">
      <article className="rounded-3xl border border-border bg-card p-7 text-center shadow-[var(--shadow-card)] md:p-10">
        <Quote className="mx-auto h-9 w-9 text-[var(--turquoise)]" />
        <div className="mt-4 flex justify-center gap-1 text-[var(--gold)]">{Array.from({ length: 5 }).map((_, index) => <Star key={index} className={`h-4 w-4 ${index < item.rating ? "fill-current" : "text-border"}`} />)}</div>
        <blockquote className="mx-auto mt-5 max-w-2xl font-display text-xl font-semibold leading-8 text-foreground md:text-2xl">“{item.content}”</blockquote>
        <div className="mt-6 flex items-center justify-center gap-3">
          <span className="grid h-11 w-11 place-items-center rounded-full bg-[var(--ocean)] font-bold text-white">{initials}</span>
          <div className="text-left"><p className="text-sm font-bold">{item.name}</p><p className="text-xs text-muted-foreground">{[item.country, item.tourName].filter(Boolean).join(" · ")}</p></div>
        </div>
      </article>
      {items.length > 1 && <div className="mt-5 flex items-center justify-center gap-4">
        <button type="button" onClick={() => setActive((active - 1 + items.length) % items.length)} aria-label="Previous testimonial" className="grid h-10 w-10 place-items-center rounded-full border border-border bg-card"><ChevronLeft className="h-5 w-5" /></button>
        <div className="flex gap-2">{items.map((_, index) => <button key={index} type="button" onClick={() => setActive(index)} aria-label={`Show testimonial ${index + 1}`} className={`h-2 rounded-full transition-all ${active === index ? "w-7 bg-[var(--ocean)]" : "w-2 bg-border"}`} />)}</div>
        <button type="button" onClick={() => setActive((active + 1) % items.length)} aria-label="Next testimonial" className="grid h-10 w-10 place-items-center rounded-full border border-border bg-card"><ChevronRight className="h-5 w-5" /></button>
      </div>}
    </div>
  );
}
