import { FormEvent, useEffect, useMemo, useState } from "react";
import { Head, Link, useForm } from "@inertiajs/react";
import {
    CalendarDays,
    Check,
    Clock,
    MapPin,
    Minus,
    Plus,
    ShieldCheck,
} from "lucide-react";
import { Footer } from "@/Components/Footer";
import { Navbar } from "@/Components/Navbar";
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
};

type BookingFormData = {
    tour_id: number;
    booking_type: "shared" | "private";
    full_name: string;
    email: string;
    whatsapp: string;
    preferred_contact: "whatsapp" | "phone" | "email";
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

export default function BookingCreate({ tour }: Props) {
    const [step, setStep] = useState<1 | 2>(1);
    const { data, setData, post, processing, errors } =
        useForm<BookingFormData>({
            tour_id: tour.id,
            booking_type: tour.pricing.sharedAvailable ? "shared" : "private",
            full_name: "",
            email: "",
            whatsapp: "",
            preferred_contact: "whatsapp",
            travel_date: "",
            adults: 2,
            children: 0,
            children_ages: [],
            pickup_zone: "not_sure",
            hotel: "",
            special_requests: "",
            consent: false,
            website: "",
        });
    const price = new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: tour.currency,
        maximumFractionDigits: 0,
    }).format(tour.price);
    const priceLabel = tour.price > 0 ? price : "Custom quote required";
    const money = (amount: number) =>
        new Intl.NumberFormat("en-US", {
            style: "currency",
            currency: tour.currency,
            maximumFractionDigits: 0,
        }).format(amount);
    const availableStyles = (["shared", "private"] as const).filter((type) =>
        type === "shared"
            ? tour.pricing.sharedAvailable
            : tour.pricing.privateAvailable,
    );
    const totalGuests = data.adults + data.children;
    const selectedPrivateTier = tour.pricing.privateTiers.find(
        (tier) => totalGuests >= tier.min && totalGuests <= tier.max,
    );
    const estimatedTotal = useMemo(() => {
        if (data.booking_type === "shared") {
            if (tour.pricing.sharedAdultPrice === null) return null;
            const infants = data.children_ages.filter((age) => age <= 2).length;
            const children = data.children - infants;
            if (children > 0 && tour.pricing.sharedChildPrice === null)
                return null;
            if (infants > 0 && tour.pricing.sharedInfantPrice === null)
                return null;
            return (
                tour.pricing.sharedAdultPrice * data.adults +
                (tour.pricing.sharedChildPrice ?? 0) * children +
                (tour.pricing.sharedInfantPrice ?? 0) * infants
            );
        }
        const guests = data.adults + data.children;
        const tier = tour.pricing.privateTiers.find(
            (item) => guests >= item.min && guests <= item.max,
        );
        if (!tier || tier.price === null) return null;
        return (
            tier.price +
            (tour.pricing.privateChildSupplement ?? 0) * data.children
        );
    }, [
        data.booking_type,
        data.adults,
        data.children,
        data.children_ages,
        tour.pricing,
    ]);
    const now = new Date();
    const today = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, "0")}-${String(now.getDate()).padStart(2, "0")}`;
    const draftKey = `coast-rider-booking-draft-${tour.slug}`;
    useEffect(() => {
        try {
            const saved = localStorage.getItem(draftKey);
            if (saved)
                setData({
                    ...data,
                    ...JSON.parse(saved),
                    tour_id: tour.id,
                    consent: false,
                    website: "",
                });
        } catch {}
    }, []);
    useEffect(() => {
        localStorage.setItem(
            draftKey,
            JSON.stringify({ ...data, consent: false, website: "" }),
        );
    }, [data, draftKey]);

    function submit(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();
        post("/bookings", {
            preserveScroll: true,
            onSuccess: () => localStorage.removeItem(draftKey),
            onError: (validationErrors) => {
                if (["booking_type", "travel_date", "adults", "children", "children_ages", "pickup_zone"].some((field) => field in validationErrors)) {
                    setStep(1);
                }
                window.setTimeout(() => {
                    document.getElementById("booking-errors")?.scrollIntoView({
                        behavior: "smooth",
                        block: "center",
                    });
                }, 50);
            },
        });
    }

    const errorMessages = Object.values(errors);

    function continueToContact() {
        if (!data.travel_date) {
            document.getElementById("travel-date")?.focus();
            return;
        }
        setStep(2);
        window.scrollTo({ top: 0, behavior: "smooth" });
    }

    return (
        <div className="min-h-screen bg-[var(--sand)]/45">
            <Head title={`Book ${tour.title}`}>
                <meta
                    name="description"
                    content={`Send a booking request for ${tour.title} with Coast Rider Tour.`}
                />
            </Head>
            <Navbar />

            <main className="mx-auto max-w-7xl px-4 py-10 sm:px-6 md:py-16 lg:px-8">
                <Link
                    href={`/tours/${tour.slug}`}
                    className="text-sm font-semibold text-[var(--ocean)] hover:underline"
                >
                    ← Back to tour details
                </Link>

                <div className="mt-6 lg:hidden">
                    <div className="flex items-center gap-4 rounded-2xl border border-border bg-card p-3 shadow-sm">
                        <img
                            src={tour.image}
                            alt={tour.title}
                            className="h-20 w-24 shrink-0 rounded-xl object-cover"
                        />
                        <div className="min-w-0">
                            <p className="text-[10px] font-bold uppercase tracking-wider text-[var(--turquoise)]">
                                Your tour
                            </p>
                            <h2 className="mt-1 truncate font-display text-lg font-bold">
                                {tour.title}
                            </h2>
                            <p className="mt-1 text-xs text-muted-foreground">
                                {tour.duration} · {priceLabel}
                                {tour.price > 0 ? " / adult" : ""}
                            </p>
                        </div>
                    </div>
                </div>

                <div className="mt-4 grid items-start gap-8 lg:mt-6 lg:grid-cols-[minmax(0,1fr)_23rem]">
                    <section className="rounded-3xl border border-border bg-card p-6 shadow-[var(--shadow-card)] md:p-10">
                        <p className="text-xs font-bold uppercase tracking-[0.2em] text-[var(--turquoise)]">
                            Booking request
                        </p>
                        <h1 className="mt-2 font-display text-3xl font-bold md:text-4xl">
                            Book {tour.title}
                        </h1>
                        <p className="mt-3 max-w-2xl text-sm leading-6 text-muted-foreground">
                            Share the essentials. We will confirm availability
                            and the final price personally.
                        </p>
                        <div className="mt-6 grid grid-cols-2 gap-2" aria-label={`Step ${step} of 2`}>
                            {["Trip details", "Contact & send"].map((label, index) => {
                                const number = index + 1;
                                const active = step === number;
                                const complete = step > number;
                                return <div key={label} className={`rounded-xl border px-3 py-3 ${active ? "border-[var(--turquoise)] bg-[var(--turquoise)]/8" : complete ? "border-emerald-200 bg-emerald-50" : "border-border bg-background"}`}><div className="flex items-center gap-2"><span className={`grid h-6 w-6 place-items-center rounded-full text-xs font-bold ${active ? "bg-[var(--ocean)] text-white" : complete ? "bg-emerald-600 text-white" : "bg-slate-100 text-slate-500"}`}>{complete ? <Check className="h-3.5 w-3.5" /> : number}</span><span className="text-xs font-bold text-foreground">{label}</span></div></div>;
                            })}
                        </div>

                        {errorMessages.length > 0 && (
                            <div
                                id="booking-errors"
                                role="alert"
                                className="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800"
                            >
                                <p className="font-bold">
                                    Please correct the following before sending:
                                </p>
                                <ul className="mt-2 list-disc space-y-1 pl-5">
                                    {errorMessages.map((message, index) => (
                                        <li key={`${message}-${index}`}>
                                            {message}
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        )}

                        <form onSubmit={submit} className="mt-7 grid gap-5">
                            <div
                                className="absolute -left-[10000px] top-auto h-px w-px overflow-hidden"
                                aria-hidden="true"
                            >
                                <label>
                                    Website
                                    <input
                                        type="hidden"
                                        tabIndex={-1}
                                        autoComplete="off"
                                        value={data.website}
                                        onChange={(event) =>
                                            setData(
                                                "website",
                                                event.target.value,
                                            )
                                        }
                                    />
                                </label>
                            </div>

                            <div className={step === 1 ? "grid gap-5" : "hidden"}>
                            {availableStyles.length > 1 && <Field
                                label="How would you like to travel?"
                                error={errors.booking_type}
                            >
                                <div className="grid gap-2 sm:grid-cols-2">
                                    {availableStyles.map((type) => (
                                        <button
                                            key={type}
                                            type="button"
                                            aria-pressed={
                                                data.booking_type === type
                                            }
                                            onClick={() =>
                                                setData("booking_type", type)
                                            }
                                            className={`rounded-xl border px-4 py-3 text-left transition ${data.booking_type === type ? "border-[var(--turquoise)] bg-[var(--turquoise)]/10 ring-2 ring-[var(--turquoise)]/15" : "border-input bg-background"}`}
                                        >
                                            <span className="block text-sm font-bold">
                                                {type === "shared" ? "Join a group" : "Only my group"}
                                            </span>
                                            <span className="mt-0.5 block text-[11px] text-muted-foreground">
                                                {type === "shared"
                                                    ? `From ${tour.pricing.sharedAdultPrice !== null ? money(tour.pricing.sharedAdultPrice) : "a custom price"} per adult`
                                                    : "Private experience · group price"}
                                            </span>
                                        </button>
                                    ))}
                                </div>
                            </Field>}

                            <div>
                                <p className="font-display text-xl font-bold">
                                    Who is travelling?
                                </p>
                                <p className="mt-1 text-xs text-muted-foreground">
                                    {data.booking_type === "private"
                                        ? "Adults and children together determine your private group-price tier."
                                        : "Shared tour prices are calculated for each guest."}
                                </p>
                            </div>
                            <div className="grid gap-4 sm:grid-cols-2">
                                <GuestCounter
                                    label="Adults"
                                    value={data.adults}
                                    min={1}
                                    max={30}
                                    error={errors.adults}
                                    onChange={(value) =>
                                        setData("adults", value)
                                    }
                                />
                                <GuestCounter
                                    label="Children"
                                    hint="Ages 0–12"
                                    value={data.children}
                                    min={0}
                                    max={20}
                                    error={errors.children}
                                    onChange={(value) => {
                                        setData("children", value);
                                        setData(
                                            "children_ages",
                                            Array.from(
                                                { length: value },
                                                (_, index) =>
                                                    data.children_ages[index] ??
                                                    6,
                                            ),
                                        );
                                    }}
                                />
                            </div>

                            {data.children > 0 && (
                                <Field
                                    label="Children's ages"
                                    error={errors.children_ages}
                                >
                                    <div className="grid grid-cols-2 gap-3 sm:grid-cols-4">
                                        {data.children_ages.map(
                                            (age, index) => (
                                                <label
                                                    key={index}
                                                    className="grid gap-1 text-xs text-muted-foreground"
                                                >
                                                    Child {index + 1}
                                                    <select
                                                        value={age}
                                                        onChange={(event) => {
                                                            const ages = [
                                                                ...data.children_ages,
                                                            ];
                                                            ages[index] =
                                                                Number(
                                                                    event.target
                                                                        .value,
                                                                );
                                                            setData(
                                                                "children_ages",
                                                                ages,
                                                            );
                                                        }}
                                                        className={inputClass(
                                                            errors[
                                                                `children_ages.${index}` as keyof typeof errors
                                                            ],
                                                        )}
                                                    >
                                                        {Array.from(
                                                            { length: 13 },
                                                            (_, value) => (
                                                                <option
                                                                    key={value}
                                                                    value={
                                                                        value
                                                                    }
                                                                >
                                                                    {value}{" "}
                                                                    {value === 1
                                                                        ? "year"
                                                                        : "years"}
                                                                </option>
                                                            ),
                                                        )}
                                                    </select>
                                                </label>
                                            ),
                                        )}
                                    </div>
                                </Field>
                            )}

                            <div
                                className={`rounded-2xl border p-5 ${data.booking_type === "private" ? "border-[var(--gold)]/60 bg-[var(--sand)]/55" : "border-[var(--turquoise)]/25 bg-[var(--turquoise)]/5"}`}
                            >
                                <div className="flex flex-wrap items-start justify-between gap-3">
                                    <div>
                                        <p className="text-[10px] font-bold uppercase tracking-[0.16em] text-muted-foreground">
                                            {data.booking_type === "private"
                                                ? "Private group price"
                                                : "Shared tour estimate"}
                                        </p>
                                        <h3 className="mt-1 font-display text-xl font-bold">
                                            {data.booking_type === "private"
                                                ? `${totalGuests} ${totalGuests === 1 ? "guest" : "guests"}`
                                                : `${data.adults} adult${data.adults === 1 ? "" : "s"}${data.children ? ` + ${data.children} child${data.children === 1 ? "" : "ren"}` : ""}`}
                                        </h3>
                                    </div>
                                    <strong className="font-display text-2xl text-[var(--ocean)]">
                                        {estimatedTotal !== null
                                            ? money(estimatedTotal)
                                            : "Request quote"}
                                    </strong>
                                </div>
                                {data.booking_type === "private" ? (
                                    <div className="mt-4 grid gap-2 border-t border-black/10 pt-4 text-xs text-muted-foreground sm:grid-cols-2">
                                        <p>
                                            <strong className="block text-foreground">
                                                Applied tier
                                            </strong>
                                            {selectedPrivateTier
                                                ? `${selectedPrivateTier.min}–${selectedPrivateTier.max} guests`
                                                : "Large group"}
                                        </p>
                                        <p>
                                            <strong className="block text-foreground">
                                                Price basis
                                            </strong>
                                            Total for your group
                                            {estimatedTotal !== null &&
                                            totalGuests > 0
                                                ? ` · about ${money(estimatedTotal / totalGuests)} per guest`
                                                : ""}
                                            {tour.pricing
                                                .privateChildSupplement !==
                                                null && data.children > 0
                                                ? ` · includes ${money(tour.pricing.privateChildSupplement)} additional child fee per child`
                                                : ""}
                                        </p>
                                    </div>
                                ) : (
                                    <p className="mt-3 border-t border-black/10 pt-3 text-xs text-muted-foreground">
                                        Adults, children and infants use their
                                        respective per-person prices.
                                    </p>
                                )}
                            </div>

                            <div className="border-t border-border pt-5">
                                <p className="font-display text-xl font-bold">
                                    When and where?
                                </p>
                                <p className="mt-1 text-xs text-muted-foreground">
                                    Choose the preferred date and pickup area.
                                </p>
                            </div>
                            <div className="grid gap-4 sm:grid-cols-2">
                                <Field
                                    label="Travel date"
                                    error={errors.travel_date}
                                >
                                    <input
                                        id="travel-date"
                                        type="date"
                                        min={today}
                                        value={data.travel_date}
                                        onChange={(event) =>
                                            setData(
                                                "travel_date",
                                                event.target.value,
                                            )
                                        }
                                        required
                                        className={inputClass(
                                            errors.travel_date,
                                        )}
                                    />
                                </Field>
                                <Field
                                    label="Pickup area"
                                    error={errors.pickup_zone}
                                >
                                    <select
                                        value={data.pickup_zone}
                                        onChange={(event) =>
                                            setData(
                                                "pickup_zone",
                                                event.target.value,
                                            )
                                        }
                                        className={inputClass(
                                            errors.pickup_zone,
                                        )}
                                        required
                                    >
                                        <option value="not_sure">
                                            Not sure yet
                                        </option>
                                        <option value="stone_town">
                                            Stone Town / Airport
                                        </option>
                                        <option value="north">
                                            North Coast
                                        </option>
                                        <option value="east">East Coast</option>
                                        <option value="south_east">
                                            South & South East Coast
                                        </option>
                                        <option value="other">
                                            Other area
                                        </option>
                                    </select>
                                </Field>
                            </div>

                            <div className="mt-1 flex items-center justify-between gap-3">
                                <span className="inline-flex items-center gap-1.5 text-xs font-semibold text-muted-foreground"><Clock className="h-4 w-4 text-[var(--turquoise)]" /> About 1 minute</span>
                                <button type="button" onClick={continueToContact} disabled={!data.travel_date} className="inline-flex min-h-12 items-center justify-center rounded-full bg-[var(--ocean)] px-7 text-sm font-bold text-white transition hover:brightness-110 disabled:cursor-not-allowed disabled:opacity-45">Continue</button>
                            </div>
                            {!data.travel_date && <p className="-mt-3 text-right text-xs text-muted-foreground">Choose a date to continue</p>}
                            </div>

                            <div className={step === 2 ? "grid gap-5" : "hidden"}>
                            <div className="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-[var(--turquoise)]/25 bg-[var(--turquoise)]/5 p-4">
                                <div><p className="text-[10px] font-bold uppercase tracking-wider text-muted-foreground">Your selection</p><p className="mt-1 text-sm font-bold capitalize">{data.booking_type} · {totalGuests} guest{totalGuests === 1 ? "" : "s"}</p><p className="mt-0.5 text-xs text-muted-foreground">{data.travel_date ? new Date(`${data.travel_date}T12:00:00`).toLocaleDateString("en-US", { day: "numeric", month: "short", year: "numeric" }) : "Date not selected"}</p></div>
                                <div className="text-right"><p className="text-[10px] font-bold uppercase tracking-wider text-muted-foreground">Estimated total</p><strong className="font-display text-xl text-[var(--ocean)]">{estimatedTotal !== null ? money(estimatedTotal) : "Custom quote"}</strong><button type="button" onClick={() => setStep(1)} className="mt-1 block text-xs font-bold text-[var(--turquoise)] hover:underline">Edit trip</button></div>
                            </div>
                            <div>
                                <p className="font-display text-xl font-bold">
                                    Your contact
                                </p>
                                <p className="mt-1 text-xs text-muted-foreground">
                                    We normally reply on WhatsApp.
                                </p>
                            </div>
                            <div className="grid gap-4 sm:grid-cols-2">
                                <Field
                                    label="Full name"
                                    error={errors.full_name}
                                >
                                    <input
                                        value={data.full_name}
                                        onChange={(event) =>
                                            setData(
                                                "full_name",
                                                event.target.value,
                                            )
                                        }
                                        autoComplete="name"
                                        required
                                        className={inputClass(errors.full_name)}
                                        placeholder="Your full name"
                                    />
                                </Field>
                                <Field
                                    label="WhatsApp number"
                                    error={errors.whatsapp}
                                >
                                    <input
                                        value={data.whatsapp}
                                        onChange={(event) =>
                                            setData(
                                                "whatsapp",
                                                event.target.value,
                                            )
                                        }
                                        autoComplete="tel"
                                        inputMode="tel"
                                        required
                                        className={inputClass(errors.whatsapp)}
                                        placeholder="+255 ..."
                                    />
                                </Field>
                            </div>

                            <details className="group rounded-2xl border border-border bg-background">
                                <summary className="flex cursor-pointer list-none items-center justify-between px-4 py-4 text-sm font-bold text-[var(--ocean)]">
                                    <span>Add optional details</span>
                                    <span className="text-lg transition group-open:rotate-45">
                                        +
                                    </span>
                                </summary>
                                <div className="grid gap-4 border-t border-border p-4">
                                    <Field
                                        label={`Email (${data.preferred_contact === "email" ? "required" : "optional"})`}
                                        error={errors.email}
                                    >
                                        <input
                                            type="email"
                                            required={
                                                data.preferred_contact ===
                                                "email"
                                            }
                                            value={data.email}
                                            onChange={(event) =>
                                                setData(
                                                    "email",
                                                    event.target.value,
                                                )
                                            }
                                            autoComplete="email"
                                            className={inputClass(errors.email)}
                                            placeholder="you@example.com"
                                        />
                                    </Field>
                                    <Field
                                        label="Hotel / pickup location"
                                        error={errors.hotel}
                                    >
                                        <input
                                            value={data.hotel}
                                            onChange={(event) =>
                                                setData(
                                                    "hotel",
                                                    event.target.value,
                                                )
                                            }
                                            className={inputClass(errors.hotel)}
                                            placeholder="Hotel name or area"
                                        />
                                    </Field>
                                    <div className="sm:col-span-2">
                                        <Field
                                            label="Special requests"
                                            error={errors.special_requests}
                                        >
                                            <textarea
                                                rows={3}
                                                value={data.special_requests}
                                                onChange={(event) =>
                                                    setData(
                                                        "special_requests",
                                                        event.target.value,
                                                    )
                                                }
                                                className={inputClass(
                                                    errors.special_requests,
                                                )}
                                                placeholder="Dietary needs, accessibility or anything else"
                                            />
                                        </Field>
                                    </div>
                                </div>
                            </details>

                            <label className="flex items-start gap-3 rounded-xl bg-[var(--sand)]/65 p-4 text-xs leading-5 text-muted-foreground">
                                <input
                                    type="checkbox"
                                    checked={data.consent}
                                    onChange={(event) =>
                                        setData("consent", event.target.checked)
                                    }
                                    required
                                    className="mt-0.5"
                                />
                                <span>
                                    I agree to be contacted about this request
                                    and accept the{" "}
                                    <a
                                        href="/terms-and-conditions"
                                        target="_blank"
                                        className="font-bold text-[var(--ocean)] underline"
                                    >
                                        Terms & Conditions
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
                                    . No payment is required now.
                                </span>
                            </label>
                            {errors.consent && (
                                <span className="-mt-3 text-xs font-medium text-red-600">
                                    {errors.consent}
                                </span>
                            )}

                            <div className="grid grid-cols-[auto_1fr] gap-3">
                                <button type="button" onClick={() => setStep(1)} className="min-h-12 rounded-full border border-border bg-white px-5 text-sm font-bold text-[var(--ocean)]">Back</button>
                                <button type="submit" disabled={processing} className="inline-flex min-h-12 items-center justify-center rounded-full bg-[var(--ocean)] px-6 font-bold text-white transition hover:brightness-110 disabled:cursor-not-allowed disabled:opacity-60">{processing ? "Sending request…" : "Request to book"}</button>
                            </div>
                            <p className="text-center text-xs text-muted-foreground">
                                No payment now. We confirm availability and final price first.
                            </p>
                            </div>
                        </form>
                    </section>

                    <aside className="hidden overflow-hidden rounded-2xl lg:block border border-border bg-card shadow-[var(--shadow-card)] lg:sticky lg:top-28">
                        <img
                            src={tour.image}
                            alt={tour.title}
                            className="aspect-[4/3] w-full object-cover"
                        />
                        <div className="p-6">
                            <p className="text-xs font-bold uppercase tracking-widest text-[var(--turquoise)]">
                                Your tour
                            </p>
                            <h2 className="mt-2 font-display text-2xl font-bold">
                                {tour.title}
                            </h2>
                            <p className="mt-2 text-sm leading-6 text-muted-foreground">
                                {tour.description}
                            </p>
                            <div className="mt-5 space-y-3 border-y border-border py-5 text-sm">
                                <p className="flex items-center gap-2">
                                    <Clock className="h-4 w-4 text-[var(--turquoise)]" />{" "}
                                    {tour.duration}
                                </p>
                                <p className="flex items-center gap-2">
                                    <MapPin className="h-4 w-4 text-[var(--turquoise)]" />{" "}
                                    {tour.location}
                                </p>
                                <p className="flex items-center gap-2">
                                    <CalendarDays className="h-4 w-4 text-[var(--turquoise)]" />{" "}
                                    Flexible date confirmation
                                </p>
                            </div>
                            {(data.travel_date || data.adults > 0) && (
                                <div className="mt-5 rounded-xl bg-[var(--sand)]/70 p-4 text-sm">
                                    <p className="text-xs font-bold uppercase tracking-wider text-muted-foreground">
                                        Your request
                                    </p>
                                    <p className="mt-2 font-semibold">
                                        {data.travel_date
                                            ? new Date(
                                                  `${data.travel_date}T12:00:00`,
                                              ).toLocaleDateString("en-US", {
                                                  day: "numeric",
                                                  month: "long",
                                                  year: "numeric",
                                              })
                                            : "Date not selected"}
                                    </p>
                                    <p className="mt-1 capitalize text-muted-foreground">
                                        {data.booking_type} tour · {data.adults}{" "}
                                        {data.adults === 1 ? "adult" : "adults"}
                                        {data.children > 0
                                            ? ` · ${data.children} ${data.children === 1 ? "child" : "children"}`
                                            : ""}
                                    </p>
                                </div>
                            )}
                            <p className="mt-5 text-sm text-muted-foreground">
                                Estimated total
                            </p>
                            <p className="font-display text-3xl font-bold text-[var(--ocean)]">
                                {estimatedTotal !== null
                                    ? money(estimatedTotal)
                                    : "Request quote"}
                            </p>
                            <div className="mt-3 rounded-xl border border-[var(--turquoise)]/20 bg-[var(--turquoise)]/5 p-3 text-xs leading-5 text-muted-foreground">
                                <strong className="block text-foreground">
                                    {data.booking_type === "shared"
                                        ? "Shared tour · price per guest"
                                        : "Private tour · total group price"}
                                </strong>
                                {estimatedTotal !== null
                                    ? "Calculated from the guests selected above. Children count toward the private group tier; only special entry, flight or meal fees are added separately. Pickup supplements, if any, are confirmed before payment."
                                    : "Your group size or missing rate needs a personalised quote from our team."}
                            </div>
                            <div className="mt-5 flex items-start gap-2 text-xs leading-5 text-muted-foreground">
                                <ShieldCheck className="h-4 w-4 shrink-0 text-[var(--turquoise)]" />
                                Your details are used only to arrange this
                                booking.
                            </div>
                        </div>
                    </aside>
                </div>
            </main>
            <Footer />
        </div>
    );
}

function GuestCounter({
    label,
    hint,
    value,
    min,
    max,
    error,
    onChange,
}: {
    label: string;
    hint?: string;
    value: number;
    min: number;
    max: number;
    error?: string;
    onChange: (value: number) => void;
}) {
    return (
        <div className="grid gap-1.5">
            <span className="text-xs font-bold uppercase tracking-wider text-foreground/75">
                {label}
            </span>
            <div
                className={`flex min-h-12 items-center justify-between rounded-xl border bg-background px-2 ${error ? "border-red-500" : "border-input"}`}
            >
                <div className="px-2">
                    <span className="text-sm font-semibold">{label}</span>
                    {hint && (
                        <span className="ml-2 text-xs text-muted-foreground">
                            {hint}
                        </span>
                    )}
                </div>
                <div className="flex items-center gap-2">
                    <button
                        type="button"
                        onClick={() => onChange(Math.max(min, value - 1))}
                        disabled={value <= min}
                        aria-label={`Remove one ${label.toLowerCase()}`}
                        className="grid h-8 w-8 place-items-center rounded-full border border-border text-[var(--ocean)] disabled:opacity-30"
                    >
                        <Minus className="h-3.5 w-3.5" />
                    </button>
                    <output className="w-5 text-center text-sm font-bold">
                        {value}
                    </output>
                    <button
                        type="button"
                        onClick={() => onChange(Math.min(max, value + 1))}
                        disabled={value >= max}
                        aria-label={`Add one ${label.toLowerCase()}`}
                        className="grid h-8 w-8 place-items-center rounded-full border border-border text-[var(--ocean)] disabled:opacity-30"
                    >
                        <Plus className="h-3.5 w-3.5" />
                    </button>
                </div>
            </div>
            {error && (
                <span className="text-xs font-medium text-red-600">
                    {error}
                </span>
            )}
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
            <span className="text-xs font-bold uppercase tracking-wider text-foreground/75">
                {label}
            </span>
            {children}
            {error && (
                <span className="text-xs font-medium text-red-600">
                    {error}
                </span>
            )}
        </label>
    );
}

function inputClass(error?: string) {
    return `w-full rounded-xl border bg-background px-4 py-3 text-sm outline-none transition focus:ring-2 focus:ring-[var(--turquoise)]/25 ${
        error
            ? "border-red-500"
            : "border-input focus:border-[var(--turquoise)]"
    }`;
}
