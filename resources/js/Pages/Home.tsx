import { Head, Link } from "@inertiajs/react";
import { Navbar } from "@/Components/Navbar";
import { HeroSection, type HeroSlideData } from "@/Components/HeroSection";
import { SectionHeading } from "@/Components/SectionHeading";
import { PopularToursCarousel } from "@/Components/PopularToursCarousel";
import { CategoryCard } from "@/Components/CategoryCard";
import {
    TestimonialSlider,
    type PublicTestimonial,
} from "@/Components/TestimonialSlider";
import { HomeGallery } from "@/Components/HomeGallery";
import { Footer } from "@/Components/Footer";
import { WhatsAppFloatingButton } from "@/Components/WhatsAppFloatingButton";
import { getWhatsAppLink, useSiteConfig } from "@/config/site";
import type { TourCardData, TourCategoryData } from "@/types/tour";
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

type HomeItem = {
    title: string;
    description: string | null;
    meta: string | null;
    icon: keyof typeof icons | null;
};

type HomePageProps = {
    tours: TourCardData[];
    categories: TourCategoryData[];
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
    categories,
    featuredTour,
    featuredHighlights,
    homeItems,
    galleryImages,
    heroSlides,
    testimonials,
    content,
}: HomePageProps) {
    const site = useSiteConfig();
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
                    <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                        {(homeItems.trust ?? []).map((item) => {
                            const Icon =
                                icons[item.icon ?? "sparkles"] ?? Sparkles;
                            return (
                                <div
                                    key={item.title}
                                    className="flex items-center gap-3"
                                >
                                    <div className="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-[var(--turquoise)]/15 text-[var(--ocean)]">
                                        <Icon className="h-5 w-5" />
                                    </div>
                                    <span className="text-sm font-semibold text-foreground">
                                        {item.title}
                                    </span>
                                </div>
                            );
                        })}
                    </div>
                </section>

                <section className="mx-auto max-w-7xl px-4 pt-10 sm:px-6 lg:px-8">
                    <div className="flex flex-col items-start justify-between gap-5 overflow-hidden rounded-3xl bg-[var(--ocean)] px-6 py-7 text-white shadow-[var(--shadow-soft)] sm:flex-row sm:items-center md:px-9">
                        <div className="flex items-start gap-4">
                            <span className="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-[var(--gold)] text-[var(--ocean)]">
                                <Sparkles className="h-6 w-6" />
                            </span>
                            <div>
                                <p className="text-xs font-bold uppercase tracking-[.18em] text-[var(--gold)]">
                                    Not sure where to start?
                                </p>
                                <h2 className="mt-1 font-display text-2xl font-bold">
                                    Find your ideal experience in under a minute
                                </h2>
                                <p className="mt-1 max-w-2xl text-sm text-white/65">
                                    Answer four simple questions and see tours
                                    and packages matched to your plans.
                                </p>
                            </div>
                        </div>
                        <Link
                            href="/tour-finder"
                            className="inline-flex shrink-0 items-center gap-2 rounded-full bg-[var(--gold)] px-6 py-3 text-sm font-bold text-foreground"
                        >
                            Start Tour Finder <ArrowRight className="h-4 w-4" />
                        </Link>
                    </div>
                </section>

                {/* Popular Tours */}
                <section
                    id="tours"
                    className="mx-auto max-w-7xl scroll-mt-20 px-4 pb-8 pt-16 sm:px-6 md:pb-10 md:pt-20 lg:px-8"
                >
                    <SectionHeading
                        eyebrow={content.popular_eyebrow}
                        title={content.popular_title}
                        subtitle={content.popular_description}
                    />
                    <PopularToursCarousel tours={tours} />
                    <div className="mt-10 text-center">
                        <a
                            href="/tours"
                            className="inline-flex items-center gap-2 rounded-full border border-[var(--ocean)] px-6 py-3 text-sm font-bold text-[var(--ocean)] transition hover:bg-[var(--ocean)] hover:text-white"
                        >
                            View all tours <ArrowRight className="h-4 w-4" />
                        </a>
                    </div>
                </section>

                {/* Categories */}
                <section className="bg-[var(--sand)]/40">
                    <div className="mx-auto max-w-7xl px-4 py-14 sm:px-6 md:py-18 lg:px-8">
                        <SectionHeading
                            eyebrow="Categories"
                            title={content.categories_title}
                            subtitle="Whatever you dream of, we have a tour for it."
                        />
                        <div className="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 lg:gap-6">
                            {categories.map((category) => (
                                <CategoryCard
                                    key={category.slug}
                                    {...category}
                                />
                            ))}
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
                    className="mx-auto max-w-7xl scroll-mt-20 px-4 py-20 sm:px-6 lg:px-8"
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
                    <div className="mx-auto mt-8 flex max-w-3xl flex-col items-center justify-between gap-4 rounded-2xl bg-[var(--ocean)] px-5 py-4 text-center text-white sm:flex-row sm:text-left">
                        <div>
                            <p className="font-display text-lg font-bold">
                                Have you travelled with us?
                            </p>
                            <p className="mt-0.5 text-xs text-white/65">
                                Your feedback helps other travellers plan with
                                confidence.
                            </p>
                        </div>
                        <Link
                            href="/feedback"
                            className="inline-flex shrink-0 items-center gap-2 rounded-full bg-[var(--gold)] px-5 py-2.5 text-sm font-bold text-foreground"
                        >
                            Share your experience{" "}
                            <ArrowRight className="h-4 w-4" />
                        </Link>
                    </div>
                </section>

                {/* Gallery Preview */}
                <section
                    id="gallery"
                    className="scroll-mt-20 bg-[var(--sand)]/40"
                >
                    <div className="mx-auto max-w-7xl px-4 py-14 sm:px-6 md:py-16 lg:px-8">
                        <SectionHeading
                            eyebrow="Gallery"
                            title={content.gallery_title}
                        />
                        <HomeGallery images={galleryImages} />
                    </div>
                </section>

                {/* Final CTA */}
                <section className="relative overflow-hidden">
                    <img
                        src={site.ctaImagePath}
                        alt=""
                        aria-hidden
                        className="absolute inset-0 h-full w-full object-cover"
                        loading="lazy"
                    />
                    <div className="absolute inset-0 bg-[oklch(0.20_0.04_240/0.8)]" />
                    <div className="relative mx-auto max-w-4xl px-4 py-24 text-center text-white">
                        <span className="text-xs font-bold uppercase tracking-[0.2em] text-[var(--gold)]">
                            Plan with our local team
                        </span>
                        <h2 className="mt-3 font-display text-4xl font-bold md:text-5xl">
                            {content.cta_title}
                        </h2>
                        <p className="mt-4 text-lg text-white/85">
                            {content.cta_description}
                        </p>
                        <div className="mt-8 flex flex-wrap justify-center gap-3">
                            <a
                                href="/tours"
                                className="inline-flex items-center gap-2 rounded-full bg-[var(--gold)] text-foreground px-7 py-3.5 font-bold hover:brightness-95 transition"
                            >
                                Explore Tours
                            </a>
                            <a
                                href={getWhatsAppLink(undefined, site)}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="inline-flex items-center gap-2 rounded-full bg-[#25D366] text-white px-7 py-3.5 font-bold hover:opacity-95 transition"
                            >
                                <MessageCircle className="h-4 w-4" /> Chat on
                                WhatsApp
                            </a>
                        </div>
                    </div>
                </section>
            </main>
            <Footer />
            <WhatsAppFloatingButton />
        </div>
    );
}
