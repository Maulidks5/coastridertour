import { FormEvent, useEffect, useMemo, useState } from "react";
import { Head, useForm, usePage } from "@inertiajs/react";
import {
    ArrowRight,
    Binoculars,
    Bus,
    CarFront,
    CheckCircle2,
    Clock3,
    Info,
    Luggage,
    MapPin,
    MessageCircle,
    Minus,
    Plane,
    Plus,
    Send,
    Users,
} from "lucide-react";
import { Footer } from "@/Components/Footer";
import { Navbar } from "@/Components/Navbar";
import { getWhatsAppLink, useSiteConfig } from "@/config/site";

const services = [
    {
        value: "airport-transfer",
        icon: Plane,
        title: "Airport Transfers",
        description:
            "A smooth welcome or departure with flight-aware pickup and luggage assistance.",
    },
    {
        value: "hotel-transfer",
        icon: CarFront,
        title: "Hotel & Resort Transfers",
        description:
            "Comfortable private travel between your hotel, resort and island destinations.",
    },
    {
        value: "tour-transfer",
        icon: Binoculars,
        title: "Tour & Activity Transfers",
        description:
            "Pickup from your hotel to an excursion, attraction or activity—with an optional return journey.",
    },
    {
        value: "private-driver",
        icon: Clock3,
        title: "Private Driver",
        description:
            "A flexible vehicle and local driver available for your own schedule and stops.",
    },
    {
        value: "group-transport",
        icon: Bus,
        title: "Group Transport",
        description:
            "Coordinated transport for families, groups, events and organised excursions.",
    },
];

const vehicles = [
    {
        value: "sedan",
        title: "Sedan",
        example: "Toyota Premio or similar",
        passengers: "1–3 guests",
        luggage: "Up to 3 bags",
        icon: CarFront,
        image: "/images/experiences/transport-sedan.jpg",
    },
    {
        value: "suv-minivan",
        title: "SUV / Minivan",
        example: "Toyota Noah, Alphard or similar",
        passengers: "4–6 guests",
        luggage: "Up to 5 bags",
        icon: CarFront,
        image: "/images/experiences/transport-minivan.jpg",
    },
    {
        value: "van",
        title: "Van",
        example: "Toyota Hiace or similar",
        passengers: "7–14 guests",
        luggage: "Group luggage",
        icon: Bus,
        image: "/images/experiences/transport-van.jpg",
    },
    {
        value: "minibus",
        title: "Minibus",
        example: "Toyota Coaster or similar",
        passengers: "15–28 guests",
        luggage: "Large group luggage",
        icon: Bus,
        image: "/images/experiences/transport-minibus.jpg",
    },
];

const popularLocations = [
    "Zanzibar International Airport (ZNZ)",
    "Stone Town",
    "Zanzibar Ferry Terminal",
    "Nungwi",
    "Kendwa",
    "Paje",
    "Jambiani",
    "Kiwengwa",
    "Matemwe",
    "Michamvi",
    "Bwejuu",
    "Dongwe",
    "Uroa",
    "Kizimkazi",
    "Fumba",
    "Mangapwani",
    "Chwaka",
    "Jozani Forest",
    "Prison Island departure point",
    "Safari Blue departure point",
];

function recommendedVehicle(passengers: number) {
    return passengers <= 3
        ? "sedan"
        : passengers <= 6
          ? "suv-minivan"
          : passengers <= 14
            ? "van"
            : "minibus";
}

type PricedRoute = {
    id: number;
    pickup: string;
    destination: string;
    currency: string;
    prices: Record<string, number | null>;
    returnDiscount: number;
    nightSurcharge: number;
    nightStart: string;
    nightEnd: string;
    childSeatFee: number;
    bidirectional: boolean;
};
type Props = {
    submittedRequest: {
        reference: string;
        pickup: string;
        destination: string;
        travelDate: string;
        phone: string;
    } | null;
    tourDestinations: Array<{ title: string; location: string | null }>;
    pricedRoutes: PricedRoute[];
};

