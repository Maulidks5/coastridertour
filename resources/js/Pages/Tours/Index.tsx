import { FormEvent, useState } from "react";
import { Head, Link, router } from "@inertiajs/react";
import { Search, SlidersHorizontal, X } from "lucide-react";
import { Footer } from "@/Components/Footer";
import { Navbar } from "@/Components/Navbar";
import { TourCard } from "@/Components/TourCard";
import { WhatsAppFloatingButton } from "@/Components/WhatsAppFloatingButton";
import type { TourCardData } from "@/types/tour";

type Props = {
  tours: {
    data: TourCardData[];
    current_page: number;
    last_page: number;
    from: number | null;
    to: number | null;
    total: number;
    links: Array<{ url: string | null; label: string; active: boolean }>;
  };
  categories: Array<{ name: string; slug: string; count: number }>;
  filters: { q: string; category: string | null; type: string | null; duration: string | null; sort: string };
  isSafari: boolean;
  basePath: string;
  eyebrow: string;
  pageTitle: string;
  pageDescription: string;
};

export default function ToursIndex({ tours, categories, filters, isSafari, basePath, eyebrow, pageTitle, pageDescription }: Props) {
  const [search, setSearch] = useState(filters.q);
  const [filtersOpen, setFiltersOpen] = useState(false);
  const hasFilters = Boolean(filters.q || filters.category || filters.type || filters.duration || filters.sort !== "recommended");

  function href(changes: Record<string, string | null>) {
    const params = new URLSearchParams();
    const values = { q: filters.q, category: filters.category, type: filters.type, duration: filters.duration, sort: filters.sort, ...changes };
    Object.entries(values).forEach(([key, value]) => {
      if (value && !(key === "sort" && value === "recommended")) params.set(key, value);
    });
    const query = params.toString();
    return `${basePath}${query ? `?${query}` : ""}`;
  }

  function submitSearch(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    router.get(basePath, { q: search || undefined, category: filters.category || undefined, type: filters.type || undefined, duration: filters.duration || undefined, sort: filters.sort }, { preserveState: true, preserveScroll: true });
  }
  return (
    <div className="min-h-screen bg-background">
      <Head>
        <title>{pageTitle}</title>
        <meta
          name="description"
          content={pageDescription}
        />
      </Head>

      <Navbar />
      <main>
        <section className="relative overflow-hidden bg-[var(--ocean)] text-white">
          <img src={isSafari ? "/images/experiences/hero-safari.webp" : "/images/experiences/hero-ocean.webp"} alt="" aria-hidden="true" className="absolute inset-0 h-full w-full object-cover" />
          <div className="absolute inset-0 bg-gradient-to-r from-[#052f53]/95 via-[#052f53]/72 to-[#052f53]/25" />
          <div className="relative mx-auto flex min-h-[19rem] max-w-7xl flex-col justify-end px-4 py-10 sm:min-h-[22rem] sm:px-6 md:py-12 lg:px-8">
            <p className="text-xs font-bold uppercase tracking-[0.22em] text-[var(--gold)]">
              {eyebrow}
            </p>
            <h1 className="mt-3 max-w-3xl font-display text-3xl font-bold sm:text-4xl md:text-5xl">
              {pageTitle}
            </h1>
            <p className="mt-3 line-clamp-3 max-w-xl text-sm leading-6 text-white/80 sm:line-clamp-none md:text-base md:leading-7">
              {pageDescription}
            </p>
          </div>
        </section>

        <section className="mx-auto max-w-7xl px-4 py-10 sm:px-6 md:py-12 lg:px-8">
          <div className="mb-6 flex items-end justify-between gap-4">
            <div><p className="text-xs font-bold uppercase tracking-[0.2em] text-[var(--turquoise)]">{isSafari ? "Safari collection" : "Our experiences"}</p><h2 className="mt-1 font-display text-2xl font-bold md:text-3xl">Choose your experience</h2></div>
            <p className="hidden text-sm text-muted-foreground sm:block">{tours.total} {tours.total === 1 ? "experience" : "experiences"}</p>
          </div>

          <div className="mb-6 rounded-2xl border border-border bg-card p-4 sm:p-5">
            <div className="flex flex-col gap-3 lg:flex-row">
              <form onSubmit={submitSearch} className="relative flex-1">
                <Search className="pointer-events-none absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                <input aria-label="Search experiences" value={search} onChange={(event) => setSearch(event.target.value)} placeholder={isSafari ? "Search safari packages or locations…" : "Search tours, activities or locations…"} className="w-full rounded-xl border border-input bg-background py-3 pl-11 pr-24 text-sm outline-none transition focus:border-[var(--ocean)] focus:ring-2 focus:ring-[var(--ocean)]/15" />
                <button className="absolute right-1.5 top-1/2 -translate-y-1/2 rounded-lg bg-[var(--ocean)] px-4 py-2 text-xs font-bold text-white transition hover:brightness-110 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[var(--gold)]">Search</button>
              </form>
              {!isSafari && (
                <div className="hidden gap-2 lg:flex">
                  {[
                    { label: "All types", value: null },
                    { label: "Tours", value: "zanzibar_tour" },
                    { label: "Water sports", value: "water_sport" },
                  ].map((type) => <Link key={type.label} href={href({ type: type.value })} preserveScroll className={`shrink-0 rounded-xl px-3.5 py-3 text-xs font-bold transition ${filters.type === type.value ? "bg-[var(--ocean)] text-white" : "border border-border bg-background text-foreground hover:border-[var(--ocean)]"}`}>{type.label}</Link>)}
                </div>
              )}
              <label className="relative hidden lg:block">
                <select aria-label="Filter by duration" value={filters.duration ?? ""} onChange={(event) => router.get(href({ duration: event.target.value || null }), {}, { preserveState: true, preserveScroll: true })} className="h-full min-h-11 rounded-xl border border-input bg-background px-3 py-3 text-xs font-bold outline-none">
                  <option value="">Any duration</option>
                  <option value="half_day">Half day</option>
                  <option value="full_day">Full day</option>
                  <option value="multi_day">Multi-day</option>
                </select>
              </label>
              <label className="relative hidden lg:block">
                <SlidersHorizontal className="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                <select aria-label="Sort experiences" value={filters.sort} onChange={(event) => router.get(href({ sort: event.target.value }), {}, { preserveState: true, preserveScroll: true })} className="h-full min-h-11 rounded-xl border border-input bg-background py-3 pl-10 pr-9 text-xs font-bold outline-none">
                  <option value="recommended">Recommended</option>
                  <option value="price_low">Price: low to high</option>
                  <option value="price_high">Price: high to low</option>
                  <option value="name">Name A–Z</option>
                </select>
              </label>
            </div>
            <div className="mt-3 flex gap-2 lg:hidden">
              <button type="button" onClick={() => setFiltersOpen((open) => !open)} aria-expanded={filtersOpen} className="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-border bg-background px-4 py-3 text-sm font-bold text-[var(--ocean)]"><SlidersHorizontal className="h-4 w-4" /> Filters{(filters.category || filters.type || filters.duration) ? " · Active" : ""}</button>
              <label className="relative flex-1"><select aria-label="Sort tours" value={filters.sort} onChange={(event) => router.get(href({ sort: event.target.value }), {}, { preserveState: true, preserveScroll: true })} className="w-full rounded-xl border border-input bg-background px-3 py-3 text-sm font-bold outline-none"><option value="recommended">Recommended</option><option value="price_low">Price: low first</option><option value="price_high">Price: high first</option><option value="name">Name A–Z</option></select></label>
            </div>
            {filtersOpen && <div className="mt-3 grid gap-4 border-t border-border pt-4 lg:hidden">
              {!isSafari && <div><p className="mb-2 text-xs font-bold uppercase tracking-wider text-muted-foreground">Experience type</p><div className="flex flex-wrap gap-2">{[{ label: "All types", value: null }, { label: "Tours", value: "zanzibar_tour" }, { label: "Water sports", value: "water_sport" }].map((type) => <Link key={type.label} href={href({ type: type.value })} className={`rounded-full px-4 py-2 text-xs font-bold ${filters.type === type.value ? "bg-[var(--ocean)] text-white" : "border border-border"}`}>{type.label}</Link>)}</div></div>}
              {!isSafari && <div><p className="mb-2 text-xs font-bold uppercase tracking-wider text-muted-foreground">Category</p><div className="flex flex-wrap gap-2"><Link href={href({ category: null })} className={`rounded-full px-4 py-2 text-xs font-bold ${!filters.category ? "bg-[var(--ocean)] text-white" : "border border-border"}`}>All tours</Link>{categories.map((category) => <Link key={category.slug} href={href({ category: category.slug })} className={`rounded-full px-4 py-2 text-xs font-bold ${filters.category === category.slug ? "bg-[var(--ocean)] text-white" : "border border-border"}`}>{category.name}</Link>)}</div></div>}
              <div><p className="mb-2 text-xs font-bold uppercase tracking-wider text-muted-foreground">Duration</p><div className="flex flex-wrap gap-2">{[{ label: "Any duration", value: null }, { label: "Half day", value: "half_day" }, { label: "Full day", value: "full_day" }, { label: "Multi-day", value: "multi_day" }].map((item) => <Link key={item.label} href={href({ duration: item.value })} className={`rounded-full px-4 py-2 text-xs font-bold ${filters.duration === item.value ? "bg-[var(--ocean)] text-white" : "border border-border"}`}>{item.label}</Link>)}</div></div>
            </div>}
          </div>

          {!isSafari && <div className="mb-6 hidden gap-2 overflow-x-auto pb-2 lg:flex">
            <Link
              href={href({ category: null })}
              className={`shrink-0 whitespace-nowrap rounded-full px-4 py-2 text-sm font-semibold transition ${
                !filters.category
                  ? "bg-[var(--ocean)] text-white"
                  : "border border-border bg-card text-foreground hover:border-[var(--ocean)]"
              }`}
            >
              All tours
            </Link>
            {categories.map((category) => (
              <Link
                key={category.slug}
                href={href({ category: category.slug })}
                preserveScroll
                className={`shrink-0 whitespace-nowrap rounded-full px-4 py-2 text-sm font-semibold transition ${
                  filters.category === category.slug
                    ? "bg-[var(--ocean)] text-white"
                    : "border border-border bg-card text-foreground hover:border-[var(--ocean)]"
                }`}
              >
                {category.name} <span className="ml-1 text-[10px] opacity-60">{category.count}</span>
              </Link>
            ))}
          </div>}

          {hasFilters && <div className="mb-5 flex flex-wrap items-center gap-2 text-xs">
            <span className="font-semibold text-muted-foreground">Active:</span>
            {filters.q && <Link href={href({ q: null })} className="inline-flex items-center gap-1 rounded-full bg-[var(--sand)] px-3 py-1.5 font-bold">Search: {filters.q} <X className="h-3 w-3" /></Link>}
            {filters.type && <Link href={href({ type: null })} className="inline-flex items-center gap-1 rounded-full bg-[var(--sand)] px-3 py-1.5 font-bold capitalize">{filters.type.replace("_", " ")} <X className="h-3 w-3" /></Link>}
            {filters.category && <Link href={href({ category: null })} className="inline-flex items-center gap-1 rounded-full bg-[var(--sand)] px-3 py-1.5 font-bold">{categories.find((item) => item.slug === filters.category)?.name ?? filters.category} <X className="h-3 w-3" /></Link>}
            {filters.duration && <Link href={href({ duration: null })} className="inline-flex items-center gap-1 rounded-full bg-[var(--sand)] px-3 py-1.5 font-bold">{filters.duration.replace("_", " ")} <X className="h-3 w-3" /></Link>}
            {filters.sort !== "recommended" && <Link href={href({ sort: "recommended" })} className="inline-flex items-center gap-1 rounded-full bg-[var(--sand)] px-3 py-1.5 font-bold">Custom sort <X className="h-3 w-3" /></Link>}
          </div>}

          {tours.data.length > 0 ? (
            <>
            <div className="mb-5 flex items-center justify-between gap-4 text-xs text-muted-foreground">
              <span>Showing {tours.from}–{tours.to} of {tours.total}</span>
              {hasFilters && <Link href={basePath} className="inline-flex items-center gap-1 font-bold text-[var(--ocean)]"><X className="h-3.5 w-3.5" /> Clear filters</Link>}
            </div>
            <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
              {tours.data.map((tour) => <TourCard key={tour.slug} tour={tour} />)}
            </div>
            {tours.last_page > 1 && (
              <nav aria-label="Tour pages" className="mt-10 flex flex-wrap justify-center gap-2">
                {tours.links.map((link, index) => link.url
                  ? <Link key={index} href={link.url} preserveScroll className={`rounded-xl border px-4 py-2.5 text-sm font-bold transition ${link.active ? "border-[var(--ocean)] bg-[var(--ocean)] text-white" : "border-border bg-card text-foreground hover:border-[var(--ocean)]"}`} dangerouslySetInnerHTML={{ __html: link.label }} />
                  : <span key={index} className="rounded-xl border border-border px-4 py-2.5 text-sm text-muted-foreground/40" dangerouslySetInnerHTML={{ __html: link.label }} />)}
              </nav>
            )}
            </>
          ) : (
            <div className="rounded-2xl border border-border bg-card px-6 py-16 text-center">
              <h2 className="font-display text-2xl font-bold">No tours found</h2>
              <p className="mt-2 text-muted-foreground">Try a different search, category or service type.</p>
              <Link href={basePath} className="mt-6 inline-flex rounded-full bg-[var(--ocean)] px-5 py-2.5 font-semibold text-white">
                Clear all filters
              </Link>
            </div>
          )}
        </section>
      </main>
      <Footer />
      <WhatsAppFloatingButton />
    </div>
  );
}
