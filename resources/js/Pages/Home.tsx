import { Head, Link } from "@inertiajs/react";
import { Navbar } from "@/Components/Navbar";
import { HeroSection, type HeroSlideData } from "@/Components/HeroSection";
import { SectionHeading } from "@/Components/SectionHeading";
import { PopularToursCarousel } from "@/Components/PopularToursCarousel";
import {
    TestimonialSlider,
    type PublicTestimonial,
} from "@/Components/TestimonialSlider";
import { HomeGallery, InstagramIcon } from "@/Components/HomeGallery";
import { Footer } from "@/Components/Footer";
import { WhatsAppFloatingButton } from "@/Components/WhatsAppFloatingButton";
import { getWhatsAppLink, useSiteConfig } from "@/config/site";
import type { TourCardData } from "@/types/tour";
import {
    Users,
    Car,
    CalendarCheck,
    MessageCircle,
    Award,
    Shield,
    HeartHandshake,
    Sparkles,
    ArrowRight,
    Compass,
    CheckCircle2,
} from "lucide-react";

const icons = {
    users: Users,
    car: Car,
    calendar: CalendarCheck,
    message: MessageCircle,
    award: Award,
    sparkles: Sparkles,
    heart: HeartHandshake,
    shield: Shield,
};

const adventureOptions = [
    {
        eyebrow: "Ocean & island",
        title: "Zanzibar Tours",
        description: "Culture, beaches, snorkeling and unforgettable island days.",
        image: "/images/experiences/hero-ocean.webp",
        href: "/tours",
        cta: "Explore Zanzibar",
    },
    {
        eyebrow: "Wildlife journeys",
        title: "Tanzania Safaris",
        description: "Private mainland adventures planned around your time and style.",
        image: "/images/experiences/hero-safari.webp",
        href: "/safaris",
        cta: "Explore safaris",
    },
    {
        eyebrow: "Travel with ease",
        title: "Private Transport",
        description: "Reliable airport, hotel and day transfers across Zanzibar.",
        image: "/images/experiences/transport-minivan.jpg",
        href: "/transport",
        cta: "Plan a transfer",
    },
    {
        eyebrow: "Roof of Africa",
        title: "Kilimanjaro Treks",
        description: "Day hikes and guided summit routes planned with licensed mountain professionals.",
        image: "/images/tours/kilimanjaro-day-hike/cover.webp",
        href: "/tours?category=kilimanjaro-treks",
        cta: "Explore Kilimanjaro",
    },
];

type HomeItem = {
    title: string;
    description: string | null;
    meta: string | null;
    icon: keyof typeof icons | null;
};

type HomePageProps = {
    tours: TourCardData[];
    safaris: TourCardData[];
    featuredTour: TourCardData | null;
    content: Record<string, string>;
    featuredHighlights: string[];
    homeItems: Record<string, HomeItem[]>;
    galleryImages: Array<{ path: string; alt: string }>;
    heroSlides: HeroSlideData[];
    testimonials: PublicTestimonial[];
};

