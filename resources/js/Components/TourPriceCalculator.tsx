import { useMemo, useState } from "react";
import { ArrowRight, Check, Minus, Plus, ShieldCheck } from "lucide-react";
import { calculateTourTotal, preferredBookingType } from "@/lib/tourPricing";
import type { TourPricing } from "@/types/tour";

type Props = { slug: string; currency: string; pricing: TourPricing; safariQuote?: boolean };

export function TourPriceCalculator({ slug, currency, pricing, safariQuote = false }: Props) {
    const [adults, setAdults] = useState(2);
    const [children, setChildren] = useState(0);
    const [infants, setInfants] = useState(0);
    const bookingType = preferredBookingType(pricing);
    const money = (amount: number) => new Intl.NumberFormat("en-US", { style: "currency", currency, maximumFractionDigits: 0 }).format(amount);
    const isQuote = pricing.privateAvailable && pricing.privatePricingModel === "quote_only";
    const isPerPerson = pricing.privateAvailable && pricing.privatePricingModel === "per_person_tiered";
    const perPersonRates = pricing.privatePerPersonTiers.filter((tier) => tier.price !== null);
    const groupRates = pricing.privateTiers.filter((tier) => tier.price !== null);
    const groupRate = groupRates[0];
    const groupRatesMatch = groupRates.length > 0 && groupRates.every((tier) => tier.price === groupRate.price);
    const groupMaximum = groupRates.reduce((maximum, tier) => Math.max(maximum, tier.max), 0);
    const sharedRate = pricing.sharedAvailable ? pricing.sharedAdultPrice : null;
    const estimatedTotal = useMemo(
        () => calculateTourTotal(pricing, adults, children, infants, bookingType),
        [pricing, adults, children, infants, bookingType],
    );
    const totalGuests = adults + children + infants;
    const needsQuote = estimatedTotal === null;
    const continueHref = safariQuote
        ? `/safaris?safari=${encodeURIComponent(slug)}&adults=${adults}&children=${children + infants}#plan-safari`
        : `/book/${slug}?type=${bookingType}&adults=${adults}&children=${children}&infants=${infants}`;
    const pricingExplanation = isQuote
        ? "Send your date, group size and hotel. We will reply with one complete price before payment."
        : isPerPerson
          ? "The adult rate reduces as your group grows. Add children or infants to see one estimated total."
          : pricing.privateAvailable
            ? "This is one private-tour price for your group. Choose the number of travellers to see the correct total."
            : "Choose the number of travellers to see the estimated total using the per-person rates below.";

    return (
        <section id="pricing" className="scroll-mt-28 overflow-hidden rounded-3xl border border-border bg-card shadow-[var(--shadow-card)]">
            <div className="grid lg:grid-cols-[minmax(0,1fr)_20rem]">
                <div className="p-6 md:p-8">
                    <p className="text-xs font-bold uppercase tracking-[0.2em] text-[var(--turquoise)]">Simple pricing</p>
                    <h2 className="mt-2 font-display text-3xl font-bold">{isQuote ? "Request your price" : "Choose your group size"}</h2>
                    {pricing.bookingStyleLabel && <p className="mt-3 inline-flex rounded-full bg-[var(--sand)] px-3 py-1.5 text-xs font-bold text-[var(--ocean)]">{pricing.bookingStyleLabel}</p>}
                    <p className="mt-2 max-w-2xl text-sm leading-6 text-muted-foreground">
                        {pricingExplanation}
                    </p>

                    {isPerPerson && perPersonRates.length > 0 && (
                        <div className="mt-6 overflow-hidden rounded-2xl border border-border">
                            <div className="grid grid-cols-[1fr_auto] bg-[var(--sand)]/70 px-4 py-3 text-xs font-bold uppercase tracking-wider text-muted-foreground"><span>Number of adults</span><span>Price per adult</span></div>
                            {perPersonRates.map((tier) => (
                                <div key={tier.min} className="grid grid-cols-[1fr_auto] items-center border-t border-border px-4 py-3 text-sm">
                                    <span className="font-semibold">{tier.min >= 4 ? `${tier.min}–${tier.max} adults` : `${tier.min} ${tier.min === 1 ? "adult" : "adults"}`}</span>
                                    <strong className="text-[var(--ocean)]">{money(tier.price!)}</strong>
                                </div>
                            ))}
                            {pricing.privateChildPrice !== null && <div className="grid grid-cols-[1fr_auto] items-center border-t border-border px-4 py-3 text-sm"><span className="font-semibold">Child (3–12 years)</span><strong className="text-[var(--ocean)]">{money(pricing.privateChildPrice)}</strong></div>}
                            {pricing.privateInfantPrice !== null && <div className="grid grid-cols-[1fr_auto] items-center border-t border-border px-4 py-3 text-sm"><span className="font-semibold">Infant (0–2 years)</span><strong className="text-[var(--ocean)]">{pricing.privateInfantPrice === 0 ? "Free" : money(pricing.privateInfantPrice)}</strong></div>}
                        </div>
                    )}

                    {!isQuote && !isPerPerson && groupRate?.price !== null && groupRate && groupRatesMatch && (
                        <div className="mt-6 rounded-2xl border border-[var(--turquoise)]/25 bg-[var(--turquoise)]/5 p-5">
                            <p className="text-xs font-bold uppercase tracking-wider text-muted-foreground">Private group price</p>
                            <p className="mt-1 font-display text-3xl font-bold text-[var(--ocean)]">{money(groupRate.price!)}</p>
                            <p className="mt-1 text-sm text-muted-foreground">One total price for up to {groupMaximum} guests</p>
                        </div>
                    )}

                    {!isQuote && !isPerPerson && groupRates.length > 0 && !groupRatesMatch && (
                        <div className="mt-6 overflow-hidden rounded-2xl border border-border">
                            {groupRates.map((tier) => <div key={tier.min} className="grid grid-cols-[1fr_auto] items-center border-t border-border px-4 py-3 text-sm first:border-t-0"><span className="font-semibold">{tier.min === tier.max ? `${tier.min} ${tier.min === 1 ? "guest" : "guests"}` : `${tier.min}–${tier.max} guests`}</span><strong className="text-[var(--ocean)]">{money(tier.price!)}</strong></div>)}
                        </div>
                    )}

                    {!isQuote && !pricing.privateAvailable && sharedRate !== null && (
                        <div className="mt-6 rounded-2xl border border-[var(--turquoise)]/25 bg-[var(--turquoise)]/5 p-5">
                            <p className="text-xs font-bold uppercase tracking-wider text-muted-foreground">Price per adult</p>
                            <p className="mt-1 font-display text-3xl font-bold text-[var(--ocean)]">{money(sharedRate)}</p>
                        </div>
                    )}

                    {pricing.pricingNote && <p className="mt-5 text-xs leading-5 text-muted-foreground">{pricing.pricingNote}</p>}
                </div>

                <div className="flex flex-col justify-between bg-[var(--ocean)] p-6 text-white md:p-8">
                    <div>
                        <span className="grid h-11 w-11 place-items-center rounded-xl bg-white/10"><Check className="h-5 w-5 text-[var(--gold)]" /></span>
                        <h3 className="mt-5 font-display text-2xl font-bold">Your group</h3>
                        <div className="mt-4 space-y-2">
                            <GuestCounter label="Adults" detail="13+" value={adults} minimum={1} maximum={30} onChange={setAdults} />
                            <GuestCounter label="Children" detail="3–12" value={children} minimum={0} maximum={20} onChange={setChildren} />
                            <GuestCounter label="Infants" detail="0–2" value={infants} minimum={0} maximum={20} onChange={setInfants} />
                        </div>
                        <div className="mt-5 border-t border-white/15 pt-5">
                            <p className="text-xs font-bold uppercase tracking-wider text-white/55">{needsQuote ? "Price" : "Estimated total"}</p>
                            <p className="mt-1 font-display text-3xl font-bold">{needsQuote ? "Request a quote" : money(estimatedTotal)}</p>
                            <p className="mt-1 text-xs text-white/65">For {totalGuests} guest{totalGuests === 1 ? "" : "s"}</p>
                        </div>
                    </div>
                    <div className="mt-7">
                        <a href={continueHref} className="inline-flex w-full items-center justify-center gap-2 rounded-full bg-[var(--gold)] px-5 py-3.5 text-sm font-bold text-foreground transition hover:brightness-95">
                            {needsQuote ? "Request a quote" : "Continue to booking"}<ArrowRight className="h-4 w-4" />
                        </a>
                        <p className="mt-3 flex items-center justify-center gap-1.5 text-[11px] text-white/65"><ShieldCheck className="h-3.5 w-3.5" /> No payment required now</p>
                    </div>
                </div>
            </div>
        </section>
    );
}

function GuestCounter({ label, detail, value, minimum, maximum, onChange }: { label: string; detail: string; value: number; minimum: number; maximum: number; onChange: (value: number) => void }) {
    return <div className="flex items-center justify-between rounded-xl bg-white/10 px-3 py-2.5"><div><p className="text-sm font-bold">{label}</p><p className="text-[10px] text-white/55">Age {detail}</p></div><div className="flex items-center gap-2"><button type="button" aria-label={`Remove one ${label.toLowerCase()}`} disabled={value <= minimum} onClick={() => onChange(value - 1)} className="grid h-8 w-8 place-items-center rounded-full border border-white/20 disabled:opacity-30"><Minus className="h-3.5 w-3.5" /></button><output className="w-5 text-center text-sm font-bold">{value}</output><button type="button" aria-label={`Add one ${label.toLowerCase()}`} disabled={value >= maximum} onClick={() => onChange(value + 1)} className="grid h-8 w-8 place-items-center rounded-full bg-white text-[var(--ocean)] disabled:opacity-30"><Plus className="h-3.5 w-3.5" /></button></div></div>;
}