export default function Transport({ tourDestinations, pricedRoutes }: Props) {
    const page = usePage<{
        flash: {
            transportRequest?: {
                reference: string;
                route: string;
                date: string;
                passengers: number;
                estimatedPrice: number | null;
                estimatedCurrency: string | null;
            } | null;
        };
    }>();
    const confirmation = page.props.flash?.transportRequest;
    const site = useSiteConfig();
    const [step, setStep] = useState(1);
    const [vehicleChooserOpen, setVehicleChooserOpen] = useState(false);
    const form = useForm({
        full_name: "",
        phone: "",
        email: "",
        service_type: "airport-transfer",
        trip_type: "one-way",
        hire_duration: "",
        pickup_location: "",
        destination: "",
        travel_date: "",
        pickup_time: "",
        return_date: "",
        return_time: "",
        passengers: 1,
        luggage: 1,
        child_seats: 0,
        vehicle_category: "sedan",
        airport_direction: "arrival",
        airline: "",
        flight_number: "",
        notes: "",
        consent: false,
        website: "",
    });
    const now = new Date();
    const today = `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, "0")}-${String(now.getDate()).padStart(2, "0")}`;
    const destinationOptions =
        form.data.service_type === "tour-transfer"
            ? tourDestinations.map((tour) => ({
                  value: tour.title,
                  detail: tour.location,
              }))
            : popularLocations.map((location) => ({
                  value: location,
                  detail: null,
              }));
    const estimate = useMemo(
        () => calculateEstimate(pricedRoutes, form.data),
        [
            pricedRoutes,
            form.data.pickup_location,
            form.data.destination,
            form.data.vehicle_category,
            form.data.trip_type,
            form.data.pickup_time,
            form.data.return_time,
            form.data.child_seats,
        ],
    );
    const selectedVehicle = vehicles.find(
        (vehicle) => vehicle.value === form.data.vehicle_category,
    );
    useEffect(() => {
        try {
            const saved = localStorage.getItem("coast-rider-transport-draft");
            if (saved)
                form.setData({
                    ...form.data,
                    ...JSON.parse(saved),
                    consent: false,
                    website: "",
                });
        } catch {}
    }, []);
    useEffect(() => {
        if (confirmation)
            localStorage.removeItem("coast-rider-transport-draft");
        else
            localStorage.setItem(
                "coast-rider-transport-draft",
                JSON.stringify({ ...form.data, consent: false, website: "" }),
            );
    }, [form.data, confirmation]);
    function chooseService(value: string) {
        form.setData("service_type", value);
        document
            .getElementById("request")
            ?.scrollIntoView({ behavior: "smooth" });
    }
    function chooseVehicle(value: string) {
        form.setData("vehicle_category", value);
        document
            .getElementById("request")
            ?.scrollIntoView({ behavior: "smooth" });
    }

    function submit(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();
        form.post("/transport", {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                setStep(1);
            },
            onError: (errors) => {
                const firstStep = [
                    "service_type",
                    "trip_type",
                    "hire_duration",
                    "pickup_location",
                    "destination",
                    "travel_date",
                    "pickup_time",
                    "return_date",
                    "return_time",
                ];
                const secondStep = [
                    "passengers",
                    "vehicle_category",
                    "luggage",
                    "child_seats",
                    "airport_direction",
                    "airline",
                    "flight_number",
                ];
                if (firstStep.some((key) => key in errors)) setStep(1);
                else if (secondStep.some((key) => key in errors)) setStep(2);
                else setStep(3);
                window.setTimeout(
                    () =>
                        document
                            .getElementById("transport-errors")
                            ?.scrollIntoView({
                                behavior: "smooth",
                                block: "center",
                            }),
                    50,
                );
            },
        });
    }

    function nextStep() {
        form.clearErrors();
        if (step === 1) {
            if (!form.data.pickup_location)
                form.setError("pickup_location", "Enter your pickup location.");
            if (!form.data.destination)
                form.setError("destination", "Enter your destination.");
            if (!form.data.travel_date)
                form.setError("travel_date", "Choose your travel date.");
            if (
                form.data.trip_type === "hourly-hire" &&
                !form.data.hire_duration
            )
                form.setError(
                    "hire_duration",
                    "Choose how long you need the vehicle.",
                );
            if (
                !form.data.pickup_location ||
                !form.data.destination ||
                !form.data.travel_date ||
                (form.data.trip_type === "hourly-hire" &&
                    !form.data.hire_duration)
            )
                return;
        }
        if (
            step === 2 &&
            form.data.trip_type === "return" &&
            !form.data.return_date
        ) {
            form.setError("return_date", "Choose your return date.");
            return;
        }
        setStep((current) => Math.min(3, current + 1));
        document
            .getElementById("request")
            ?.scrollIntoView({ behavior: "smooth" });
    }

    return (
        <div className="min-h-screen bg-background">
            <Head title="Private Transport">
                <meta
                    name="description"
                    content="Book reliable airport transfers, hotel transport and private drivers in Zanzibar with Coast Rider Tour."
                />
            </Head>
            <Navbar />
            <main>
                <section className="relative overflow-hidden bg-[var(--ocean)] text-white">
                    <img
                        src="/images/experiences/transport-van.jpg"
                        alt="Comfortable private transfer van"
                        className="absolute inset-0 h-full w-full object-cover"
                        fetchPriority="high"
                    />
                    <div className="absolute inset-0 bg-gradient-to-r from-[#052f53]/95 via-[#052f53]/75 to-[#052f53]/30" />
                    <div className="absolute inset-0 bg-gradient-to-t from-[#052f53]/55 via-transparent to-black/10" />
                    <div className="absolute -right-24 -top-32 h-96 w-96 rounded-full border border-white/10" />
                    <div className="relative mx-auto grid min-h-[32rem] max-w-7xl items-center gap-10 px-4 py-14 sm:min-h-[38rem] sm:px-6 md:py-20 lg:grid-cols-[1fr_25rem] lg:px-8">
                        <div className="relative">
                            <p className="text-xs font-bold uppercase tracking-[0.22em] text-[var(--gold)]">
                                Coast Rider Transport
                            </p>
                            <h1 className="mt-4 max-w-4xl font-display text-4xl font-extrabold leading-tight md:text-6xl">
                                Reliable transport, from arrival to adventure.
                            </h1>
                            <p className="mt-4 line-clamp-3 max-w-xl text-sm leading-6 text-white/80 sm:line-clamp-none md:text-lg md:leading-8">
                                Private airport pickups, hotel transfers and
                                driver services across Zanzibar—clear,
                                comfortable and planned around you.
                            </p>
                            <a
                                href="#request"
                                className="mt-7 inline-flex items-center gap-2 rounded-full bg-[var(--gold)] px-6 py-3 font-bold text-[#17324a]"
                            >
                                Request transport{" "}
                                <ArrowRight className="h-4 w-4" />
                            </a>
                        </div>
                        <div className="relative hidden gap-3 rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur-md sm:grid">
                            {[
                                "Professional local drivers",
                                "Private, comfortable vehicles",
                                "Clear pickup communication",
                                "Support on WhatsApp",
                            ].map((item) => (
                                <p
                                    key={item}
                                    className="flex items-center gap-3 rounded-xl bg-white/5 p-3 text-sm font-semibold"
                                >
                                    <CheckCircle2 className="h-5 w-5 text-[var(--gold)]" />
                                    {item}
                                </p>
                            ))}
                        </div>
                    </div>
                </section>

                <section className="mx-auto max-w-7xl px-4 py-16 sm:px-6 md:py-20 lg:px-8">
                    <div className="mx-auto max-w-2xl text-center">
                        <p className="text-xs font-bold uppercase tracking-[0.2em] text-[var(--turquoise)]">
                            Travel your way
                        </p>
                        <h2 className="mt-3 font-display text-3xl font-extrabold md:text-4xl">
                            Transport for every part of your stay
                        </h2>
                    </div>
                    <div className="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-5">
                        {services.map(
                            ({ value, icon: Icon, title, description }) => (
                                <button
                                    type="button"
                                    onClick={() => chooseService(value)}
                                    key={title}
                                    className="rounded-2xl border border-border bg-card p-6 text-left shadow-[var(--shadow-card)] transition hover:-translate-y-1 hover:border-[var(--turquoise)]"
                                >
                                    <span className="grid h-12 w-12 place-items-center rounded-xl bg-[var(--turquoise)]/12 text-[var(--ocean)]">
                                        <Icon className="h-5 w-5" />
                                    </span>
                                    <h3 className="mt-5 font-display text-lg font-extrabold">
                                        {title}
                                    </h3>
                                    <p className="mt-2 text-sm leading-6 text-muted-foreground">
                                        {description}
                                    </p>
                                    <span className="mt-4 inline-flex items-center gap-1 text-xs font-bold text-[var(--ocean)]">
                                        Select service{" "}
                                        <ArrowRight className="h-3.5 w-3.5" />
                                    </span>
                                </button>
                            ),
                        )}
                    </div>
                    <div className="mt-12">
                        <div className="mb-6">
                            <p className="text-xs font-bold uppercase tracking-[0.2em] text-[var(--turquoise)]">
                                Choose by group size
                            </p>
                            <h2 className="mt-2 font-display text-2xl font-bold md:text-3xl">
                                Vehicle categories
                            </h2>
                            <p className="mt-2 text-sm text-muted-foreground">
                                Real vehicle examples to help you choose. The
                                confirmed vehicle may be an equivalent model,
                                depending on availability.
                            </p>
                        </div>
                        <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                            {vehicles.map(
                                ({
                                    value,
                                    title,
                                    example,
                                    passengers,
                                    luggage,
                                    icon: Icon,
                                    image,
                                }) => (
                                    <button
                                        type="button"
                                        key={value}
                                        onClick={() => chooseVehicle(value)}
                                        className={`group overflow-hidden rounded-2xl border bg-white text-left shadow-sm transition hover:-translate-y-1 hover:shadow-md ${form.data.vehicle_category === value ? "border-[var(--turquoise)] ring-2 ring-[var(--turquoise)]/15" : "border-border"}`}
                                    >
                                        <div className="relative aspect-[16/10] overflow-hidden bg-[var(--sand)]">
                                            <img
                                                src={image}
                                                alt={`${example} vehicle example`}
                                                loading="lazy"
                                                className="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                                            />
                                            <span className="absolute left-3 top-3 grid h-10 w-10 place-items-center rounded-xl bg-white/90 text-[var(--ocean)] shadow-sm backdrop-blur">
                                                <Icon className="h-5 w-5" />
                                            </span>
                                        </div>
                                        <div className="p-5">
                                            <h3 className="font-display text-lg font-bold">
                                                {title}
                                            </h3>
                                            <p className="mt-1 text-xs text-muted-foreground">
                                                {example}
                                            </p>
                                            <div className="mt-4 space-y-1.5 text-xs font-semibold">
                                                <p className="flex items-center gap-2">
                                                    <Users className="h-3.5 w-3.5 text-[var(--turquoise)]" />
                                                    {passengers}
                                                </p>
                                                <p className="flex items-center gap-2">
                                                    <Luggage className="h-3.5 w-3.5 text-[var(--turquoise)]" />
                                                    {luggage}
                                                </p>
                                            </div>
                                        </div>
                                    </button>
                                ),
                            )}
                        </div>
                    </div>
                </section>

                {pricedRoutes.length > 0 && (
                    <section className="border-y border-border bg-white py-14">
                        <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                            <div className="flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
                                <div>
                                    <p className="text-xs font-bold uppercase tracking-[0.2em] text-[var(--turquoise)]">
                                        Clear fixed prices
                                    </p>
                                    <h2 className="mt-2 font-display text-3xl font-extrabold">
                                        Popular transfer routes
                                    </h2>
                                    <p className="mt-2 text-sm text-muted-foreground">
                                        One-way price per vehicle. Select your
                                        exact route below for a full estimate.
                                    </p>
                                </div>
                                <span className="text-xs font-bold text-[var(--ocean)]">
                                    No price per person
                                </span>
                            </div>
                            <div className="mt-7 grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                                {pricedRoutes.map((route) => (
                                    <button
                                        type="button"
                                        key={route.id}
                                        onClick={() => {
                                            form.setData((data) => ({
                                                ...data,
                                                pickup_location: route.pickup,
                                                destination: route.destination,
                                                trip_type: "one-way",
                                            }));
                                            document
                                                .getElementById("request")
                                                ?.scrollIntoView({
                                                    behavior: "smooth",
                                                });
                                        }}
                                        className="rounded-2xl border border-border p-5 text-left transition hover:border-[var(--turquoise)] hover:shadow-md"
                                    >
                                        <p className="text-xs font-semibold text-muted-foreground">
                                            {route.pickup}
                                        </p>
                                        <p className="mt-1 font-display text-lg font-bold">
                                            → {route.destination}
                                        </p>
                                        <p className="mt-4 text-sm">
                                            From{" "}
                                            <strong className="text-xl text-[var(--ocean)]">
                                                {route.currency}{" "}
                                                {firstPrice(route) ??
                                                    "Custom quote required"}
                                            </strong>{" "}
                                            <span className="text-xs text-muted-foreground">
                                                / vehicle
                                            </span>
                                        </p>
                                    </button>
                                ))}
                            </div>
                        </div>
                    </section>
                )}

                <section
                    id="request"
                    className="scroll-mt-24 bg-[var(--sand)] py-16 md:py-20"
                >
                    <div className="mx-auto grid max-w-7xl items-start gap-8 px-4 sm:px-6 lg:grid-cols-[22rem_1fr] lg:px-8">
                        <aside className="rounded-3xl bg-[var(--ocean)] p-7 text-white lg:sticky lg:top-28">
                            <MapPin className="h-7 w-7 text-[var(--gold)]" />
                            <h2 className="mt-5 font-display text-2xl font-extrabold">
                                Tell us where you need to go
                            </h2>
                            <p className="mt-3 text-sm leading-6 text-white/65">
                                Send your route and travel details to receive a
                                personalised price. No payment is required now.
                            </p>
                            <a
                                href={getWhatsAppLink(
                                    "Hello Coast Rider Tour, I need private transport.",
                                    site,
                                )}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="mt-6 inline-flex items-center gap-2 text-sm font-bold text-[var(--gold)]"
                            >
                                <MessageCircle className="h-4 w-4" /> Prefer
                                WhatsApp?
                            </a>
                        </aside>
                        <form
                            onSubmit={submit}
                            className="grid gap-5 rounded-3xl border border-border bg-white p-6 shadow-[var(--shadow-card)] md:p-9"
                        >
                            <div>
                                <p className="text-xs font-bold uppercase tracking-[0.2em] text-[var(--turquoise)]">
                                    Transport request
                                </p>
                                <h2 className="mt-2 font-display text-3xl font-extrabold">
                                    Plan your transfer
                                </h2>
                                <div
                                    className="mt-5 grid grid-cols-3 gap-2"
                                    aria-label={`Step ${step} of 3`}
                                >
                                    {[
                                        "Route",
                                        "Vehicle & price",
                                        "Contact",
                                    ].map((label, index) => (
                                        <div
                                            key={label}
                                            className={`rounded-full px-2 py-2 text-center text-[10px] font-bold sm:text-xs ${step === index + 1 ? "bg-[var(--ocean)] text-white" : step > index + 1 ? "bg-emerald-100 text-emerald-800" : "bg-slate-100 text-slate-500"}`}
                                        >
                                            {index + 1}. {label}
                                        </div>
                                    ))}
                                </div>
                            </div>
                            {confirmation && (
                                <div className="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 text-sm text-emerald-900">
                                    <p className="font-bold">
                                        Transport request received
                                    </p>
                                    <div className="mt-3 grid gap-1 text-xs">
                                        <p>
                                            <strong>Reference:</strong>{" "}
                                            {confirmation.reference}
                                        </p>
                                        <p>
                                            <strong>Route:</strong>{" "}
                                            {confirmation.route}
                                        </p>
                                        <p>
                                            <strong>Travel:</strong>{" "}
                                            {confirmation.date} ·{" "}
                                            {confirmation.passengers} passenger
                                            {confirmation.passengers === 1
                                                ? ""
                                                : "s"}
                                        </p>
                                        {confirmation.estimatedPrice !=
                                            null && (
                                            <p>
                                                <strong>Estimate:</strong>{" "}
                                                {confirmation.estimatedCurrency}{" "}
                                                {confirmation.estimatedPrice.toFixed(
                                                    2,
                                                )}{" "}
                                                per vehicle
                                            </p>
                                        )}
                                    </div>
                                    <a
                                        href={getWhatsAppLink(
                                            `Hello Coast Rider Tour, I submitted transport request ${confirmation.reference} for ${confirmation.route} on ${confirmation.date}.`,
                                            site,
                                        )}
                                        target="_blank"
                                        rel="noreferrer"
                                        className="mt-4 inline-flex items-center gap-2 rounded-full bg-[#25D366] px-4 py-2.5 text-xs font-bold text-white"
                                    >
                                        <MessageCircle className="h-4 w-4" />{" "}
                                        Continue on WhatsApp
                                    </a>
                                </div>
                            )}
                            <input
                                type="hidden"
                                tabIndex={-1}
                                autoComplete="off"
                                value={form.data.website}
                                onChange={(e) =>
                                    form.setData("website", e.target.value)
                                }
                                className="absolute -left-[10000px] h-px w-px"
                                aria-hidden="true"
                            />
                            {Object.keys(form.errors).length > 0 && (
                                <div
                                    id="transport-errors"
                                    role="alert"
                                    className="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800"
                                >
                                    <strong>
                                        Please check the highlighted fields.
                                    </strong>
                                </div>
                            )}
                            <div
                                className={
                                    step === 3
                                        ? "grid gap-5 sm:grid-cols-2"
                                        : "hidden"
                                }
                            >
                                <Field
                                    label="Full name"
                                    error={form.errors.full_name}
                                >
                                    <input
                                        required
                                        autoComplete="name"
                                        value={form.data.full_name}
                                        onChange={(e) =>
                                            form.setData(
                                                "full_name",
                                                e.target.value,
                                            )
                                        }
                                        className={input}
                                    />
                                </Field>
                                <Field
                                    label="Phone / WhatsApp"
                                    error={form.errors.phone}
                                >
                                    <input
                                        required
                                        autoComplete="tel"
                                        inputMode="tel"
                                        value={form.data.phone}
                                        onChange={(e) =>
                                            form.setData(
                                                "phone",
                                                e.target.value,
                                            )
                                        }
                                        className={input}
                                    />
                                </Field>
                            </div>
                            <div className="grid gap-5 sm:grid-cols-2">
                                <div
                                    className={step === 3 ? "block" : "hidden"}
                                >
                                    <Field
                                        label="Email (optional)"
                                        error={form.errors.email}
                                    >
                                        <input
                                            type="email"
                                            autoComplete="email"
                                            value={form.data.email}
                                            onChange={(e) =>
                                                form.setData(
                                                    "email",
                                                    e.target.value,
                                                )
                                            }
                                            className={input}
                                        />
                                    </Field>
                                </div>
                                <div
                                    className={step === 1 ? "block" : "hidden"}
                                >
                                    <Field
                                        label="Service"
                                        error={form.errors.service_type}
                                    >
                                        <select
                                            value={form.data.service_type}
                                            onChange={(e) =>
                                                form.setData(
                                                    "service_type",
                                                    e.target.value,
                                                )
                                            }
                                            className={input}
                                        >
                                            <option value="airport-transfer">
                                                Airport transfer
                                            </option>
                                            <option value="hotel-transfer">
                                                Hotel / resort transfer
                                            </option>
                                            <option value="tour-transfer">
                                                Tour / activity transfer
                                            </option>
                                            <option value="private-driver">
                                                Private driver
                                            </option>
                                            <option value="full-day-hire">
                                                Full-day vehicle with driver
                                            </option>
                                            <option value="group-transport">
                                                Group transport
                                            </option>
                                            <option value="custom-transfer">
                                                Other / custom route
                                            </option>
                                        </select>
                                    </Field>
                                </div>
                            </div>
                            {step === 1 &&
                                form.data.service_type === "tour-transfer" && (
                                    <div className="rounded-2xl border border-sky-200 bg-sky-50 p-4 text-sm text-sky-900">
                                        <p className="flex gap-2 font-bold">
                                            <Info className="mt-0.5 h-4 w-4 shrink-0" />
                                            Already booking a Coast Rider tour?
                                        </p>
                                        <p className="mt-1 pl-6 text-xs leading-5 text-sky-800">
                                            Hotel pickup may already be
                                            included. Send this request only if
                                            you need separate transport, a
                                            different pickup point, or transport
                                            to another activity.
                                        </p>
                                    </div>
                                )}
                            <div className={step === 1 ? "block" : "hidden"}>
                                <Field
                                    label="Journey type"
                                    error={form.errors.trip_type}
                                >
                                    <div className="grid grid-cols-3 gap-2">
                                        {[
                                            ["one-way", "One-way"],
                                            ["return", "Return"],
                                            ["hourly-hire", "Hourly / day"],
                                        ].map(([value, label]) => (
                                            <button
                                                key={value}
                                                type="button"
                                                aria-pressed={
                                                    form.data.trip_type ===
                                                    value
                                                }
                                                onClick={() => {
                                                    form.setData(
                                                        "trip_type",
                                                        value,
                                                    );
                                                    form.setData(
                                                        "hire_duration",
                                                        value === "hourly-hire"
                                                            ? "4-hours"
                                                            : "",
                                                    );
                                                }}
                                                className={`rounded-xl border px-3 py-3 text-xs font-bold ${form.data.trip_type === value ? "border-[var(--ocean)] bg-[var(--ocean)] text-white" : "border-border"}`}
                                            >
                                                {label}
                                            </button>
                                        ))}
                                    </div>
                                </Field>
                            </div>
                            {step === 1 &&
                                form.data.trip_type === "hourly-hire" && (
                                    <Field
                                        label="How long do you need the vehicle?"
                                        error={form.errors.hire_duration}
                                    >
                                        <div className="grid grid-cols-3 gap-2">
                                            {[
                                                ["4-hours", "Half day · 4h"],
                                                ["8-hours", "Full day · 8h"],
                                                ["12-hours", "Extended · 12h"],
                                            ].map(([value, label]) => (
                                                <button
                                                    key={value}
                                                    type="button"
                                                    onClick={() =>
                                                        form.setData(
                                                            "hire_duration",
                                                            value,
                                                        )
                                                    }
                                                    className={`rounded-xl border px-3 py-3 text-xs font-bold ${form.data.hire_duration === value ? "border-[var(--ocean)] bg-[var(--ocean)] text-white" : "border-border"}`}
                                                >
                                                    {label}
                                                </button>
                                            ))}
                                        </div>
                                    </Field>
                                )}
                            <div
                                className={
                                    step === 1
                                        ? "grid gap-5 sm:grid-cols-2"
                                        : "hidden"
                                }
                            >
                                <Field
                                    label={
                                        form.data.service_type ===
                                        "tour-transfer"
                                            ? "Pickup hotel / location"
                                            : "Pickup location"
                                    }
                                    error={form.errors.pickup_location}
                                >
                                    <input
                                        required
                                        list="pickup-locations"
                                        value={form.data.pickup_location}
                                        onChange={(e) =>
                                            form.setData(
                                                "pickup_location",
                                                e.target.value,
                                            )
                                        }
                                        className={input}
                                        placeholder="Search or type a hotel / area"
                                        autoComplete="off"
                                    />
                                    <datalist id="pickup-locations">
                                        {popularLocations.map((location) => (
                                            <option
                                                key={location}
                                                value={location}
                                            />
                                        ))}
                                    </datalist>
                                    <span className="text-[11px] font-normal normal-case tracking-normal text-muted-foreground">
                                        Select a suggestion or type another
                                        hotel, address or meeting point.
                                    </span>
                                </Field>
                                <Field
                                    label={
                                        form.data.service_type ===
                                        "tour-transfer"
                                            ? "Tour / activity destination"
                                            : "Destination"
                                    }
                                    error={form.errors.destination}
                                >
                                    <input
                                        required
                                        list="destination-locations"
                                        value={form.data.destination}
                                        onChange={(e) =>
                                            form.setData(
                                                "destination",
                                                e.target.value,
                                            )
                                        }
                                        className={input}
                                        placeholder={
                                            form.data.service_type ===
                                            "tour-transfer"
                                                ? "Search a tour or type an activity"
                                                : "Search or type a hotel / area"
                                        }
                                        autoComplete="off"
                                    />
                                    <datalist id="destination-locations">
                                        {destinationOptions.map((option) => (
                                            <option
                                                key={`${option.value}-${option.detail ?? ""}`}
                                                value={option.value}
                                            >
                                                {option.detail || undefined}
                                            </option>
                                        ))}
                                    </datalist>
                                    <span className="text-[11px] font-normal normal-case tracking-normal text-muted-foreground">
                                        Select a suggestion or type any
                                        destination not shown.
                                    </span>
                                </Field>
                            </div>
                            <div
                                className={
                                    step === 1
                                        ? "grid gap-5 sm:grid-cols-3"
                                        : "hidden"
                                }
                            >
                                <Field
                                    label="Travel date"
                                    error={form.errors.travel_date}
                                >
                                    <input
                                        required
                                        type="date"
                                        min={today}
                                        value={form.data.travel_date}
                                        onChange={(e) =>
                                            form.setData(
                                                "travel_date",
                                                e.target.value,
                                            )
                                        }
                                        className={input}
                                    />
                                </Field>
                                <Field
                                    label="Pickup time"
                                    error={form.errors.pickup_time}
                                >
                                    <input
                                        type="time"
                                        value={form.data.pickup_time}
                                        onChange={(e) =>
                                            form.setData(
                                                "pickup_time",
                                                e.target.value,
                                            )
                                        }
                                        className={input}
                                    />
                                </Field>
                                <Field
                                    label="Passengers"
                                    error={form.errors.passengers}
                                >
                                    <input
                                        required
                                        type="number"
                                        min={1}
                                        max={100}
                                        value={form.data.passengers}
                                        onChange={(e) => {
                                            const count = Number(
                                                e.target.value,
                                            );
                                            form.setData("passengers", count);
                                            form.setData(
                                                "vehicle_category",
                                                recommendedVehicle(count),
                                            );
                                        }}
                                        className={input}
                                    />
                                </Field>
                            </div>
                            <div className={step === 2 ? "block" : "hidden"}>
                                <Field
                                    label="Recommended vehicle"
                                    error={form.errors.vehicle_category}
                                >
                                    <div className="flex items-center gap-3 rounded-2xl border border-sky-200 bg-sky-50 p-3">
                                        {selectedVehicle && (
                                            <img
                                                src={selectedVehicle.image}
                                                alt=""
                                                className="h-14 w-20 rounded-xl object-cover"
                                            />
                                        )}
                                        <div className="min-w-0 flex-1">
                                            <p className="text-sm font-bold text-slate-900">
                                                {selectedVehicle?.title}
                                            </p>
                                            <p className="text-[11px] text-slate-600">
                                                {selectedVehicle?.example} · {selectedVehicle?.passengers}
                                            </p>
                                        </div>
                                        <button
                                            type="button"
                                            onClick={() =>
                                                setVehicleChooserOpen(
                                                    !vehicleChooserOpen,
                                                )
                                            }
                                            className="shrink-0 text-xs font-bold text-[var(--ocean)] underline"
                                        >
                                            Change
                                        </button>
                                    </div>
                                    {vehicleChooserOpen && (
                                        <div className="mt-2 grid grid-cols-2 gap-2 sm:grid-cols-4">
                                            {vehicles.map((vehicle) => (
                                                <button
                                                    type="button"
                                                    key={vehicle.value}
                                                    onClick={() => {
                                                        form.setData(
                                                            "vehicle_category",
                                                            vehicle.value,
                                                        );
                                                        setVehicleChooserOpen(
                                                            false,
                                                        );
                                                    }}
                                                    className={`rounded-xl border px-3 py-3 text-left ${form.data.vehicle_category === vehicle.value ? "border-[var(--ocean)] bg-[var(--ocean)] text-white" : "border-border"}`}
                                                >
                                                    <span className="block text-xs font-bold">
                                                        {vehicle.title}
                                                    </span>
                                                    <span className="mt-0.5 block text-[10px] opacity-70">
                                                        {vehicle.passengers}
                                                    </span>
                                                </button>
                                            ))}
                                        </div>
                                    )}
                                </Field>
                            </div>
                            {step === 2 && form.data.trip_type === "return" && (
                                <div className="grid gap-5 sm:grid-cols-2">
                                    <Field
                                        label="Return date"
                                        error={form.errors.return_date}
                                    >
                                        <input
                                            required
                                            type="date"
                                            min={form.data.travel_date || today}
                                            value={form.data.return_date}
                                            onChange={(e) =>
                                                form.setData(
                                                    "return_date",
                                                    e.target.value,
                                                )
                                            }
                                            className={input}
                                        />
                                    </Field>
                                    <Field
                                        label="Return time"
                                        error={form.errors.return_time}
                                    >
                                        <input
                                            type="time"
                                            value={form.data.return_time}
                                            onChange={(e) =>
                                                form.setData(
                                                    "return_time",
                                                    e.target.value,
                                                )
                                            }
                                            className={input}
                                        />
                                    </Field>
                                </div>
                            )}
                            <div
                                className={
                                    step === 2
                                        ? "grid gap-5 sm:grid-cols-2"
                                        : "hidden"
                                }
                            >
                                <Field
                                    label="Luggage / bags"
                                    error={form.errors.luggage}
                                >
                                    <NumberCounter
                                        value={form.data.luggage}
                                        min={0}
                                        max={100}
                                        onChange={(value) =>
                                            form.setData("luggage", value)
                                        }
                                    />
                                </Field>
                                <Field
                                    label="Child seats"
                                    error={form.errors.child_seats}
                                >
                                    <NumberCounter
                                        value={form.data.child_seats}
                                        min={0}
                                        max={20}
                                        onChange={(value) =>
                                            form.setData("child_seats", value)
                                        }
                                    />
                                </Field>
                            </div>
                            {step === 2 &&
                                form.data.service_type ===
                                    "airport-transfer" && (
                                    <div className="grid gap-5 sm:grid-cols-2">
                                        <Field
                                            label="Airport journey"
                                            error={
                                                form.errors.airport_direction
                                            }
                                        >
                                            <select
                                                value={
                                                    form.data.airport_direction
                                                }
                                                onChange={(e) =>
                                                    form.setData(
                                                        "airport_direction",
                                                        e.target.value,
                                                    )
                                                }
                                                className={input}
                                            >
                                                <option value="arrival">
                                                    Arrival pickup
                                                </option>
                                                <option value="departure">
                                                    Departure drop-off
                                                </option>
                                            </select>
                                        </Field>
                                        <Field
                                            label="Flight number (recommended)"
                                            error={form.errors.flight_number}
                                        >
                                            <input
                                                value={form.data.flight_number}
                                                onChange={(e) =>
                                                    form.setData(
                                                        "flight_number",
                                                        e.target.value,
                                                    )
                                                }
                                                className={input}
                                            />
                                        </Field>
                                    </div>
                                )}
                            {step === 2 &&
                                form.data.pickup_location &&
                                form.data.destination && (
                                    <div
                                        className={`rounded-2xl border p-5 ${estimate ? "border-emerald-200 bg-emerald-50" : "border-amber-200 bg-amber-50"}`}
                                    >
                                        {estimate ? (
                                            <>
                                                <p className="text-xs font-bold uppercase tracking-wider text-emerald-700">
                                                    Your estimated fixed price
                                                </p>
                                                <div className="mt-2 flex flex-wrap items-end justify-between gap-3">
                                                    <p className="font-display text-3xl font-extrabold text-emerald-950">
                                                        {estimate.currency}{" "}
                                                        {estimate.total.toFixed(
                                                            2,
                                                        )}
                                                    </p>
                                                    <span className="rounded-full bg-white px-3 py-1.5 text-xs font-bold text-emerald-800">
                                                        Per vehicle ·{" "}
                                                        {form.data.trip_type ===
                                                        "return"
                                                            ? "Return"
                                                            : "One-way"}
                                                    </span>
                                                </div>
                                                <p className="mt-2 text-xs leading-5 text-emerald-800">
                                                    Includes the selected
                                                    vehicle
                                                    {estimate.discount > 0
                                                        ? `, ${estimate.discountPercent}% return discount`
                                                        : ""}
                                                    {estimate.night > 0
                                                        ? ` and ${estimate.currency} ${estimate.night.toFixed(2)} night surcharge`
                                                        : ""}
                                                    . Final availability is
                                                    confirmed by our team.
                                                </p>
                                            </>
                                        ) : (
                                            <>
                                                <p className="font-bold text-amber-950">
                                                    Custom quote required
                                                </p>
                                                <p className="mt-1 text-xs leading-5 text-amber-800">
                                                    This route or vehicle does
                                                    not yet have a fixed online
                                                    price. Submit the request
                                                    and our team will send a
                                                    personalised price.
                                                </p>
                                            </>
                                        )}
                                    </div>
                                )}
                            <div className={step === 3 ? "block" : "hidden"}>
                                <div className="mb-5 rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <p className="text-[10px] font-bold uppercase tracking-wider text-[var(--turquoise)]">
                                        Request summary
                                    </p>
                                    <div className="mt-2 grid gap-2 text-xs text-slate-700 sm:grid-cols-2">
                                        <p><strong>Route:</strong> {form.data.pickup_location} → {form.data.destination}</p>
                                        <p><strong>Travel:</strong> {form.data.travel_date}{form.data.pickup_time ? ` at ${form.data.pickup_time}` : ""}</p>
                                        <p><strong>Guests:</strong> {form.data.passengers} · {form.data.luggage} bag{form.data.luggage === 1 ? "" : "s"}</p>
                                        <p><strong>Vehicle:</strong> {selectedVehicle?.title ?? "Private vehicle"}</p>
                                        <p className="font-bold text-[var(--ocean)] sm:col-span-2">
                                            {estimate ? `Estimated price: ${estimate.currency} ${estimate.total.toFixed(2)} per vehicle` : "Price: Custom quote required"}
                                        </p>
                                    </div>
                                </div>
                                <Field
                                    label="Additional details"
                                    error={form.errors.notes}
                                >
                                    <textarea
                                        rows={4}
                                        value={form.data.notes}
                                        onChange={(e) =>
                                            form.setData(
                                                "notes",
                                                e.target.value,
                                            )
                                        }
                                        className={input}
                                        placeholder="Luggage, child seats, special stops or other requests…"
                                    />
                                </Field>
                            </div>
                            {step === 3 && (
                                <div className="rounded-xl border border-sky-100 bg-sky-50 p-4 text-xs leading-5 text-sky-800">
                                    The displayed amount is an estimate and no
                                    payment is required now. Our team will
                                    confirm vehicle availability and your final
                                    transfer details.
                                </div>
                            )}
                            {step === 3 && (
                                <label className="flex items-start gap-3 rounded-xl border border-border p-4 text-xs leading-5 text-muted-foreground">
                                    <input
                                        required
                                        type="checkbox"
                                        checked={form.data.consent}
                                        onChange={(e) =>
                                            form.setData(
                                                "consent",
                                                e.target.checked,
                                            )
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
                            )}
                            {step === 3 && form.errors.consent && (
                                <span className="text-xs font-medium text-red-600">
                                    {form.errors.consent}
                                </span>
                            )}
                            <div className="flex flex-col-reverse gap-3 sm:flex-row sm:justify-between">
                                {step > 1 && (
                                    <button
                                        type="button"
                                        onClick={() =>
                                            setStep((current) =>
                                                Math.max(1, current - 1),
                                            )
                                        }
                                        className="min-h-12 rounded-full border border-border px-7 font-bold text-[var(--ocean)]"
                                    >
                                        Back
                                    </button>
                                )}
                                {step < 3 && (
                                    <button
                                        type="button"
                                        onClick={nextStep}
                                        className="min-h-12 flex-1 rounded-full bg-[var(--ocean)] px-7 font-bold text-white sm:flex-none"
                                    >
                                        Continue to{" "}
                                        {step === 1
                                            ? "vehicle & price"
                                            : "contact"}
                                    </button>
                                )}
                                {step === 3 && (
                                    <button
                                        disabled={form.processing}
                                        className="inline-flex min-h-13 flex-1 items-center justify-center gap-2 rounded-full bg-[var(--ocean)] px-7 font-bold text-white disabled:opacity-60 sm:flex-none"
                                    >
                                        <Send className="h-4 w-4" />
                                        {form.processing
                                            ? "Sending request…"
                                            : estimate
                                              ? `Request transfer — ${estimate.currency} ${estimate.total.toFixed(2)}`
                                              : "Request a quote"}
                                    </button>
                                )}
                            </div>
                        </form>
                    </div>
                </section>
            </main>
            <Footer />
        </div>
    );
}