export default function HomePage({
    tours,
    safaris,
    featuredTour,
    featuredHighlights,
    homeItems,
    galleryImages,
    heroSlides,
    testimonials,
    content,
}: HomePageProps) {
    const site = useSiteConfig();
    const homepageTours = featuredTour
        ? [featuredTour, ...tours.filter((tour) => tour.slug !== featuredTour.slug)].slice(0, 4)
        : tours.slice(0, 4);

    return (
        <div className="min-h-screen bg-background">
            <Head>
                <title>{site.seoTitle}</title>
                <meta name="description" content={site.seoDescription} />
            </Head>
            <Navbar />
            <main>
                <HeroSection slides={heroSlides} />

                {/* Trust Bar */}
                <section className="border-y border-border/60 bg-card/50">
                    <div className="mx-auto grid max-w-7xl grid-cols-2 gap-4 px-4 py-6 sm:px-6 md:grid-cols-4 lg:px-8">
                        {(homeItems.trust ?? []).map((item) => {
                            const Icon = icons[item.icon ?? "sparkles"] ?? Sparkles;
                            return (
                                <div key={item.title} className="flex items-center gap-3">
                                    <div className="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-[var(--turquoise)]/15 text-[var(--ocean)]">
                                        <Icon className="h-5 w-5" />
                                    </div>
                                    <span className="text-sm font-semibold text-foreground">{item.title}</span>
                                </div>
                            );
                        })}
                    </div>
                </section>

                {/* Featured Zanzibar and safari experiences */}
                <section
                    id="tours"
                    className="mx-auto max-w-7xl scroll-mt-20 px-4 pb-8 pt-14 sm:px-6 md:pb-10 md:pt-20 lg:px-8"
                >
                    <SectionHeading
                        eyebrow="Handpicked experiences"
                        title="Featured Zanzibar & Safari Experiences"
                        subtitle="Compare island tours and mainland safari ideas, then choose the journey that fits your trip."
                    />

                    <div className="mb-5 mt-10 flex items-end justify-between gap-4">
                        <div>
                            <p className="text-[10px] font-bold uppercase tracking-[0.2em] text-[var(--turquoise)]">Island experiences</p>
                            <h3 className="mt-1 font-display text-2xl font-bold text-foreground">Popular Zanzibar Tours</h3>
                        </div>
                        <Link href="/tours" className="hidden items-center gap-1 text-sm font-bold text-[var(--ocean)] hover:underline sm:inline-flex">
                            View all <ArrowRight className="h-4 w-4" />
                        </Link>
                    </div>
                    <PopularToursCarousel tours={homepageTours} />

                    <div className="mb-5 mt-14 flex items-end justify-between gap-4 border-t border-border/70 pt-10">
                        <div>
                            <p className="text-[10px] font-bold uppercase tracking-[0.2em] text-[var(--turquoise)]">Mainland journeys</p>
                            <h3 className="mt-1 font-display text-2xl font-bold text-foreground">Tanzania Safari Ideas</h3>
                        </div>
                        <Link href="/safaris" className="hidden items-center gap-1 text-sm font-bold text-[var(--ocean)] hover:underline sm:inline-flex">
                            View all <ArrowRight className="h-4 w-4" />
                        </Link>
                    </div>
                    <PopularToursCarousel tours={safaris} />

                    <div className="mt-8 grid grid-cols-2 gap-3 sm:hidden">
                        <Link href="/tours" className="inline-flex items-center justify-center rounded-xl border border-[var(--ocean)] px-3 py-3 text-xs font-bold text-[var(--ocean)]">All Zanzibar tours</Link>
                        <Link href="/safaris" className="inline-flex items-center justify-center rounded-xl bg-[var(--ocean)] px-3 py-3 text-xs font-bold text-white">All safaris</Link>
                    </div>
                </section>

                {/* Choose your adventure */}
                <section className="bg-[var(--sand)]/35">
                    <div className="mx-auto max-w-7xl px-4 py-14 sm:px-6 md:py-16 lg:px-8">
                        <div className="mx-auto max-w-3xl text-center">
                            <p className="text-xs font-bold uppercase tracking-[0.22em] text-[var(--turquoise)]">
                                Choose your adventure
                            </p>
                            <h2 className="mt-3 font-display text-3xl font-bold tracking-tight md:text-5xl">
                                How would you like to explore?
                            </h2>
                            <p className="mx-auto mt-4 max-w-2xl text-sm leading-7 text-muted-foreground md:text-base">
                                Start with the experience that fits your trip. Our local team can combine everything into one smooth plan.
                            </p>
                        </div>

                        <div className="mt-9 grid gap-5 md:grid-cols-2 xl:grid-cols-4">
                            {adventureOptions.map((option, index) => (
                                <Link
                                    key={option.title}
                                    href={option.href}
                                    className="group relative isolate min-h-[20rem] overflow-hidden rounded-3xl bg-[var(--ocean)] shadow-[var(--shadow-soft)]"
                                >
                                    <img
                                        src={option.image}
                                        alt=""
                                        aria-hidden
                                        loading="lazy"
                                        className="absolute inset-0 h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                    />
                                    <div className="absolute inset-0 bg-gradient-to-t from-[#071a2d]/95 via-[#071a2d]/25 to-[#071a2d]/5" />
                                    <span className="absolute right-5 top-5 grid h-9 w-9 place-items-center rounded-full border border-white/30 bg-black/10 text-xs font-bold text-white backdrop-blur-sm">
                                        0{index + 1}
                                    </span>
                                    <div className="absolute inset-x-0 bottom-0 p-6 text-white md:p-7">
                                        <p className="text-[10px] font-bold uppercase tracking-[0.2em] text-[var(--gold)]">
                                            {option.eyebrow}
                                        </p>
                                        <h3 className="mt-2 font-display text-3xl font-bold">
                                            {option.title}
                                        </h3>
                                        <p className="mt-2 max-w-sm text-sm leading-6 text-white/70">
                                            {option.description}
                                        </p>
                                        <span className="mt-5 inline-flex items-center gap-2 text-sm font-bold">
                                            {option.cta}
                                            <ArrowRight className="h-4 w-4 transition group-hover:translate-x-1" />
                                        </span>
                                    </div>
                                </Link>
                            ))}
                        </div>

                        <div className="mt-8 flex flex-col items-center justify-between gap-4 rounded-2xl bg-[var(--sand)]/60 px-5 py-5 text-center sm:flex-row sm:text-left md:px-7">
                            <div>
                                <p className="font-display text-lg font-bold text-[var(--ocean)]">
                                    Not sure which experience fits you?
                                </p>
                                <p className="mt-1 text-xs leading-5 text-muted-foreground">
                                    Answer a few short questions and get a suitable starting point.
                                </p>
                            </div>
                            <Link href="/tour-finder" className="inline-flex shrink-0 items-center gap-2 rounded-xl bg-[var(--ocean)] px-5 py-3 text-sm font-bold text-white transition hover:brightness-110">
                                <Sparkles className="h-4 w-4" /> Find My Perfect Tour
                            </Link>
                        </div>
                    </div>
                </section>

                {/* Booking clarity */}
                <section className="border-y border-border/60 bg-white">
                    <div className="mx-auto max-w-7xl px-4 py-14 sm:px-6 md:py-16 lg:px-8">
                        <div className="grid gap-8 lg:grid-cols-[20rem_minmax(0,1fr)] lg:items-center">
                            <div>
                                <p className="text-xs font-bold uppercase tracking-[0.2em] text-[var(--turquoise)]">
                                    Simple & personal
                                </p>
                                <h2 className="mt-2 font-display text-3xl font-bold tracking-tight text-foreground">
                                    From choosing a tour to confirmed pickup
                                </h2>
                                <p className="mt-3 text-sm leading-6 text-muted-foreground">
                                    Send your plans without paying online. Our local team checks availability, pickup and the final total first.
                                </p>
                            </div>
                            <ol className="grid gap-3 sm:grid-cols-3">
                                {[
                                    { icon: Compass, number: "01", title: "Choose your experience", description: "Explore tours with duration, inclusions and clear group-size pricing." },
                                    { icon: CalendarCheck, number: "02", title: "Share your trip details", description: "Tell us your date, group size and hotel or pickup area." },
                                    { icon: CheckCircle2, number: "03", title: "Receive confirmation", description: "We confirm availability, pickup and the final price before payment." },
                                ].map(({ icon: Icon, number, title, description }) => (
                                    <li key={number} className="relative rounded-2xl border border-border bg-[var(--sand)]/30 p-5">
                                        <div className="flex items-center justify-between">
                                            <span className="grid h-10 w-10 place-items-center rounded-xl bg-[var(--ocean)] text-white">
                                                <Icon className="h-5 w-5" />
                                            </span>
                                            <span className="font-display text-2xl font-extrabold text-[var(--ocean)]/15">{number}</span>
                                        </div>
                                        <h3 className="mt-4 font-display text-lg font-bold">{title}</h3>
                                        <p className="mt-2 text-xs leading-5 text-muted-foreground">{description}</p>
                                    </li>
                                ))}
                            </ol>
                        </div>
                    </div>
                </section>

                {/* About Preview */}
                <section
                    id="about"
                    className="scroll-mt-20 bg-[oklch(0.20_0.04_240)] text-white"
                >
                    <div className="mx-auto grid max-w-7xl items-center gap-10 px-4 py-16 sm:px-6 md:py-20 lg:grid-cols-[minmax(0,1fr)_30rem] lg:px-8">
                        <div>
                            <span className="text-xs font-semibold uppercase tracking-[0.2em] text-[var(--gold)]">
                                About Coast Rider Tour
                            </span>
                            <h2 className="mt-3 max-w-2xl font-display text-3xl font-bold md:text-4xl">
                                {content.why_title}
                            </h2>
                            <p className="mt-4 max-w-2xl text-sm leading-7 text-white/70">
                                {site.footerDescription}
                            </p>
                            <Link
                                href="/about"
                                className="mt-7 inline-flex items-center gap-2 rounded-full bg-[var(--gold)] px-6 py-3 text-sm font-bold text-foreground"
                            >
                                Learn more about us{" "}
                                <ArrowRight className="h-4 w-4" />
                            </Link>
                        </div>
                        <div className="grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                            {(homeItems.why ?? []).slice(0, 3).map((item) => {
                                const Icon =
                                    icons[item.icon ?? "sparkles"] ?? Sparkles;
                                return (
                                    <div
                                        key={item.title}
                                        className="flex items-center gap-4 rounded-2xl border border-white/10 bg-white/5 p-4"
                                    >
                                        <span className="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white/10 text-[var(--gold)]">
                                            <Icon className="h-5 w-5" />
                                        </span>
                                        <div>
                                            <h3 className="font-bold">
                                                {item.title}
                                            </h3>
                                            {item.description && (
                                                <p className="mt-1 line-clamp-1 text-xs text-white/60">
                                                    {item.description}
                                                </p>
                                            )}
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    </div>
                </section>

                {/* Testimonials */}
                <section
                    id="testimonials"
                    className="mx-auto max-w-7xl scroll-mt-20 px-4 py-14 sm:px-6 md:py-16 lg:px-8"
                >
                    <SectionHeading
                        eyebrow="Traveller feedback"
                        title={content.testimonials_title}
                    />
                    {testimonials.length > 0 ? (
                        <TestimonialSlider items={testimonials} />
                    ) : (
                        <div className="mx-auto max-w-3xl rounded-3xl border border-dashed border-[var(--turquoise)]/40 bg-[var(--sand)]/45 px-6 py-10 text-center">
                            <MessageCircle className="mx-auto h-9 w-9 text-[var(--turquoise)]" />
                            <h3 className="mt-4 font-display text-2xl font-bold">
                                Travelled with Coast Rider Tour?
                            </h3>
                            <p className="mx-auto mt-2 max-w-lg text-sm leading-6 text-muted-foreground">
                                Be among the first travellers to share an
                                experience with our community.
                            </p>
                        </div>
                    )}
                    <div className="mx-auto mt-7 flex max-w-3xl flex-col items-center justify-between gap-3 border-t border-border pt-6 text-center sm:flex-row sm:text-left">
                        <div>
                            <p className="font-display text-base font-bold text-foreground">
                                Have you travelled with us?
                            </p>
                            <p className="mt-0.5 text-xs text-muted-foreground">
                                Your feedback helps other travellers plan with
                                confidence.
                            </p>
                        </div>
                        <Link
                            href="/feedback"
                            className="inline-flex shrink-0 items-center gap-2 rounded-full border border-[var(--ocean)] px-5 py-2.5 text-sm font-bold text-[var(--ocean)] transition hover:bg-[var(--ocean)] hover:text-white"
                        >
                            Share your experience{" "}
                            <ArrowRight className="h-4 w-4" />
                        </Link>
                    </div>
                </section>

                {/* Travel gallery */}
                {galleryImages.length > 0 && (
                    <section id="gallery" className="scroll-mt-20 bg-[var(--sand)]/45">
                        <div className="mx-auto max-w-7xl px-4 py-16 sm:px-6 md:py-20 lg:px-8">
                            <div className="mx-auto mb-9 max-w-3xl text-center">
                                <p className="text-xs font-bold uppercase tracking-[0.22em] text-[var(--turquoise)]">Travel moments</p>
                                <h2 className="mt-3 font-display text-3xl font-bold tracking-tight text-foreground md:text-5xl">
                                    From Zanzibar & Tanzania
                                </h2>
                                <p className="mx-auto mt-4 max-w-2xl text-sm leading-7 text-muted-foreground md:text-base">
                                    A glimpse of island days, safari journeys and memorable experiences across Tanzania.
                                </p>
                            </div>
                            <HomeGallery images={galleryImages} instagramUrl={site.social.instagram} />
                            {site.social.instagram && site.social.instagram !== "#" && (
                                <div className="mt-9 text-center">
                                    <a href={site.social.instagram} target="_blank" rel="noopener noreferrer" className="inline-flex items-center gap-2 rounded-full bg-[var(--ocean)] px-6 py-3 text-sm font-bold text-white transition hover:brightness-110">
                                        <InstagramIcon className="h-4 w-4" /> Follow @coastridertour
                                    </a>
                                </div>
                            )}
                        </div>
                    </section>
                )}

                {/* Final CTA */}
                <section className="bg-white px-4 py-14 sm:px-6 md:py-16 lg:px-8">
                    <div className="mx-auto grid max-w-7xl overflow-hidden rounded-3xl bg-[var(--ocean)] text-white shadow-[var(--shadow-soft)] md:grid-cols-[minmax(0,1.15fr)_minmax(20rem,0.85fr)]">
                        <div className="flex flex-col justify-center px-6 py-10 sm:px-10 md:py-14 lg:px-14">
                            <span className="text-xs font-bold uppercase tracking-[0.2em] text-[var(--gold)]">Plan with a local expert</span>
                            <h2 className="mt-3 max-w-2xl font-display text-3xl font-bold leading-tight md:text-4xl">{content.cta_title}</h2>
                            <p className="mt-4 max-w-xl text-sm leading-7 text-white/75 md:text-base">{content.cta_description}</p>
                            <div className="mt-7 flex flex-col gap-3 sm:flex-row">
                                <Link
                                    href="/tour-finder"
                                    className="inline-flex items-center justify-center gap-2 rounded-full bg-[var(--gold)] px-6 py-3 text-sm font-bold text-foreground transition hover:brightness-95"
                                >
                                    Plan My Trip <ArrowRight className="h-4 w-4" />
                                </Link>
                            <a
                                href={getWhatsAppLink(undefined, site)}
                                target="_blank"
                                rel="noopener noreferrer"
                                    className="inline-flex items-center justify-center gap-2 rounded-full border border-white/30 px-6 py-3 text-sm font-bold text-white transition hover:bg-white hover:text-[var(--ocean)]"
                            >
                                    <MessageCircle className="h-4 w-4" /> WhatsApp Us
                            </a>
                            </div>
                        </div>
                        <div className="relative min-h-64 md:min-h-full">
                            <img src={site.ctaImagePath} alt="Coast Rider travel experience" className="absolute inset-0 h-full w-full object-cover" loading="lazy" />
                            <div className="absolute inset-0 bg-gradient-to-r from-[var(--ocean)]/35 to-transparent md:from-[var(--ocean)]/55" />
                        </div>
                    </div>
                </section>
            </main>
            <Footer />
            <WhatsAppFloatingButton />
        </div>
    );
}
