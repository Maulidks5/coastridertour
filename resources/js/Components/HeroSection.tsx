import { ArrowRight, MapPin, Sparkles } from "lucide-react";

export type HeroSlideData = {
  id: number;
  eyebrow: string | null;
  title: string;
  description: string | null;
  image: string;
  imageAlt: string;
  imagePosition: string;
  primaryLabel: string;
  primaryUrl: string;
  secondaryLabel: string | null;
  secondaryUrl: string | null;
};

export function HeroSection({ slides }: { slides: HeroSlideData[] }) {
  const hero = slides[0];

  if (!hero) return null;
  const titleParts = splitLastWord(hero.title);

  return (
    <section className="relative isolate overflow-hidden bg-[#063b68] text-white">
      <img src={hero.image} alt={hero.imageAlt} className={`absolute inset-0 h-full w-full object-cover ${positionClass(hero.imagePosition)}`} fetchPriority="high" />
      <div className="absolute inset-0 bg-[#071a2d]/45" />
      <div className="absolute inset-0 bg-gradient-to-t from-[#031d32]/65 via-transparent to-[#031d32]/15" />

      {hero.image.includes("hero-zanzibar-dhow") && (
        <a href="https://commons.wikimedia.org/wiki/File:Dhow_Sunset,_Zanzibar_(10164046475).jpg" target="_blank" rel="noopener noreferrer" className="absolute bottom-2 right-3 z-10 text-[9px] text-white/55 transition hover:text-white sm:bottom-3 sm:right-4">
          Photo: Rod Waddington · CC BY-SA 2.0
        </a>
      )}

      <div className="relative mx-auto flex min-h-[calc(100svh-4.5rem)] max-w-7xl items-center justify-center px-4 py-20 text-center sm:min-h-[calc(100svh-5rem)] sm:px-6 lg:px-8">
        <div className="mx-auto max-w-4xl">
          <div className="inline-flex items-center gap-2 rounded-full border border-white/25 bg-black/15 px-3 py-2 text-[11px] font-bold uppercase tracking-[0.18em] text-white backdrop-blur-sm">
            <MapPin className="h-3.5 w-3.5 text-[var(--gold)]" />
            {hero.eyebrow || "Zanzibar · Tanzania"}
          </div>

          <h1 className="mx-auto mt-5 max-w-4xl font-display text-[2.7rem] font-extrabold leading-[1.02] tracking-[-0.035em] text-balance sm:text-6xl md:text-7xl lg:text-[5rem]">
            {titleParts.leading}<span className="text-[#ffb34a]">{titleParts.accent}</span>
          </h1>
          <p className="mx-auto mt-5 line-clamp-3 max-w-2xl text-sm leading-6 text-white/85 sm:line-clamp-none sm:text-base sm:leading-7 md:text-lg">
            {hero.description || "Discover Zanzibar and Tanzania through thoughtful tours, unforgettable safaris and private transport you can rely on."}
          </p>

          <div className="mt-7 flex flex-col justify-center gap-3 sm:flex-row">
            <a href="/tours" className="group inline-flex min-h-12 items-center justify-center gap-3 rounded-xl bg-[var(--gold)] px-7 text-sm font-extrabold text-[#17324a] shadow-lg transition hover:-translate-y-0.5 hover:brightness-105">
              Explore Zanzibar Tours <ArrowRight className="h-4 w-4 transition group-hover:translate-x-1" />
            </a>
            <a href="/tour-finder" className="inline-flex min-h-12 items-center justify-center gap-2.5 rounded-xl border border-white/35 bg-white/15 px-7 text-sm font-bold text-white backdrop-blur-sm transition hover:bg-white hover:text-[var(--ocean)]">
              <Sparkles className="h-4 w-4" /> Plan My Trip
            </a>
          </div>
        </div>
      </div>
    </section>
  );
}

function splitLastWord(title: string) {
  const words = title.trim().split(/\s+/);
  const accent = words.pop() || "planned.";
  return { leading: `${words.join(" ")} `, accent };
}

function positionClass(position: string) {
  return {
    left: "object-left",
    right: "object-right",
    top: "object-top",
    bottom: "object-bottom",
    center: "object-center",
  }[position] ?? "object-center";
}
