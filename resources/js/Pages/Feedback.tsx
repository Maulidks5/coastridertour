import { FormEvent } from "react";
import { Head, useForm } from "@inertiajs/react";
import { CheckCircle2, Star } from "lucide-react";
import { Footer } from "@/Components/Footer";
import { Navbar } from "@/Components/Navbar";
import { useSiteConfig } from "@/config/site";

type Props = {
    prefill: {
        name: string;
        email: string;
        tourName: string;
        bookingReference: string;
        alreadySubmitted: boolean;
    } | null;
};

export default function Feedback({ prefill }: Props) {
    const site = useSiteConfig();
    const form = useForm({
        name: prefill?.name ?? "",
        email: prefill?.email ?? "",
        country: "",
        tour_name: prefill?.tourName ?? "",
        booking_reference: prefill?.bookingReference ?? "",
        rating: 5,
        content: "",
        website: "",
    });
    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(`/feedback${window.location.search}`, {
            preserveScroll: true,
            onSuccess: () => form.reset("country", "content", "website"),
        });
    };

    return (
        <div className="min-h-screen bg-[var(--sand)]/40">
            <Head title={`Share your experience · ${site.name}`} />
            <Navbar />
            <main className="mx-auto max-w-3xl px-4 py-12 sm:px-6 md:py-16">
                <section className="rounded-3xl border border-border bg-card p-6 shadow-[var(--shadow-soft)] md:p-10">
                    <p className="text-xs font-bold uppercase tracking-[0.2em] text-[var(--turquoise)]">
                        Traveller feedback
                    </p>
                    <h1 className="mt-3 font-display text-3xl font-bold md:text-5xl">
                        How was your experience?
                    </h1>
                    <p className="mt-4 text-sm leading-7 text-muted-foreground">
                        Your feedback helps future travellers. Reviews are
                        checked by our team before they appear publicly.
                    </p>
                    {prefill && (
                        <div className="mt-5 rounded-xl border border-sky-200 bg-sky-50 p-4 text-sm text-sky-900">
                            <strong>{prefill.tourName}</strong>
                            <span className="ml-2 text-sky-700">
                                Booking {prefill.bookingReference}
                            </span>
                        </div>
                    )}
                    {prefill?.alreadySubmitted && (
                        <div className="mt-5 flex gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm font-semibold text-amber-800">
                            <CheckCircle2 className="h-5 w-5 shrink-0" />
                            Feedback has already been submitted for this
                            booking. Thank you.
                        </div>
                    )}
                    {form.recentlySuccessful && (
                        <div className="mt-6 flex gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700">
                            <CheckCircle2 className="h-5 w-5 shrink-0" />
                            Thank you. Your feedback has been received for
                            review.
                        </div>
                    )}
                    {!prefill?.alreadySubmitted && (
                        <form onSubmit={submit} className="mt-8 grid gap-5">
                            <input
                                type="hidden"
                                tabIndex={-1}
                                autoComplete="off"
                                value={form.data.website}
                                onChange={(event) =>
                                    form.setData("website", event.target.value)
                                }
                            />
                            <div className="grid gap-5 sm:grid-cols-2">
                                <Field
                                    label="Your name"
                                    error={form.errors.name}
                                >
                                    <input
                                        required
                                        value={form.data.name}
                                        onChange={(e) =>
                                            form.setData("name", e.target.value)
                                        }
                                        className={input(form.errors.name)}
                                    />
                                </Field>
                                <Field
                                    label="Email (not published)"
                                    error={form.errors.email}
                                >
                                    <input
                                        required
                                        type="email"
                                        value={form.data.email}
                                        onChange={(e) =>
                                            form.setData(
                                                "email",
                                                e.target.value,
                                            )
                                        }
                                        className={input(form.errors.email)}
                                    />
                                </Field>
                                <Field
                                    label="Country (optional)"
                                    error={form.errors.country}
                                >
                                    <input
                                        value={form.data.country}
                                        onChange={(e) =>
                                            form.setData(
                                                "country",
                                                e.target.value,
                                            )
                                        }
                                        placeholder="Example: Germany"
                                        className={input(form.errors.country)}
                                    />
                                </Field>
                                <Field
                                    label="Tour or safari (optional)"
                                    error={form.errors.tour_name}
                                >
                                    <input
                                        value={form.data.tour_name}
                                        onChange={(e) =>
                                            form.setData(
                                                "tour_name",
                                                e.target.value,
                                            )
                                        }
                                        placeholder="Example: Safari Blue"
                                        className={input(form.errors.tour_name)}
                                    />
                                </Field>
                            </div>
                            <fieldset>
                                <legend className="text-xs font-bold uppercase tracking-wider text-foreground/75">
                                    Your rating
                                </legend>
                                <div className="mt-2 flex gap-2">
                                    {[1, 2, 3, 4, 5].map((rating) => (
                                        <button
                                            key={rating}
                                            type="button"
                                            onClick={() =>
                                                form.setData("rating", rating)
                                            }
                                            aria-label={`${rating} stars`}
                                            className="p-1"
                                        >
                                            <Star
                                                className={`h-8 w-8 ${rating <= form.data.rating ? "fill-[var(--gold)] text-[var(--gold)]" : "text-border"}`}
                                            />
                                        </button>
                                    ))}
                                </div>
                                {form.errors.rating && (
                                    <p className="text-xs text-red-600">
                                        {form.errors.rating}
                                    </p>
                                )}
                            </fieldset>
                            <Field
                                label="Your feedback"
                                error={form.errors.content}
                            >
                                <textarea
                                    required
                                    minLength={20}
                                    maxLength={1500}
                                    rows={6}
                                    value={form.data.content}
                                    onChange={(e) =>
                                        form.setData("content", e.target.value)
                                    }
                                    placeholder="Tell other travellers what you enjoyed…"
                                    className={input(form.errors.content)}
                                />
                            </Field>
                            <div className="flex items-center justify-between text-xs text-muted-foreground">
                                <span>Minimum 20 characters</span>
                                <span>{form.data.content.length}/1500</span>
                            </div>
                            <button
                                disabled={form.processing}
                                className="min-h-12 rounded-full bg-[var(--ocean)] px-6 font-bold text-white disabled:opacity-60"
                            >
                                {form.processing
                                    ? "Sending…"
                                    : "Submit feedback"}
                            </button>
                        </form>
                    )}
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
            <span className="text-xs font-bold uppercase tracking-wider text-foreground/75">
                {label}
            </span>
            {children}
            {error && <span className="text-xs text-red-600">{error}</span>}
        </label>
    );
}
function input(error?: string) {
    return `w-full rounded-xl border bg-background px-4 py-3 text-sm outline-none focus:ring-2 focus:ring-[var(--turquoise)]/20 ${error ? "border-red-500" : "border-input focus:border-[var(--turquoise)]"}`;
}
