import { Head, Link } from "@inertiajs/react";
import { CheckCircle2, Download, MessageCircle, ShieldCheck } from "lucide-react";
import { Footer } from "@/Components/Footer";
import { Navbar } from "@/Components/Navbar";
import { getWhatsAppLink, useSiteConfig } from "@/config/site";

type Props = {
  booking: {
    reference: string;
    invoiceNumber: string;
    fullName: string;
    email: string | null;
    whatsapp: string;
    tourTitle: string;
    tourSlug: string;
    travelDate: string;
    bookingType: string;
    bookingStyleLabel: string | null;
    adults: number;
    children: number;
    childrenAges: number[];
    pickupZone: string | null;
    hotel: string | null;
    specialRequests: string | null;
    currency: string;
    total: number;
    deposit: number | null;
    amountPaid: number;
    balance: number;
    paymentStatus: string;
    confirmedAt: string | null;
  };
};

export default function BookingConfirmation({ booking }: Props) {
  const site = useSiteConfig();
  const money = (amount: number) => new Intl.NumberFormat("en-US", { style: "currency", currency: booking.currency, minimumFractionDigits: 2 }).format(amount);
  const pickup = ({ stone_town: "Stone Town / Airport", north: "North Coast", east: "East Coast", south_east: "South & South East Coast", other: "Other area", not_sure: "To be confirmed" } as Record<string, string>)[booking.pickupZone ?? ""] ?? "To be confirmed";
  const guests = `${booking.adults} ${booking.adults === 1 ? "adult" : "adults"}${booking.children ? `, ${booking.children} ${booking.children === 1 ? "child" : "children"}` : ""}`;
  const message = `Hello Coast Rider Tour, I am contacting you about confirmed booking ${booking.reference} (${booking.invoiceNumber}).`;

  return (
    <div className="min-h-screen bg-slate-100 print:bg-white">
      <Head title={`Booking confirmation ${booking.reference}`}><meta name="robots" content="noindex,nofollow" /></Head>
      <div className="print:hidden"><Navbar /></div>

      <main className="mx-auto max-w-5xl px-4 py-10 sm:px-6 md:py-14 print:max-w-none print:p-0">
        <div className="mb-5 flex flex-col justify-between gap-3 sm:flex-row sm:items-center print:hidden">
          <div>
            <p className="text-xs font-bold uppercase tracking-[0.18em] text-[var(--turquoise)]">Confirmed booking</p>
            <h1 className="mt-1 font-display text-2xl font-bold">Your trip is confirmed</h1>
          </div>
          <div className="flex flex-wrap gap-2">
            <button type="button" onClick={() => window.print()} className="inline-flex items-center gap-2 rounded-full border border-[var(--ocean)] bg-white px-5 py-2.5 text-sm font-bold text-[var(--ocean)]"><Download className="h-4 w-4" /> Print / Save PDF</button>
            <a href={getWhatsAppLink(message, site)} target="_blank" rel="noopener noreferrer" className="inline-flex items-center gap-2 rounded-full bg-[#25D366] px-5 py-2.5 text-sm font-bold text-white"><MessageCircle className="h-4 w-4" /> Get help</a>
          </div>
        </div>

        <article className="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm print:rounded-none print:border-0 print:shadow-none">
          <header className="flex flex-col justify-between gap-6 bg-[var(--ocean)] px-6 py-8 text-white sm:flex-row sm:items-start md:px-10 print:bg-white print:px-0 print:text-slate-950">
            <div>
              <img src={site.logoPath} alt={site.name} className="h-16 max-w-56 rounded-lg bg-white p-1 object-contain" />
              <p className="mt-3 text-sm text-white/70 print:text-slate-500">{site.location} · {site.phone} · {site.email}</p>
            </div>
            <div className="sm:text-right">
              <p className="text-xs font-bold uppercase tracking-[0.18em] text-[var(--gold)] print:text-slate-500">Booking confirmation</p>
              <p className="mt-2 font-display text-xl font-bold">{booking.invoiceNumber}</p>
              <p className="mt-1 text-xs text-white/65 print:text-slate-500">Confirmed {booking.confirmedAt}</p>
            </div>
          </header>

          <div className="p-6 md:p-10 print:px-0">
            <div className="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-900">
              <CheckCircle2 className="mt-0.5 h-5 w-5 shrink-0" />
              <div><p className="font-bold">Booking confirmed</p><p className="mt-1 text-sm">Your date and final price have been reviewed by our team.</p></div>
            </div>

            <div className="mt-8 grid gap-8 md:grid-cols-2">
              <section>
                <h2 className="font-display text-lg font-bold">Guest</h2>
                <dl className="mt-4 space-y-3 text-sm">
                  <Row label="Name" value={booking.fullName} />
                  <Row label="Email" value={booking.email || "Not provided"} />
                  <Row label="WhatsApp" value={booking.whatsapp} />
                  <Row label="Booking reference" value={booking.reference} />
                </dl>
              </section>
              <section>
                <h2 className="font-display text-lg font-bold">Trip details</h2>
                <dl className="mt-4 space-y-3 text-sm">
                  <Row label="Experience" value={booking.tourTitle} />
                  <Row label="Travel date" value={booking.travelDate} />
                  <Row label="Tour style" value={booking.bookingStyleLabel || (booking.bookingType === "shared" ? "Shared tour" : "Private arrangement")} />
                  <Row label="Guests" value={guests} />
                  <Row label="Pickup" value={booking.hotel || pickup} />
                </dl>
              </section>
            </div>

            <section className="mt-9 overflow-hidden rounded-2xl border border-slate-200">
              <div className="grid grid-cols-[1fr_auto] bg-slate-50 px-5 py-3 text-xs font-bold uppercase tracking-wider text-slate-500"><span>Description</span><span>Amount</span></div>
              <div className="grid grid-cols-[1fr_auto] gap-4 px-5 py-5 text-sm"><div><p className="font-bold">{booking.tourTitle}</p><p className="mt-1 text-xs text-slate-500">{booking.travelDate} · {guests}</p></div><strong>{money(booking.total)}</strong></div>
              {booking.deposit !== null && <div className="flex justify-between border-t border-slate-200 px-5 py-3 text-sm"><span className="text-slate-500">Deposit requested</span><strong>{money(booking.deposit)}</strong></div>}
              <div className="flex justify-between border-t border-slate-200 px-5 py-3 text-sm"><span className="text-slate-500">Amount paid</span><strong>{money(booking.amountPaid)}</strong></div>
              <div className="flex items-end justify-between border-t border-slate-200 bg-[var(--sand)]/45 px-5 py-5"><div><p className="text-xs font-bold uppercase tracking-wider text-slate-500">Payment status</p><p className="mt-1 font-semibold capitalize">{booking.paymentStatus.replaceAll("_", " ")}</p></div><div className="text-right"><p className="text-xs text-slate-500">Balance</p><p className="font-display text-2xl font-bold text-[var(--ocean)]">{money(booking.balance)}</p></div></div>
            </section>

            {booking.specialRequests && <section className="mt-8"><h2 className="font-display text-lg font-bold">Special requests</h2><p className="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">{booking.specialRequests}</p></section>}

            <div className="mt-9 flex items-start gap-3 border-t border-slate-200 pt-6 text-xs leading-5 text-slate-500">
              <ShieldCheck className="mt-0.5 h-4 w-4 shrink-0 text-[var(--turquoise)]" />
              <p>This document confirms the booking and serves as a proforma invoice until payment is completed. It is not a payment receipt. Changes and cancellations remain subject to our booking policy.</p>
            </div>
          </div>
        </article>

        <div className="mt-6 text-center print:hidden"><Link href={`/tours/${booking.tourSlug}`} className="text-sm font-bold text-[var(--ocean)]">View tour details</Link></div>
      </main>
      <div className="print:hidden"><Footer /></div>
    </div>
  );
}

function Row({ label, value }: { label: string; value: string }) {
  return <div className="flex justify-between gap-5 border-b border-slate-100 pb-2"><dt className="text-slate-500">{label}</dt><dd className="text-right font-semibold text-slate-900">{value}</dd></div>;
}
