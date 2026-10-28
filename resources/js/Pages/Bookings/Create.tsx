import { FormEvent, useMemo, useState } from "react";
import { Head, Link, useForm } from "@inertiajs/react";
import { CalendarDays, Clock, MapPin, Minus, Plus, ShieldCheck } from "lucide-react";
import { Footer } from "@/Components/Footer";
import { Navbar } from "@/Components/Navbar";
import { calculateTourTotal, preferredBookingType } from "@/lib/tourPricing";
import type { TourPricing } from "@/types/tour";

type TourSummary = {
    id: number;
    slug: string;
    title: string;
    description: string;
    duration: string;
    location: string;
    price: number;
    currency: string;
    pricing: TourPricing;
    image: string;
};

type Props = {
    tour: TourSummary;
    selection: { bookingType: "shared" | "private" | null; adults: number; children: number; infants: number };
};

type BookingFormData = {
    tour_id: number;
    booking_type: "shared" | "private";
    full_name: string;
    email: string;
    whatsapp: string;
    preferred_contact: "whatsapp";
    travel_date: string;
    adults: number;
    children: number;
    children_ages: number[];
    pickup_zone: string;
    hotel: string;
    special_requests: string;
    consent: boolean;
    website: string;
};

const pickupZones = [
    ["south_east", "South & South-East Coast"],
    ["stone_town", "Stone Town / Airport"],
    ["north", "North Coast"],
    ["east", "East Coast"],
    ["other", "Other area"],
    ["not_sure", "Not sure yet"],
] as const;

