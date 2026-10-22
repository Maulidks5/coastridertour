import { useEffect, useMemo, useState } from "react";
import { Head, Link } from "@inertiajs/react";
import { Check, ChevronLeft, ChevronRight, Clock, Images, MapPin, MessageCircle, ShieldCheck, Star, X } from "lucide-react";
import { Footer } from "@/Components/Footer";
import { Navbar } from "@/Components/Navbar";
import { SectionHeading } from "@/Components/SectionHeading";
import { TourCard } from "@/Components/TourCard";
import { WhatsAppFloatingButton } from "@/Components/WhatsAppFloatingButton";
import { getWhatsAppLink, useSiteConfig } from "@/config/site";
import type { TourCardData, TourDetailData } from "@/types/tour";
import { tourPriceSummary } from "@/lib/tourPricing";

type Props = {
  tour: TourDetailData;
  relatedTours: TourCardData[];
};

export default function TourShow({ tour, relatedTours }: Props) {
  const site = useSiteConfig();
  const [activeImage, setActiveImage] = useState<number | null>(null);
  const gallery = useMemo(() => {
    const images = [{ path: tour.image, alt: tour.title }, ...tour.images];
    return images.filter((image, index) => images.findIndex((item) => item.path === image.path) === index);
  }, [tour.image, tour.images, tour.title]);
  const isSafari = tour.experienceType === "safari";
  const cataloguePath = isSafari ? "/safaris" : "/tours";
  const catalogueLabel = isSafari ? "All safaris" : "All tours";
  const price = new Intl.NumberFormat("en-US", {
    style: "currency",
    currency: tour.currency,
    maximumFractionDigits: 0,
  }).format(tour.price);
  const priceSummary = tourPriceSummary(tour.price, tour.currency, tour.pricing);
  const priceLabel = priceSummary.label;
  const primaryCta = tour.price > 0 ? "Request to book" : "Request a quote";
  const bookingMessage = `Hello Coast Rider Tour, I would like to book ${tour.title}.`;
  const canonicalUrl = typeof window !== "undefined" ? window.location.href.split("?")[0] : "";
  const structuredData = {
    "@context": "https://schema.org",
    "@type": "TouristTrip",
    name: tour.title,
    description: tour.metaDescription || tour.description,
    image: tour.image,
    touristType: tour.experienceType === "safari" ? "Safari travellers" : "Zanzibar travellers",
    itinerary: tour.location,
    provider: {
      "@type": "TravelAgency",
      name: site.name,
      telephone: site.phone,
      email: site.email,
      address: site.location,
    },
    ...(tour.price > 0 ? {
      offers: {
        "@type": "Offer",
        price: tour.price,
        priceCurrency: tour.currency,
        url: canonicalUrl,
        availability: "https://schema.org/InStock",
      },
    } : {}),
  };

  useEffect(() => {
    if (activeImage === null) return;
    const onKeyDown = (event: KeyboardEvent) => {
      if (event.key === "Escape") setActiveImage(null);
      if (event.key === "ArrowLeft") setActiveImage((current) => current === null ? null : (current - 1 + gallery.length) % gallery.length);
      if (event.key === "ArrowRight") setActiveImage((current) => current === null ? null : (current + 1) % gallery.length);
    };
    document.body.style.overflow = "hidden";
    window.addEventListener("keydown", onKeyDown);
    return () => {
      document.body.style.overflow = "";
      window.removeEventListener("keydown", onKeyDown);
    };
  }, [activeImage, gallery.length]);

  return (
    <div className="min-h-screen bg-background pb-20 lg:pb-0">
      <Head>
        <title>{tour.metaTitle || tour.title}</title>
        <meta name="description" content={tour.metaDescription || tour.description} />
        <meta property="og:type" content="website" />
        <meta property="og:title" content={tour.metaTitle || tour.title} />
        <meta property="og:description" content={tour.metaDescription || tour.description} />
        <meta property="og:image" content={tour.image} />
        <meta property="og:url" content={canonicalUrl} />
        <meta name="twitter:card" content="summary_large_image" />
        <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(structuredData) }} />
      </Head>

      <Navbar />
      <main>
        <section className="relative min-h-[30rem] overflow-hidden sm:min-h-[34rem] lg:min-h-[60vh]">
          <img src={tour.image} alt={tour.title} className="absolute inset-0 h-full w-full object-cover" />
          <div className="absolute inset-0 bg-gradient-to-t from-[#10182d]/95 via-[#10182d]/45 to-[#10182d]/30" />
          <div className="relative mx-auto flex min-h-[30rem] max-w-7xl items-end px-4 pb-8 pt-16 sm:min-h-[34rem] sm:px-6 sm:pb-10 lg:min-h-[60vh] lg:px-8 lg:pb-12 lg:pt-24">
            <div className="max-w-4xl text-white">
              <Link href={cataloguePath} className="inline-flex items-center gap-1 text-sm font-medium text-white/75 hover:text-white">
                <ChevronLeft className="h-4 w-4" /> {catalogueLabel}
              </Link>
              <p className="mt-6 text-xs font-bold uppercase tracking-[0.22em] text-[var(--gold)]">{tour.category}</p>
              <h1 className="mt-3 font-display text-3xl font-bold sm:text-4xl md:text-6xl">{tour.title}</h1>
              <div className="mt-4 inline-flex items-center gap-3 rounded-2xl bg-white/95 px-4 py-3 text-[var(--ocean)] shadow-lg backdrop-blur">
                <span className="text-[10px] font-bold uppercase tracking-[0.15em] text-muted-foreground">{tour.price > 0 ? "Starting from" : "Pricing"}</span>
                <strong className="font-display text-xl">{priceLabel}</strong>
              </div>
              <div className="mt-5 flex flex-wrap gap-x-6 gap-y-3 text-sm text-white/85">
                <span className="inline-flex items-center gap-2"><Clock className="h-4 w-4" /> {tour.duration}</span>
                <span className="inline-flex items-center gap-2"><MapPin className="h-4 w-4" /> {tour.location}</span>
                {tour.reviewsCount > 0 && <span className="hidden items-center gap-2 sm:inline-flex"><Star className="h-4 w-4 fill-[var(--gold)] text-[var(--gold)]" />{tour.rating} ({tour.reviewsCount} reviews)</span>}
                {tour.minimumAge !== null && <span className="hidden sm:inline">Minimum age: {tour.minimumAge}</span>}
                {tour.difficulty && <span className="hidden sm:inline">Level: {tour.difficulty}</span>}
              </div>
            </div>
          </div>
        </section>

        <section className="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 md:py-16 lg:grid-cols-[minmax(0,1fr)_23rem] lg:px-8">
          <div className="space-y-12">
            <section>
              <p className="text-sm font-bold uppercase tracking-[0.2em] text-[var(--turquoise)]">Overview</p>
              <h2 className="mt-2 font-display text-3xl font-bold">About this experience</h2>
              <p className="mt-5 max-w-3xl whitespace-pre-line text-base leading-8 text-muted-foreground">{tour.fullDescription}</p>
            </section>

            {gallery.length > 1 && (
              <section>
                <div className="flex items-end justify-between gap-4">
                  <h2 className="font-display text-3xl font-bold">Experience gallery</h2>
                  <button type="button" onClick={() => setActiveImage(0)} className="inline-flex shrink-0 items-center gap-2 text-sm font-bold text-[var(--ocean)]">
                    <Images className="h-4 w-4" /> View all
                  </button>
                </div>
                <div className="mt-5 grid grid-cols-2 gap-2 overflow-hidden rounded-2xl sm:grid-cols-4 sm:grid-rows-2">
                  {gallery.slice(0, 5).map((image, index) => (
                    <button type="button" key={image.path} onClick={() => setActiveImage(index)} className={`group relative overflow-hidden bg-muted ${index === 0 ? "col-span-2 row-span-2 aspect-[4/3] sm:aspect-auto" : "aspect-[4/3]"}`}>
                      <img src={image.path} alt={image.alt} loading="lazy" className="h-full w-full object-cover transition duration-500 group-hover:scale-105" />
                      {index === 4 && gallery.length > 5 && <span className="absolute inset-0 grid place-items-center bg-[#10182d]/65 text-sm font-bold text-white">+{gallery.length - 5} photos</span>}
                    </button>
                  ))}
                </div>
              </section>
            )}

            {tour.highlights.length > 0 && (
              <section>
                <h2 className="font-display text-3xl font-bold">Tour highlights</h2>
                <div className="mt-5 grid gap-3 sm:grid-cols-2">
                  {tour.highlights.map((highlight) => <div key={highlight} className="flex items-start gap-3 rounded-xl border border-border bg-card p-4"><Check className="mt-0.5 h-5 w-5 shrink-0 text-[var(--turquoise)]" /><span className="font-medium">{highlight}</span></div>)}
                </div>
              </section>
            )}

            {(tour.included.length > 0 || tour.notIncluded.length > 0) && (
              <section className="grid gap-6 md:grid-cols-2">
                {tour.included.length > 0 && <div className="rounded-2xl border border-border bg-card p-6">
                  <h2 className="font-display text-2xl font-bold">What’s included</h2>
                  <ul className="mt-5 space-y-3">{tour.included.map((item) => <li key={item} className="flex gap-3 text-sm"><Check className="h-5 w-5 shrink-0 text-[var(--turquoise)]" /> {item}</li>)}</ul>
                </div>}
                {tour.notIncluded.length > 0 && <div className="rounded-2xl border border-border bg-card p-6">
                  <h2 className="font-display text-2xl font-bold">Not included</h2>
                  <ul className="mt-5 space-y-3">{tour.notIncluded.map((item) => <li key={item} className="flex gap-3 text-sm"><X className="h-5 w-5 shrink-0 text-orange-600" /> {item}</li>)}</ul>
                </div>}
              </section>
            )}

            {tour.itinerary.length > 0 && (
              <section>
                <h2 className="font-display text-3xl font-bold">Your itinerary</h2>
                <ol className="mt-6 space-y-6 border-l-2 border-[var(--turquoise)]/25 pl-7">
                  {tour.itinerary.map((item, index) => <li key={`${item.title}-${index}`} className="relative">
                    <span className="absolute -left-[2.15rem] top-1 grid h-4 w-4 place-items-center rounded-full bg-[var(--turquoise)] ring-4 ring-background" />
                    {item.time && <p className="text-xs font-bold uppercase tracking-wider text-[var(--turquoise)]">{item.time}</p>}
                    <h3 className="mt-1 text-xl font-bold">{item.title}</h3>
                    {item.description && <p className="mt-1 text-sm leading-6 text-muted-foreground">{item.description}</p>}
                  </li>)}
                </ol>
              </section>
            )}

            {tour.pickupDetails && <section className="rounded-2xl bg-[var(--sand)] p-6 md:p-8">
              <div className="flex items-start gap-4"><ShieldCheck className="h-7 w-7 shrink-0 text-[var(--ocean)]" /><div><h2 className="font-display text-2xl font-bold">Pickup details</h2><p className="mt-2 text-sm leading-6 text-muted-foreground">{tour.pickupDetails}</p></div></div>
            </section>}

            {tour.pricing.privateAvailable && tour.pricing.privatePricingModel === "per_person_tiered" && <section className="rounded-2xl border border-border bg-card p-6 md:p-8">
              <p className="text-sm font-bold uppercase tracking-[0.2em] text-[var(--turquoise)]">Clear pricing</p>
              <h2 className="mt-2 font-display text-3xl font-bold">Private tour price per person</h2>
              <p className="mt-2 text-sm leading-6 text-muted-foreground">The adult rate becomes lower as the adult group grows. Children are priced separately.</p>
              <div className="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-4">
                {tour.pricing.privatePerPersonTiers.map((tier) => <div key={tier.min} className="rounded-xl bg-[var(--sand)]/60 p-4"><p className="text-xs font-bold uppercase tracking-wide text-muted-foreground">{tier.min >= 4 ? `${tier.min}+ adults` : `${tier.min} adult${tier.min === 1 ? "" : "s"}`}</p><p className="mt-1 font-display text-2xl font-bold text-[var(--ocean)]">{tier.price === null ? "Quote" : new Intl.NumberFormat("en-US", { style: "currency", currency: tour.currency, maximumFractionDigits: 0 }).format(tier.price)}</p><p className="text-xs text-muted-foreground">per adult</p></div>)}
              </div>
              <div className="mt-4 flex flex-wrap gap-3 text-sm"><span className="rounded-full border border-border px-4 py-2">Child 3–12: {tour.pricing.privateChildPrice === null ? "quote" : new Intl.NumberFormat("en-US", { style: "currency", currency: tour.currency, maximumFractionDigits: 0 }).format(tour.pricing.privateChildPrice)}</span><span className="rounded-full border border-border px-4 py-2">Infant 0–2: {tour.pricing.privateInfantPrice === null ? "quote" : new Intl.NumberFormat("en-US", { style: "currency", currency: tour.currency, maximumFractionDigits: 0 }).format(tour.pricing.privateInfantPrice)}</span></div>
              {tour.pricing.pricingNote && <p className="mt-4 text-xs leading-5 text-muted-foreground">{tour.pricing.pricingNote}</p>}
            </section>}

            {tour.faqs.length > 0 && <section>
              <p className="text-sm font-bold uppercase tracking-[0.2em] text-[var(--turquoise)]">Good to know</p>
              <h2 className="mt-2 font-display text-3xl font-bold">Frequently asked questions</h2>
              <div className="mt-5 divide-y divide-border overflow-hidden rounded-2xl border border-border bg-card">
                {tour.faqs.map((faq, index) => <details key={`${faq.question}-${index}`} className="group p-5 open:bg-[var(--sand)]/35">
                  <summary className="flex cursor-pointer list-none items-center justify-between gap-4 font-bold"><span>{faq.question}</span><span className="text-xl text-[var(--turquoise)] transition group-open:rotate-45">+</span></summary>
                  <p className="mt-3 max-w-3xl text-sm leading-6 text-muted-foreground">{faq.answer}</p>
                </details>)}
              </div>
            </section>}
          </div>

          <aside className="hidden h-fit lg:sticky lg:top-28 lg:block">
            <div className="rounded-2xl border border-border bg-card p-6 shadow-[var(--shadow-soft)]">
              <p className="text-xs font-bold uppercase tracking-widest text-muted-foreground">{priceSummary.caption}</p>
              <p className="mt-1 font-display text-4xl font-bold text-[var(--ocean)]">{priceLabel}</p>
              <div className="my-6 h-px bg-border" />
              <div className="space-y-3 text-sm">
                <div className="flex items-center gap-3"><Clock className="h-4 w-4 text-[var(--turquoise)]" /><span>{tour.duration}</span></div>
                <div className="flex items-center gap-3"><MapPin className="h-4 w-4 text-[var(--turquoise)]" /><span>{tour.location}</span></div>
                {tour.difficulty && <div className="flex items-center gap-3"><ShieldCheck className="h-4 w-4 text-[var(--turquoise)]" /><span>{tour.difficulty} level</span></div>}
              </div>
              <div className="my-6 h-px bg-border" />
              <div className="rounded-xl bg-[var(--sand)]/65 p-4 text-xs leading-5 text-muted-foreground">
                {tour.price > 0 ? <><strong className="block text-foreground">See your estimated total</strong><span>Select your date and number of travellers. We show the estimate before you send the request.</span></> : <><strong className="block text-foreground">Tailored pricing</strong><span>Send your date and group size for a personalised quote.</span></>}
              </div>
              <div className="mt-4 grid grid-cols-2 gap-2 text-xs text-muted-foreground"><span className="inline-flex items-center gap-1.5"><ShieldCheck className="h-4 w-4 text-[var(--turquoise)]" /> No payment now</span><span className="inline-flex items-center gap-1.5"><MessageCircle className="h-4 w-4 text-[var(--turquoise)]" /> Local support</span></div>
              <a href={`/book/${tour.slug}`} className="mt-6 inline-flex w-full items-center justify-center rounded-full bg-[var(--gold)] px-5 py-3.5 font-bold text-foreground transition hover:brightness-95">{primaryCta}</a>
              <a href={getWhatsAppLink(bookingMessage, site)} target="_blank" rel="noopener noreferrer" className="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-full bg-[#25D366] px-5 py-3 font-bold text-white transition hover:brightness-95"><MessageCircle className="h-5 w-5" /> Ask on WhatsApp</a>
              <p className="mt-4 text-center text-xs text-muted-foreground">Availability and final price are confirmed before booking.</p>
            </div>
          </aside>
        </section>

        {relatedTours.length > 0 && <section className="bg-[var(--sand)]/60">
          <div className="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <SectionHeading eyebrow="Keep exploring" title={isSafari ? "More safari ideas" : "You may also like"} />
            <div className="grid gap-6 md:grid-cols-3">{relatedTours.map((item) => <TourCard key={item.slug} tour={item} />)}</div>
          </div>
        </section>}
      </main>
      <Footer />

      <div className="fixed inset-x-0 bottom-0 z-40 border-t border-border bg-background/95 p-3 shadow-[0_-8px_30px_rgba(16,24,45,0.12)] backdrop-blur lg:hidden">
        <div className="mx-auto flex max-w-lg items-center gap-2.5">
          <div className="min-w-0 flex-1"><p className="text-[10px] font-semibold uppercase tracking-wide text-muted-foreground">{tour.price > 0 ? "Starting from" : "Pricing"}</p><p className="truncate font-display text-lg font-bold text-[var(--ocean)]">{priceLabel}</p></div>
          <a href={getWhatsAppLink(bookingMessage, site)} target="_blank" rel="noopener noreferrer" aria-label="Ask about this tour on WhatsApp" className="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-[#25D366] text-white"><MessageCircle className="h-5 w-5" /></a>
          <a href={`/book/${tour.slug}`} className="inline-flex min-h-11 shrink-0 items-center justify-center rounded-full bg-[var(--gold)] px-4 text-center text-xs font-bold leading-4 text-foreground">{tour.price > 0 ? "Request to book" : "Request quote"}</a>
        </div>
      </div>
      <div className="hidden lg:block"><WhatsAppFloatingButton /></div>

      {activeImage !== null && <div className="fixed inset-0 z-[70] flex items-center justify-center bg-[#070d1c]/95 p-4" role="dialog" aria-modal="true" aria-label="Tour image gallery">
        <button type="button" onClick={() => setActiveImage(null)} aria-label="Close gallery" className="absolute right-4 top-4 grid h-11 w-11 place-items-center rounded-full bg-white/10 text-white hover:bg-white/20"><X className="h-6 w-6" /></button>
        {gallery.length > 1 && <button type="button" onClick={() => setActiveImage((activeImage - 1 + gallery.length) % gallery.length)} aria-label="Previous image" className="absolute left-3 grid h-11 w-11 place-items-center rounded-full bg-white/10 text-white hover:bg-white/20 sm:left-6"><ChevronLeft className="h-7 w-7" /></button>}
        <img src={gallery[activeImage].path} alt={gallery[activeImage].alt} className="max-h-[82vh] max-w-[88vw] rounded-xl object-contain" />
        {gallery.length > 1 && <button type="button" onClick={() => setActiveImage((activeImage + 1) % gallery.length)} aria-label="Next image" className="absolute right-3 grid h-11 w-11 place-items-center rounded-full bg-white/10 text-white hover:bg-white/20 sm:right-6"><ChevronRight className="h-7 w-7" /></button>}
        <p className="absolute bottom-5 text-sm font-medium text-white/75">{activeImage + 1} / {gallery.length}</p>
      </div>}
    </div>
  );
}


