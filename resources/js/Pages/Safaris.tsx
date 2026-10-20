import { FormEvent, useEffect, useState } from "react";
import { Head, Link, useForm } from "@inertiajs/react";
import {
    ArrowRight,
    CalendarDays,
    Check,
    MapPin,
    MessageCircle,
    Minus,
    Plus,
    ShieldCheck,
    Sparkles,
} from "lucide-react";
import { Navbar } from "@/Components/Navbar";
import { Footer } from "@/Components/Footer";
import { getWhatsAppLink, useSiteConfig } from "@/config/site";

type SafariIdea = {
    slug: string;
    title: string;
    description: string;
    duration: string;
    image: string;
    location: string;
    price: number;
    currency: string;
    highlights: string[];
};
type Props = {
    pageTitle: string;
    pageDescription: string;
    ideas: SafariIdea[];
};
type FormData = {
    name: string;
    email: string;
    whatsapp: string;
    preferred_contact: "whatsapp" | "email";
    start_date: string;
    days: number;
    adults: number;
    children: number;
    parks: string[];
    accommodation: string;
    arrival_point: string;
    budget: string;
    notes: string;
    consent: boolean;
    website: string;
};
const parks = [
    "Mikumi",
    "Nyerere",
    "Serengeti",
    "Ngorongoro",
    "Tarangire",
    "Lake Manyara",
    "Not sure",
];
export default function Safaris({ pageTitle, pageDescription, ideas }: Props) {
    const site = useSiteConfig();
    const [loadHeroVideo, setLoadHeroVideo] = useState(false);
    useEffect(() => {
        const connection = (
            navigator as Navigator & {
                connection?: { saveData?: boolean; effectiveType?: string };
            }
        ).connection;
        if (connection?.saveData || connection?.effectiveType?.includes("2g"))
            return;
        const timer = window.setTimeout(() => setLoadHeroVideo(true), 1200);
        return () => window.clearTimeout(timer);
    }, []);
    const now = new Date();
    const today = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, "0")}-${String(now.getDate()).padStart(2, "0")}`;
    const { data, setData, post, processing, errors, recentlySuccessful } =
        useForm<FormData>({
            name: "",
            email: "",
            whatsapp: "",
            preferred_contact: "whatsapp",
            start_date: "",
            days: 3,
            adults: 2,
            children: 0,
            parks: [],
            accommodation: "Mid-range",
            arrival_point: "Zanzibar",
            budget: "",
            notes: "",
            consent: false,
            website: "",
        });
    useEffect(() => {
        try {
            const saved = localStorage.getItem("coast-rider-safari-draft");
            if (saved)
                setData({
                    ...data,
                    ...JSON.parse(saved),
                    consent: false,
                    website: "",
                });
        } catch {}
    }, []);
    useEffect(() => {
        if (!recentlySuccessful)
            localStorage.setItem(
                "coast-rider-safari-draft",
                JSON.stringify({ ...data, consent: false, website: "" }),
            );
        else localStorage.removeItem("coast-rider-safari-draft");
    }, [data, recentlySuccessful]);
    const submit = (e: FormEvent) => {
        e.preventDefault();
        post("/safari-requests", {
            preserveScroll: true,
            onError: () =>
                window.setTimeout(
                    () =>
                        document
                            .getElementById("safari-errors")
                            ?.scrollIntoView({
                                behavior: "smooth",
                                block: "center",
                            }),
                    50,
                ),
        });
    };
    const togglePark = (park: string) =>
        setData(
            "parks",
            data.parks.includes(park)
                ? data.parks.filter((x) => x !== park)
                : [...data.parks, park],
        );
    return (
        <div className="min-h-screen bg-background">
            <Head title={pageTitle}>
                <meta name="description" content={pageDescription} />
            </Head>
            <Navbar />
            <main>
                <section className="relative overflow-hidden bg-[oklch(0.20_0.04_240)] text-white">
                    <img
                        src="/images/experiences/safari-ngorongoro.webp"
                        alt=""
                        aria-hidden="true"
                        className="absolute inset-0 h-full w-full object-cover"
                    />
                    <video
                        src={
                            loadHeroVideo
                                ? "/videos/ngorongoro-elephants-hero.webm"
                                : undefined
                        }
                        autoPlay
                        muted
                        loop
                        playsInline
                        preload="none"
                        poster="/images/experiences/safari-ngorongoro.webp"
                        aria-hidden="true"
                        className="absolute inset-0 h-full w-full object-cover motion-reduce:hidden"
                    />
                    <div className="absolute inset-0 bg-gradient-to-r from-[#071d2b]/95 via-[#071d2b]/78 to-[#071d2b]/55" />
                    <div className="absolute inset-0 bg-gradient-to-t from-[#071d2b]/55 via-transparent to-black/15" />
                    <div className="absolute -right-20 -top-20 h-96 w-96 rounded-full border border-white/10" />
                    <div className="absolute bottom-0 left-1/3 h-64 w-64 rounded-full bg-[var(--turquoise)]/10 blur-3xl" />
                    <div className="relative mx-auto grid max-w-7xl items-center gap-10 px-4 py-16 sm:px-6 md:py-20 lg:grid-cols-[1fr_25rem] lg:px-8">
                        <div>
                            <p className="text-xs font-bold uppercase tracking-[.22em] text-[var(--gold)]">
                                Mainland Tanzania
                            </p>
                            <h1 className="mt-4 max-w-4xl font-display text-4xl font-extrabold leading-tight sm:text-5xl md:text-6xl">
                                {pageTitle}
                            </h1>
                            <p className="mt-4 line-clamp-3 max-w-xl text-sm leading-6 text-white/80 sm:line-clamp-none md:text-lg md:leading-7">
                                {pageDescription}
                            </p>
                            <div className="mt-8 flex flex-wrap gap-3">
                                <a
                                    href="#plan-safari"
                                    className="rounded-full bg-[var(--gold)] px-6 py-3.5 font-bold text-foreground"
                                >
                                    Plan my safari
                                </a>
                                <a
                                    href={getWhatsAppLink(
                                        "Hello Coast Rider Tour, I would like help planning a Tanzania safari.",
                                        site,
                                    )}
                                    target="_blank"
                                    rel="noreferrer"
                                    className="hidden items-center gap-2 rounded-full border border-white/25 bg-white/10 px-6 py-3.5 font-bold sm:inline-flex"
                                >
                                    <MessageCircle className="h-4 w-4" /> Ask on
                                    WhatsApp
                                </a>
                            </div>
                        </div>
                        <div className="hidden gap-3 rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur sm:grid">
                            <p className="text-xs font-bold uppercase tracking-widest text-[var(--gold)]">
                                Built around your trip
                            </p>
                            {[
                                "Private safari planning",
                                "Park and route recommendations",
                                "Accommodation choices",
                                "Transfers coordinated",
                            ].map((x) => (
                                <p
                                    key={x}
                                    className="flex items-center gap-3 rounded-xl bg-white/5 p-3 text-sm font-semibold"
                                >
                                    <Check className="h-4 w-4 text-[var(--gold)]" />
                                    {x}
                                </p>
                            ))}
                        </div>
                    </div>
                </section>
                <section className="mx-auto max-w-7xl px-4 py-14 sm:px-6 md:py-20 lg:px-8">
                    <div className="max-w-2xl">
                        <p className="text-xs font-bold uppercase tracking-[.2em] text-[var(--turquoise)]">
                            Safari ideas
                        </p>
                        <h2 className="mt-2 font-display text-3xl font-bold md:text-4xl">
                            Start with an idea, then make it yours
                        </h2>
                        <p className="mt-3 text-sm leading-6 text-muted-foreground">
                            These are planning examples. Duration, parks and
                            accommodation are tailored before we send your
                            quote.
                        </p>
                    </div>
                    <div className="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        {ideas.map((idea) => (
                            <article
                                key={idea.title}
                                className="group flex flex-col overflow-hidden rounded-2xl border border-border bg-card shadow-[var(--shadow-card)] transition hover:-translate-y-1 hover:shadow-[var(--shadow-soft)]"
                            >
                                <Link
                                    href={`/tours/${idea.slug}`}
                                    aria-label={`View ${idea.title} details`}
                                    className="relative block aspect-[16/10] overflow-hidden bg-muted"
                                >
                                    <img
                                        src={idea.image}
                                        alt={`${idea.title} in ${idea.location}`}
                                        loading="lazy"
                                        className="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                    />
                                    <div className="absolute inset-0 bg-gradient-to-t from-[#10182d]/70 via-transparent to-black/10" />
                                    <span className="absolute left-4 top-4 rounded-full bg-white/95 px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-[var(--ocean)] shadow-sm">
                                        {idea.duration}
                                    </span>
                                    <span className="absolute bottom-4 left-4 inline-flex items-center gap-1.5 text-xs font-semibold text-white">
                                        <MapPin className="h-3.5 w-3.5" />
                                        {idea.location}
                                    </span>
                                </Link>
                                <div className="flex flex-1 flex-col p-5">
                                    <h3 className="font-display text-xl font-bold">
                                        <Link
                                            href={`/tours/${idea.slug}`}
                                            className="transition hover:text-[var(--ocean)]"
                                        >
                                            {idea.title}
                                        </Link>
                                    </h3>
                                    <p className="mt-2 text-sm leading-6 text-muted-foreground">
                                        {idea.description}
                                    </p>
                                    <div className="mt-4 flex flex-wrap gap-2">
                                        {idea.highlights.map((highlight) => (
                                            <span
                                                key={highlight}
                                                className="rounded-full bg-[var(--sand)] px-3 py-1 text-[11px] font-semibold text-foreground/70"
                                            >
                                                {highlight}
                                            </span>
                                        ))}
                                    </div>
                                    <div className="mt-5 border-t border-border pt-4">
                                        <p className="text-[10px] font-bold uppercase tracking-wider text-muted-foreground">
                                            Price
                                        </p>
                                        <p className="font-display text-lg font-bold text-[var(--ocean)]">
                                            {idea.price > 0
                                                ? `${idea.currency} ${new Intl.NumberFormat("en-US", { maximumFractionDigits: 0 }).format(idea.price)} / adult`
                                                : "Custom quote required"}
                                        </p>
                                        <Link
                                            href={`/tours/${idea.slug}`}
                                            className="mt-2 inline-flex items-center gap-1 text-sm font-bold text-[var(--ocean)]"
                                        >
                                            View safari details{" "}
                                            <ArrowRight className="h-4 w-4" />
                                        </Link>
                                    </div>
                                </div>
                            </article>
                        ))}
                    </div>
                </section>
                <section className="bg-[var(--sand)]/55">
                    <div className="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 md:py-20 lg:grid-cols-2 lg:px-8">
                        <div>
                            <p className="text-xs font-bold uppercase tracking-[.2em] text-[var(--turquoise)]">
                                Simple planning
                            </p>
                            <h2 className="mt-2 font-display text-3xl font-bold">
                                How it works
                            </h2>
                            <div className="mt-6 grid gap-4">
                                {[
                                    [
                                        "1",
                                        "Tell us your plans",
                                        "Share dates, group size, parks and comfort level.",
                                    ],
                                    [
                                        "2",
                                        "We shape the route",
                                        "Our team checks logistics, availability and the best sequence.",
                                    ],
                                    [
                                        "3",
                                        "Receive your quote",
                                        "Review the itinerary and confirmed inclusions before deciding.",
                                    ],
                                ].map(([n, t, d]) => (
                                    <div
                                        key={n}
                                        className="flex gap-4 rounded-2xl bg-white p-5"
                                    >
                                        <span className="grid h-10 w-10 shrink-0 place-items-center rounded-full bg-[var(--ocean)] font-bold text-white">
                                            {n}
                                        </span>
                                        <div>
                                            <h3 className="font-bold">{t}</h3>
                                            <p className="mt-1 text-sm leading-6 text-muted-foreground">
                                                {d}
                                            </p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                        <div>
                            <p className="text-xs font-bold uppercase tracking-[.2em] text-[var(--turquoise)]">
                                Travel style
                            </p>
                            <h2 className="mt-2 font-display text-3xl font-bold">
                                Choose your comfort level
                            </h2>
                            <div className="mt-6 grid gap-4">
                                {[
                                    [
                                        "Budget",
                                        "Simple, clean stays and practical routing.",
                                    ],
                                    [
                                        "Mid-range",
                                        "Comfortable lodges or camps with balanced value.",
                                    ],
                                    [
                                        "Luxury",
                                        "Premium camps, lodges and highly personalized arrangements.",
                                    ],
                                ].map(([t, d]) => (
                                    <div
                                        key={t}
                                        className="rounded-2xl border border-border bg-white p-5"
                                    >
                                        <h3 className="font-bold">{t}</h3>
                                        <p className="mt-1 text-sm text-muted-foreground">
                                            {d}
                                        </p>
                                    </div>
                                ))}
                            </div>
                            <p className="mt-5 flex gap-2 text-xs leading-5 text-muted-foreground">
                                <ShieldCheck className="h-4 w-4 shrink-0 text-[var(--turquoise)]" />
                                Exact inclusions, park fees, transport and
                                accommodation are listed clearly in your
                                personal quote.
                            </p>
                        </div>
                    </div>
                </section>
                <section id="plan-safari" className="scroll-mt-24">
                    <div className="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 md:py-20 lg:grid-cols-[.75fr_1.25fr] lg:px-8">
                        <div>
                            <p className="text-xs font-bold uppercase tracking-[.2em] text-[var(--turquoise)]">
                                Custom request
                            </p>
                            <h2 className="mt-2 font-display text-3xl font-bold md:text-4xl">
                                Plan your safari
                            </h2>
                            <p className="mt-4 text-sm leading-7 text-muted-foreground">
                                Send the essentials. No payment is required, and
                                this request does not confirm a booking.
                            </p>
                            <div className="mt-6 space-y-3 text-sm">
                                {[
                                    "A route matched to your dates",
                                    "Clear quote before confirmation",
                                    "Direct support from our local team",
                                ].map((x) => (
                                    <p key={x} className="flex gap-2">
                                        <Check className="h-5 w-5 text-[var(--turquoise)]" />
                                        {x}
                                    </p>
                                ))}
                            </div>
                        </div>
                        <form
                            onSubmit={submit}
                            className="grid gap-5 rounded-3xl border border-border bg-card p-6 shadow-[var(--shadow-card)] md:p-8"
                        >
                            <div className="absolute -left-[9999px]">
                                <input
                                    type="hidden"
                                    tabIndex={-1}
                                    value={data.website}
                                    onChange={(e) =>
                                        setData("website", e.target.value)
                                    }
                                />
                            </div>
                            {recentlySuccessful && (
                                <div className="rounded-xl bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">
                                    Safari request received. Our team will
                                    contact you shortly.
                                </div>
                            )}
                            {Object.keys(errors).length > 0 && (
                                <div
                                    id="safari-errors"
                                    role="alert"
                                    className="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800"
                                >
                                    <strong>
                                        Please check the highlighted fields.
                                    </strong>
                                    <ul className="mt-2 list-disc pl-5">
                                        {Object.values(errors).map(
                                            (message, index) => (
                                                <li key={index}>{message}</li>
                                            ),
                                        )}
                                    </ul>
                                </div>
                            )}
                            <div className="grid gap-5 sm:grid-cols-2">
                                <Field label="Full name" error={errors.name}>
                                    <input
                                        required
                                        autoComplete="name"
                                        value={data.name}
                                        onChange={(e) =>
                                            setData("name", e.target.value)
                                        }
                                        className={input(errors.name)}
                                    />
                                </Field>
                                <Field
                                    label="Preferred response"
                                    error={errors.preferred_contact}
                                >
                                    <select
                                        value={data.preferred_contact}
                                        onChange={(e) =>
                                            setData(
                                                "preferred_contact",
                                                e.target
                                                    .value as FormData["preferred_contact"],
                                            )
                                        }
                                        className={input(
                                            errors.preferred_contact,
                                        )}
                                    >
                                        <option value="whatsapp">
                                            WhatsApp
                                        </option>
                                        <option value="email">Email</option>
                                    </select>
                                </Field>
                                <Field
                                    label={`WhatsApp (${data.preferred_contact === "whatsapp" ? "required" : "optional"})`}
                                    error={errors.whatsapp}
                                >
                                    <input
                                        required={
                                            data.preferred_contact ===
                                            "whatsapp"
                                        }
                                        autoComplete="tel"
                                        inputMode="tel"
                                        value={data.whatsapp}
                                        onChange={(e) =>
                                            setData("whatsapp", e.target.value)
                                        }
                                        placeholder="+255 ..."
                                        className={input(errors.whatsapp)}
                                    />
                                </Field>
                                <Field
                                    label={`Email (${data.preferred_contact === "email" ? "required" : "optional"})`}
                                    error={errors.email}
                                >
                                    <input
                                        required={
                                            data.preferred_contact === "email"
                                        }
                                        type="email"
                                        autoComplete="email"
                                        value={data.email}
                                        onChange={(e) =>
                                            setData("email", e.target.value)
                                        }
                                        className={input(errors.email)}
                                    />
                                </Field>
                                <Field
                                    label="Preferred start date"
                                    error={errors.start_date}
                                >
                                    <input
                                        required
                                        type="date"
                                        min={today}
                                        value={data.start_date}
                                        onChange={(e) =>
                                            setData(
                                                "start_date",
                                                e.target.value,
                                            )
                                        }
                                        className={input(errors.start_date)}
                                    />
                                </Field>
                            </div>
                            <div className="grid gap-4 sm:grid-cols-3">
                                <Counter
                                    label="Days"
                                    value={data.days}
                                    min={1}
                                    max={30}
                                    set={(v) => setData("days", v)}
                                />
                                <Counter
                                    label="Adults"
                                    value={data.adults}
                                    min={1}
                                    max={30}
                                    set={(v) => setData("adults", v)}
                                />
                                <Counter
                                    label="Children"
                                    value={data.children}
                                    min={0}
                                    max={20}
                                    set={(v) => setData("children", v)}
                                />
                            </div>
                            <Field
                                label="Parks you are interested in"
                                error={errors.parks}
                            >
                                <div className="flex flex-wrap gap-2">
                                    {parks.map((park) => (
                                        <button
                                            type="button"
                                            aria-pressed={data.parks.includes(
                                                park,
                                            )}
                                            key={park}
                                            onClick={() => togglePark(park)}
                                            className={`rounded-full px-4 py-2 text-xs font-bold ${data.parks.includes(park) ? "bg-[var(--ocean)] text-white" : "border border-border"}`}
                                        >
                                            {park}
                                        </button>
                                    ))}
                                </div>
                            </Field>
                            <div className="grid gap-5 sm:grid-cols-2">
                                <Field
                                    label="Accommodation"
                                    error={errors.accommodation}
                                >
                                    <select
                                        value={data.accommodation}
                                        onChange={(e) =>
                                            setData(
                                                "accommodation",
                                                e.target.value,
                                            )
                                        }
                                        className={input(errors.accommodation)}
                                    >
                                        {[
                                            "Budget",
                                            "Mid-range",
                                            "Luxury",
                                            "Not sure",
                                        ].map((x) => (
                                            <option key={x}>{x}</option>
                                        ))}
                                    </select>
                                </Field>
                                <Field
                                    label="Arrival / start point"
                                    error={errors.arrival_point}
                                >
                                    <select
                                        value={data.arrival_point}
                                        onChange={(e) =>
                                            setData(
                                                "arrival_point",
                                                e.target.value,
                                            )
                                        }
                                        className={input(errors.arrival_point)}
                                    >
                                        {[
                                            "Zanzibar",
                                            "Dar es Salaam",
                                            "Arusha",
                                            "Other",
                                            "Not sure",
                                        ].map((x) => (
                                            <option key={x}>{x}</option>
                                        ))}
                                    </select>
                                </Field>
                            </div>
                            <Field
                                label="Approximate budget (optional)"
                                error={errors.budget}
                            >
                                <input
                                    value={data.budget}
                                    onChange={(e) =>
                                        setData("budget", e.target.value)
                                    }
                                    placeholder="Example: USD 1,500 per person"
                                    className={input(errors.budget)}
                                />
                            </Field>
                            <Field
                                label="Notes or special interests (optional)"
                                error={errors.notes}
                            >
                                <textarea
                                    rows={4}
                                    value={data.notes}
                                    onChange={(e) =>
                                        setData("notes", e.target.value)
                                    }
                                    placeholder="Flights, preferred parks, room setup, mobility or special occasions"
                                    className={input(errors.notes)}
                                />
                            </Field>
                            <label className="flex items-start gap-3 rounded-xl bg-[var(--sand)]/65 p-4 text-xs leading-5 text-muted-foreground">
                                <input
                                    required
                                    type="checkbox"
                                    checked={data.consent}
                                    onChange={(e) =>
                                        setData("consent", e.target.checked)
                                    }
                                    className="mt-1"
                                />
                                <span>
                                    I accept the{" "}
                                    <a
                                        href="/terms-and-conditions"
                                        target="_blank"
                                        className="font-bold text-[var(--ocean)] underline"
                                    >
                                        Terms
                                    </a>
                                    ,{" "}
                                    <a
                                        href="/booking-cancellation-policy"
                                        target="_blank"
                                        className="font-bold text-[var(--ocean)] underline"
                                    >
                                        Cancellation Policy
                                    </a>{" "}
                                    and{" "}
                                    <a
                                        href="/privacy-policy"
                                        target="_blank"
                                        className="font-bold text-[var(--ocean)] underline"
                                    >
                                        Privacy Policy
                                    </a>
                                    .
                                </span>
                            </label>
                            {errors.consent && (
                                <span className="text-xs text-red-600">
                                    {errors.consent}
                                </span>
                            )}
                            <button
                                disabled={processing}
                                className="min-h-12 rounded-full bg-[var(--ocean)] px-6 font-bold text-white disabled:opacity-60"
                            >
                                {processing
                                    ? "Sending request…"
                                    : "Request my safari plan"}
                            </button>
                            <p className="text-center text-xs text-muted-foreground">
                                No payment required · Availability and price
                                confirmed personally
                            </p>
                        </form>
                    </div>
                </section>
            </main>
            <Footer />
        </div>
    );
}
function Field({
    label,
    error,
    children,
}: {
    label: string;
    error?: string;
    children: React.ReactNode;
}) {
    return (
        <label className="grid gap-1.5">
            <span className="text-xs font-bold uppercase tracking-wider text-foreground/70">
                {label}
            </span>
            {children}
            {error && <span className="text-xs text-red-600">{error}</span>}
        </label>
    );
}
function Counter({
    label,
    value,
    min,
    max,
    set,
}: {
    label: string;
    value: number;
    min: number;
    max: number;
    set: (v: number) => void;
}) {
    return (
        <div>
            <p className="mb-1.5 text-xs font-bold uppercase tracking-wider text-foreground/70">
                {label}
            </p>
            <div className="flex min-h-12 items-center justify-between rounded-xl border border-input px-2">
                <button
                    type="button"
                    onClick={() => set(Math.max(min, value - 1))}
                    disabled={value <= min}
                    className="grid h-8 w-8 place-items-center rounded-full border disabled:opacity-30"
                >
                    <Minus className="h-3.5 w-3.5" />
                </button>
                <strong>{value}</strong>
                <button
                    type="button"
                    onClick={() => set(Math.min(max, value + 1))}
                    disabled={value >= max}
                    className="grid h-8 w-8 place-items-center rounded-full border disabled:opacity-30"
                >
                    <Plus className="h-3.5 w-3.5" />
                </button>
            </div>
        </div>
    );
}
function input(error?: string) {
    return `w-full rounded-xl border bg-background px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[var(--turquoise)]/20 ${error ? "border-red-500" : "border-input focus:border-[var(--turquoise)]"}`;
}