function NumberCounter({
    value,
    min,
    max,
    onChange,
}: {
    value: number;
    min: number;
    max: number;
    onChange: (value: number) => void;
}) {
    return (
        <div className="flex min-h-12 items-center justify-between rounded-xl border border-input bg-background px-2">
            <button
                type="button"
                aria-label="Decrease"
                disabled={value <= min}
                onClick={() => onChange(Math.max(min, value - 1))}
                className="grid h-9 w-9 place-items-center rounded-full border border-border text-[var(--ocean)] disabled:opacity-35"
            >
                <Minus className="h-4 w-4" />
            </button>
            <span className="font-display text-lg font-extrabold text-slate-900">
                {value}
            </span>
            <button
                type="button"
                aria-label="Increase"
                disabled={value >= max}
                onClick={() => onChange(Math.min(max, value + 1))}
                className="grid h-9 w-9 place-items-center rounded-full border border-border text-[var(--ocean)] disabled:opacity-35"
            >
                <Plus className="h-4 w-4" />
            </button>
        </div>
    );
}

function aliases(value: string) {
    return value
        .toLowerCase()
        .split("/")
        .map((part) => part.trim())
        .filter(Boolean);
}
function locationMatches(configured: string, requested: string) {
    const target = requested.toLowerCase().trim();
    return (
        configured.toLowerCase().trim() === target ||
        aliases(configured).includes(target)
    );
}
function isNight(time: string, start: string, end: string) {
    if (!time) return false;
    return start > end
        ? time >= start || time < end
        : time >= start && time < end;
}
function firstPrice(route: PricedRoute) {
    return Object.values(route.prices).find((price) => price !== null) ?? null;
}
function calculateEstimate(routes: PricedRoute[], data: any) {
    if (!["one-way", "return"].includes(data.trip_type)) return null;
    const route = routes.find(
        (item) =>
            (locationMatches(item.pickup, data.pickup_location) &&
                locationMatches(item.destination, data.destination)) ||
            (item.bidirectional &&
                locationMatches(item.pickup, data.destination) &&
                locationMatches(item.destination, data.pickup_location)),
    );
    if (!route) return null;
    const base = route.prices[data.vehicle_category];
    if (base == null) return null;
    const journeys = data.trip_type === "return" ? 2 : 1;
    const subtotal = base * journeys;
    const discount =
        data.trip_type === "return"
            ? subtotal * (route.returnDiscount / 100)
            : 0;
    let night = isNight(data.pickup_time, route.nightStart, route.nightEnd)
        ? route.nightSurcharge
        : 0;
    if (
        data.trip_type === "return" &&
        isNight(data.return_time, route.nightStart, route.nightEnd)
    )
        night += route.nightSurcharge;
    const seats = route.childSeatFee * Number(data.child_seats || 0);
    return {
        currency: route.currency,
        total: subtotal - discount + night + seats,
        discount,
        discountPercent: route.returnDiscount,
        night,
    };
}

const input =
    "w-full rounded-xl border border-input bg-background px-4 py-3 text-sm outline-none transition focus:border-[var(--turquoise)] focus:ring-2 focus:ring-[var(--turquoise)]/15";
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
            {error && (
                <span className="text-xs font-medium text-red-600">
                    {error}
                </span>
            )}
        </label>
    );
}
