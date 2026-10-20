import { ArrowRight, Binoculars, CarFront, MapPin, MessageCircle, Palmtree } from "lucide-react";
import { getWhatsAppLink, useSiteConfig } from "@/config/site";

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

const services = [
  { icon: Palmtree, label: "Island Tours", detail: "Culture, beaches & ocean" },
  { icon: Binoculars, label: "Tanzania Safaris", detail: "Wildlife journeys made personal" },
  { icon: CarFront, label: "Private Transport", detail: "Airport, hotel & day transfers" },
];

export function HeroSection({ slides }: { slides: HeroSlideData[] }) {
  const site = useSiteConfig();
  const hero = slides[0];

  if (!hero) return null;
  const titleParts = splitLastWord(hero.title);

  return (
    <section className="relative isolate overflow-hidden bg-[#063b68] text-white">
      <img src={hero.image} alt={hero.imageAlt} className={`absolute inset-0 h-full w-full object-cover ${positionClass(hero.imagePosition)}`} fetchPriority="high" />
      <div className="absolute inset-0 bg-[linear-gradient(90deg,rgba(2,25,45,0.78)_0%,rgba(2,31,54,0.48)_42%,rgba(2,25,45,0.04)_76%)] max-sm:bg-[linear-gradient(0deg,rgba(2,25,45,0.88)_0%,rgba(2,31,54,0.36)_70%,rgba(2,25,45,0.08)_100%)]" />
      <div className="absolute inset-x-0 bottom-0 h-48 bg-gradient-to-t from-[#031d32]/75 to-transparent" />

      {hero.image.includes("hero-zanzibar-dhow") && (
        <a href="https://commons.wikimedia.org/wiki/File:Dhow_Sunset,_Zanzibar_(10164046475).jpg" target="_blank" rel="noopener noreferrer" className="absolute bottom-2 right-3 z-10 text-[9px] text-white/55 transition hover:text-white sm:bottom-3 sm:right-4">
          Photo: Rod Waddington · CC BY-SA 2.0
        </a>
      )}

      <div className="relative mx-auto flex min-h-[38rem] max-w-7xl flex-col justify-end px-4 pb-6 pt-28 sm:min-h-[43rem] sm:px-6 sm:pb-8 md:min-h-[46rem] lg:px-8">
        <div className="max-w-[42rem]">
          <div className="inline-flex items-center gap-2 rounded-full border border-white/25 bg-black/15 px-3 py-2 text-[11px] font-bold uppercase tracking-[0.18em] text-white backdrop-blur-sm">
            <MapPin className="h-3.5 w-3.5 text-[var(--gold)]" />
            {hero.eyebrow || "Zanzibar · Tanzania"}
          </div>

          <h1 className="mt-4 max-w-3xl font-display text-[2.55rem] font-extrabold leading-[1.02] tracking-[-0.035em] text-balance sm:mt-5 sm:text-6xl md:text-7xl">
            {titleParts.leading}<span className="text-[#ffb34a]">{titleParts.accent}</span>
          </h1>
          <p className="mt-4 line-clamp-3 max-w-xl text-sm leading-6 text-white/85 sm:mt-5 sm:line-clamp-none sm:text-base sm:leading-7 md:text-lg">
            {hero.description || "Discover Zanzibar and Tanzania through thoughtful tours, unforgettable safaris and private transport you can rely on."}
          </p>

          <div className="mt-6 flex flex-wrap gap-3 sm:mt-7">
            <a href={hero.primaryUrl || "/tours"} className="group inline-flex min-h-12 flex-1 items-center justify-center gap-3 rounded-full bg-[var(--gold)] px-6 text-sm font-extrabold text-[#17324a] shadow-lg transition hover:-translate-y-0.5 hover:brightness-105 sm:flex-none">
              {hero.primaryLabel || "Explore tours"} <ArrowRight className="h-4 w-4 transition group-hover:translate-x-1" />
            </a>
            <a href={getWhatsAppLink("Hello Coast Rider Tour, I would like help planning my trip.", site)} target="_blank" rel="noopener noreferrer" className="inline-flex min-h-12 flex-1 items-center justify-center gap-2.5 rounded-full border border-white/45 bg-black/15 px-6 text-sm font-bold text-white backdrop-blur-sm transition hover:bg-white hover:text-[var(--ocean)] sm:flex-none">
              <MessageCircle className="h-4 w-4" /> Ask a local expert
            </a>
          </div>
        </div>

        <div className="mt-8 hidden border-t border-white/25 pt-5 sm:grid sm:grid-cols-3 sm:gap-5 lg:max-w-4xl">
          {services.map(({ icon: Icon, label, detail }, index) => (
            <a key={label} href={label === "Private Transport" ? "/transport" : label === "Tanzania Safaris" ? "/safaris" : "/tours"} className={`group flex items-center gap-3 ${index > 0 ? "border-l border-white/20 pl-5" : ""}`}>
              <span className="grid h-9 w-9 shrink-0 place-items-center rounded-full border border-white/25 text-[var(--gold)] transition group-hover:border-[var(--gold)]"><Icon className="h-4 w-4" /></span>
              <span className="min-w-0">
                <strong className="block text-sm font-extrabold">{label}</strong>
                <span className="mt-0.5 block text-[11px] text-white/65">{detail}</span>
              </span>
            </a>
          ))}
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