export default function BookingCreate({ tour, selection }: Props) {
    const [childCount, setChildCount] = useState(selection.children);
    const [infantCount, setInfantCount] = useState(selection.infants);
    const { data, setData, post, processing, errors } = useForm<BookingFormData>({
        tour_id: tour.id,
        booking_type:
            selection.bookingType === "shared" && tour.pricing.sharedAvailable
                ? "shared"
                : selection.bookingType === "private" && tour.pricing.privateAvailable
                  ? "private"
                  : preferredBookingType(tour.pricing),
        full_name: "",
        email: "",
        whatsapp: "",
        preferred_contact: "whatsapp",
        travel_date: "",
        adults: selection.adults,
        children: selection.children + selection.infants,
        children_ages: [...Array(selection.children).fill(6), ...Array(selection.infants).fill(1)],
        pickup_zone: "not_sure",
        hotel: "",
        special_requests: "",
        consent: false,
        website: "",
    });
    const money = (amount: number) => new Intl.NumberFormat("en-US", { style: "currency", currency: tour.currency, maximumFractionDigits: 0 }).format(amount);
    const estimatedTotal = useMemo(
        () => calculateTourTotal(tour.pricing, data.adults, childCount, infantCount, data.booking_type),
        [tour.pricing, data.adults, childCount, infantCount, data.booking_type],
    );
    const isQuote = estimatedTotal === null;
    const totalGuests = data.adults + childCount + infantCount;
    const bookingStyleLabel = tour.pricing.bookingStyleLabel || (data.booking_type === "shared" ? "Shared tour" : "Private arrangement");
    const now = new Date();
    const today = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, "0")}-${String(now.getDate()).padStart(2, "0")}`;
    const errorMessages = Object.values(errors);

    function updateYoungGuests(children: number, infants: number) {
        setChildCount(children);
        setInfantCount(infants);
        setData({ ...data, children: children + infants, children_ages: [...Array(children).fill(6), ...Array(infants).fill(1)] });
    }

    function submit(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();
        post("/bookings", {
            preserveScroll: true,
            onError: () => window.setTimeout(() => document.getElementById("booking-errors")?.scrollIntoView({ behavior: "smooth", block: "center" }), 50),
        });
    }

    return (
        <div className="min-h-screen bg-[var(--sand)]/45">
            <Head title={`${isQuote ? "Request a quote" : "Book"} ${tour.title}`}>
                <meta name="description" content={`Send a simple booking request for ${tour.title} with Coast Rider Tour.`} />
            </Head>
            <Navbar />

            <main className="mx-auto max-w-6xl px-4 py-10 sm:px-6 md:py-16 lg:px-8">
                <Link href={`/tours/${tour.slug}`} className="text-sm font-semibold text-[var(--ocean)] hover:underline">← Back to tour details</Link>

                <div className="mt-6 grid items-start gap-8 lg:grid-cols-[minmax(0,1fr)_22rem]">
                    <section className="rounded-3xl border border-border bg-card p-6 shadow-[var(--shadow-card)] md:p-9">
                        <p className="text-xs font-bold uppercase tracking-[0.2em] text-[var(--turquoise)]">Simple booking</p>
                        <h1 className="mt-2 font-display text-3xl font-bold md:text-4xl">{isQuote ? "Request your quote" : "Book this tour"}</h1>
                        <p className="mt-3 max-w-2xl text-sm leading-6 text-muted-foreground">Complete this one short form. No payment is required—we confirm availability, pickup and the final price first.</p>

                        {errorMessages.length > 0 && (
                            <div id="booking-errors" role="alert" className="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
                                <p className="font-bold">Please correct the following:</p>
                                <ul className="mt-2 list-disc space-y-1 pl-5">{errorMessages.map((message, index) => <li key={`${message}-${index}`}>{message}</li>)}</ul>
                            </div>
                        )}

                        <form onSubmit={submit} className="mt-7 grid gap-6">
                            <div className="absolute -left-[10000px] h-px w-px overflow-hidden" aria-hidden="true">
                                <label>Website<input tabIndex={-1} autoComplete="off" value={data.website} onChange={(event) => setData("website", event.target.value)} /></label>
                            </div>

                            <div className="grid gap-4 sm:grid-cols-2">
                                <Field label="Tour date" error={errors.travel_date}>
                                    <input id="travel-date" type="date" min={today} value={data.travel_date} onChange={(event) => setData("travel_date", event.target.value)} required className={inputClass(errors.travel_date)} />
                                </Field>
                                <Field label="Hotel / pickup place" error={errors.hotel}>
                                    <input value={data.hotel} onChange={(event) => setData("hotel", event.target.value)} required className={inputClass(errors.hotel)} placeholder="Hotel name or location" />
                                </Field>
                                <Field label="Pickup area" error={errors.pickup_zone}>
                                    <select value={data.pickup_zone} onChange={(event) => setData("pickup_zone", event.target.value)} className={inputClass(errors.pickup_zone)}>
                                        {pickupZones.map(([value, label]) => <option key={value} value={value}>{label}</option>)}
                                    </select>
                                </Field>
                            </div>

                            <div>
                                <h2 className="font-display text-xl font-bold">Travellers</h2>
                                <div className="mt-3 grid gap-3 sm:grid-cols-3">
                                    <GuestCounter label="Adults" detail="Age 13+" value={data.adults} min={1} max={30} onChange={(value) => setData("adults", value)} />
                                    <GuestCounter label="Children" detail="Age 3–12" value={childCount} min={0} max={20} onChange={(value) => updateYoungGuests(value, infantCount)} />
                                    <GuestCounter label="Infants" detail="Age 0–2" value={infantCount} min={0} max={20} onChange={(value) => updateYoungGuests(childCount, value)} />
                                </div>
                            </div>

                            <div className={`rounded-2xl border p-5 ${isQuote ? "border-[var(--gold)]/50 bg-[var(--sand)]/60" : "border-[var(--turquoise)]/25 bg-[var(--turquoise)]/5"}`}>
                                <div className="flex items-center justify-between gap-4">
                                    <div><p className="text-xs font-bold uppercase tracking-wider text-muted-foreground">{isQuote ? "Your price" : "Estimated total"}</p><p className="mt-1 text-sm text-muted-foreground">{totalGuests} guest{totalGuests === 1 ? "" : "s"}</p></div>
                                    <strong className="text-right font-display text-2xl text-[var(--ocean)]">{isQuote ? "We’ll confirm it" : money(estimatedTotal)}</strong>
                                </div>
                                <p className="mt-3 border-t border-black/10 pt-3 text-xs leading-5 text-muted-foreground">{isQuote ? "We will send one complete price based on your group and pickup location." : "This estimate uses the published tour rates. Any pickup adjustment is confirmed before payment."}</p>
                            </div>

                            <div>
                                <h2 className="font-display text-xl font-bold">Your contact</h2>
                                <div className="mt-3 grid gap-4 sm:grid-cols-2">
                                    <Field label="Full name" error={errors.full_name}>
                                        <input value={data.full_name} onChange={(event) => setData("full_name", event.target.value)} autoComplete="name" required className={inputClass(errors.full_name)} placeholder="Your full name" />
                                    </Field>
                                    <Field label="WhatsApp number" error={errors.whatsapp}>
                                        <input value={data.whatsapp} onChange={(event) => setData("whatsapp", event.target.value)} autoComplete="tel" inputMode="tel" required className={inputClass(errors.whatsapp)} placeholder="+255 ..." />
                                    </Field>
                                    <Field label="Email (optional)" error={errors.email}>
                                        <input type="email" value={data.email} onChange={(event) => setData("email", event.target.value)} autoComplete="email" className={inputClass(errors.email)} placeholder="you@example.com" />
                                    </Field>
                                </div>
                            </div>

                            <Field label="Message (optional)" error={errors.special_requests}>
                                <textarea rows={3} value={data.special_requests} onChange={(event) => setData("special_requests", event.target.value)} className={inputClass(errors.special_requests)} placeholder="Dietary needs, accessibility or anything else" />
                            </Field>

                            <label className="flex items-start gap-3 rounded-xl bg-[var(--sand)]/65 p-4 text-xs leading-5 text-muted-foreground">
                                <input type="checkbox" checked={data.consent} onChange={(event) => setData("consent", event.target.checked)} required className="mt-0.5" />
                                <span>I agree to be contacted about this request and accept the <a href="/terms-and-conditions" target="_blank" className="font-bold text-[var(--ocean)] underline">Terms</a>, <a href="/booking-cancellation-policy" target="_blank" className="font-bold text-[var(--ocean)] underline">Cancellation Policy</a> and <a href="/privacy-policy" target="_blank" className="font-bold text-[var(--ocean)] underline">Privacy Policy</a>.</span>
                            </label>
                            {errors.consent && <span className="-mt-4 text-xs font-medium text-red-600">{errors.consent}</span>}

                            <button type="submit" disabled={processing} className="inline-flex min-h-13 items-center justify-center rounded-full bg-[var(--ocean)] px-6 py-3.5 font-bold text-white transition hover:brightness-110 disabled:cursor-not-allowed disabled:opacity-60">
                                {processing ? "Sending…" : isQuote ? "Send quote request" : "Send booking request"}
                            </button>
                            <p className="text-center text-xs text-muted-foreground">No account and no payment required.</p>
                        </form>
                    </section>

                    <aside className="overflow-hidden rounded-2xl border border-border bg-card shadow-[var(--shadow-card)] lg:sticky lg:top-28">
                        <img src={tour.image} alt={tour.title} className="aspect-[16/10] w-full object-cover" />
                        <div className="p-5">
                            <p className="text-xs font-bold uppercase tracking-widest text-[var(--turquoise)]">Selected tour</p>
                            <h2 className="mt-2 font-display text-2xl font-bold">{tour.title}</h2>
                            <div className="mt-4 space-y-3 border-y border-border py-4 text-sm">
                                <p className="flex items-center gap-2"><Clock className="h-4 w-4 text-[var(--turquoise)]" /> {tour.duration}</p>
                                <p className="flex items-center gap-2"><MapPin className="h-4 w-4 text-[var(--turquoise)]" /> {tour.location}</p>
                                <p className="flex items-center gap-2"><CalendarDays className="h-4 w-4 text-[var(--turquoise)]" /> {data.travel_date || "Choose your date"}</p>
                            </div>
                            <p className="mt-4 text-xs font-bold uppercase tracking-wider text-muted-foreground">{isQuote ? "Final price" : "Estimated total"}</p>
                            <p className="mt-1 font-display text-3xl font-bold text-[var(--ocean)]">{isQuote ? "Request quote" : money(estimatedTotal)}</p>
                            <p className="mt-2 text-xs leading-5 text-muted-foreground">{totalGuests} guest{totalGuests === 1 ? "" : "s"} · {bookingStyleLabel}</p>
                            <div className="mt-5 flex items-start gap-2 rounded-xl bg-[var(--turquoise)]/5 p-3 text-xs leading-5 text-muted-foreground"><ShieldCheck className="h-4 w-4 shrink-0 text-[var(--turquoise)]" /> Availability and the final total are confirmed before payment.</div>
                        </div>
                    </aside>
                </div>
            </main>
            <Footer />
        </div>
    );
}

function GuestCounter({ label, detail, value, min, max, onChange }: { label: string; detail: string; value: number; min: number; max: number; onChange: (value: number) => void }) {
    return <div className="rounded-xl border border-input bg-background p-3"><div className="flex items-center justify-between gap-2"><div><p className="text-sm font-bold">{label}</p><p className="text-[11px] text-muted-foreground">{detail}</p></div><div className="flex items-center gap-2"><button type="button" onClick={() => onChange(Math.max(min, value - 1))} disabled={value <= min} aria-label={`Remove one ${label.toLowerCase()}`} className="grid h-8 w-8 place-items-center rounded-full border border-border text-[var(--ocean)] disabled:opacity-30"><Minus className="h-3.5 w-3.5" /></button><output className="w-5 text-center text-sm font-bold">{value}</output><button type="button" onClick={() => onChange(Math.min(max, value + 1))} disabled={value >= max} aria-label={`Add one ${label.toLowerCase()}`} className="grid h-8 w-8 place-items-center rounded-full bg-[var(--ocean)] text-white disabled:opacity-30"><Plus className="h-3.5 w-3.5" /></button></div></div></div>;
}

function Field({ label, error, children }: { label: string; error?: string; children: React.ReactNode }) {
    return <label className="grid gap-1.5 text-sm font-semibold"><span>{label}</span>{children}{error && <span className="text-xs font-medium text-red-600">{error}</span>}</label>;
}

function inputClass(error?: string) {
    return `min-h-12 w-full rounded-xl border bg-background px-3 py-2.5 text-sm outline-none transition focus:ring-2 focus:ring-[var(--turquoise)]/20 ${error ? "border-red-500" : "border-input"}`;
}
