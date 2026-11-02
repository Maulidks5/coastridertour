import { useEffect, useState } from "react";
import { ArrowUpRight, ChevronLeft, ChevronRight, X } from "lucide-react";

type GalleryImage = { path: string; alt: string; tour: { title: string; href: string } | null };

export function HomeGallery({ images }: { images: GalleryImage[] }) {
  const gallery = images.slice(0, 8);
  const [activeIndex, setActiveIndex] = useState<number | null>(null);
  const [touchStart, setTouchStart] = useState<number | null>(null);
  const activeImage = activeIndex === null ? null : gallery[activeIndex];
  const previous = () => setActiveIndex((current) => current === null ? null : (current - 1 + gallery.length) % gallery.length);
  const next = () => setActiveIndex((current) => current === null ? null : (current + 1) % gallery.length);

  useEffect(() => {
    if (activeIndex === null) return;
    const onKeyDown = (event: KeyboardEvent) => {
      if (event.key === "Escape") setActiveIndex(null);
      if (event.key === "ArrowLeft") previous();
      if (event.key === "ArrowRight") next();
    };
    window.addEventListener("keydown", onKeyDown);
    return () => window.removeEventListener("keydown", onKeyDown);
  }, [activeIndex, gallery.length]);

  if (!gallery.length) return null;

  return <>
    <div className="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
      {gallery.map((image, index) => <button key={`${image.path}-${index}`} type="button" onClick={() => setActiveIndex(index)} className={`group relative overflow-hidden rounded-2xl bg-muted text-left focus:outline-none focus-visible:ring-4 focus-visible:ring-[var(--turquoise)]/35 ${index === 0 ? "col-span-2 row-span-2 aspect-square lg:aspect-auto" : "aspect-square"}`} aria-label={`Open ${image.alt}`}>
        <img src={image.path} alt={image.alt} loading="lazy" decoding="async" className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" />
        <span className="absolute inset-x-0 bottom-0 translate-y-full bg-gradient-to-t from-[#071a2d]/90 to-transparent px-4 pb-4 pt-12 text-xs font-semibold text-white transition duration-300 group-hover:translate-y-0">View moment</span>
      </button>)}
    </div>

    {activeImage && activeIndex !== null && <div role="dialog" aria-modal="true" aria-label={activeImage.alt} className="fixed inset-0 z-[100] flex items-center justify-center bg-[#03101de8] p-4 sm:p-8" onClick={() => setActiveIndex(null)}>
      <div className="relative flex h-full w-full max-w-6xl flex-col items-center justify-center" onClick={(event) => event.stopPropagation()}>
        <button type="button" onClick={() => setActiveIndex(null)} aria-label="Close image" className="absolute right-0 top-0 z-10 grid h-11 w-11 place-items-center rounded-full bg-white/15 text-white backdrop-blur transition hover:bg-white/25"><X className="h-5 w-5" /></button>
        <p className="absolute left-0 top-3 rounded-full bg-black/30 px-3 py-1 text-xs font-bold tracking-wide text-white">{activeIndex + 1} / {gallery.length}</p>
        <img src={activeImage.path} alt={activeImage.alt} className="max-h-[76vh] w-auto max-w-full select-none rounded-xl object-contain shadow-2xl" onTouchStart={(event) => setTouchStart(event.touches[0]?.clientX ?? null)} onTouchEnd={(event) => { const end = event.changedTouches[0]?.clientX; if (touchStart !== null && end !== undefined && Math.abs(end - touchStart) > 45) end < touchStart ? next() : previous(); setTouchStart(null); }} />
        <div className="mt-4 flex w-full max-w-3xl items-center justify-between gap-3"><button type="button" onClick={previous} aria-label="Previous image" className="grid h-11 w-11 shrink-0 place-items-center rounded-full border border-white/30 text-white transition hover:bg-white/15"><ChevronLeft className="h-5 w-5" /></button><div className="min-w-0 text-center text-white"><p className="truncate text-sm font-semibold sm:text-base">{activeImage.alt}</p>{activeImage.tour && <a href={activeImage.tour.href} className="mt-2 inline-flex items-center gap-1.5 rounded-full bg-[var(--sun)] px-4 py-2 text-sm font-bold text-[#071a2d] transition hover:brightness-105">Explore {activeImage.tour.title}<ArrowUpRight className="h-4 w-4" /></a>}</div><button type="button" onClick={next} aria-label="Next image" className="grid h-11 w-11 shrink-0 place-items-center rounded-full border border-white/30 text-white transition hover:bg-white/15"><ChevronRight className="h-5 w-5" /></button></div>
      </div>
    </div>}
  </>;
}
