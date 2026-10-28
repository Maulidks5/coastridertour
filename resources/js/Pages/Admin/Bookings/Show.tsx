import { FormEvent } from "react";
import { Link, router, useForm } from "@inertiajs/react";
import { Archive, ArrowLeft, CalendarDays, CheckCircle2, ExternalLink, Mail, MessageCircle, Send, Star, Users } from "lucide-react";
import { AdminLayout } from "@/Components/AdminLayout";
import { AdminStatusBadge } from "@/Components/AdminStatusBadge";

type Props = {
  booking: {
    reference: string;
    fullName: string;
    email: string | null;
    whatsapp: string;
    travelDate: string;
    adults: number;
    children: number;
    childrenAges: number[];
    bookingType: string;
    bookingStyleLabel: string | null;
    pickupZone: string | null;
    preferredContact: string;
    estimatedPrice: number | null;
    estimatedCurrency: string | null;
    hotel: string | null;
    specialRequests: string | null;
    adminNotes: string | null;
    status: string;
    quotedPrice: number | null;
    quotedCurrency: string | null;
    depositAmount: number | null;
    amountPaid: number;
    paymentStatus: string;
    invoiceNumber: string | null;
    confirmationSentAt: string | null;
    confirmationUrl: string | null;
    source: string;
    createdAt: string;
    contactedAt: string | null;
    confirmedAt: string | null;
    cancelledAt: string | null;
    feedbackUrl: string | null;
    tour: {
      title: string;
      slug: string;
      image: string | null;
    };
  };
  statuses: string[];
};

export default function AdminBookingShow({ booking, statuses }: Props) {
  const { data, setData, patch, post, processing, errors } = useForm({
    status: booking.status,
    admin_notes: booking.adminNotes ?? "",
    quoted_price: booking.quotedPrice?.toString() ?? "",
    quoted_currency: booking.quotedCurrency ?? "USD",
    deposit_amount: booking.depositAmount?.toString() ?? "",
    amount_paid: booking.amountPaid?.toString() ?? "0",
    payment_status: booking.paymentStatus ?? "unpaid",
  });
  const whatsappNumber = booking.whatsapp.replace(/[^\d]/g, "");
  const whatsappMessage = encodeURIComponent(booking.confirmationUrl
    ? `Hello ${booking.fullName}, your booking ${booking.reference} for ${booking.tour.title} is confirmed. View your confirmation and proforma invoice here: ${booking.confirmationUrl}`
    : `Hello ${booking.fullName}, this is Coast Rider Tour regarding booking ${booking.reference} for ${booking.tour.title}.`);
  const feedbackMessage = booking.feedbackUrl ? encodeURIComponent(`Hello ${booking.fullName}, thank you for travelling with Coast Rider Tour on ${booking.tour.title}. We would love to hear about your experience. Please share your feedback here: ${booking.feedbackUrl}`) : "";
  const quotedPrice = booking.quotedPrice && booking.quotedCurrency
    ? new Intl.NumberFormat("en-US", { style: "currency", currency: booking.quotedCurrency, maximumFractionDigits: 0 }).format(booking.quotedPrice)
    : "Price on request";
  const estimatedPrice = booking.estimatedPrice !== null && booking.estimatedCurrency
    ? new Intl.NumberFormat("en-US", { style: "currency", currency: booking.estimatedCurrency, maximumFractionDigits: 0 }).format(booking.estimatedPrice)
    : "Custom quote required";

  function save(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    patch(`/admin/bookings/${booking.reference}`, { preserveScroll: true });
  }

  function confirmBooking() {
    const delivery = booking.email ? "email it to the customer" : "create a link you can send by WhatsApp";
    if (!confirm(`Confirm this booking, create its proforma invoice and ${delivery}?`)) return;
    post(`/admin/bookings/${booking.reference}/confirm`, { preserveScroll: true });
  }

  return (
    <AdminLayout title={booking.reference}>
      <Link href="/admin/bookings" className="inline-flex items-center gap-1.5 text-sm font-semibold text-[var(--ocean)]"><ArrowLeft className="h-4 w-4" /> All bookings</Link>
      <div className="mt-4 flex flex-wrap items-start justify-between gap-4">
        <div>
          <div className="flex flex-wrap items-center gap-2"><p className="text-xs font-bold uppercase tracking-[0.2em] text-[var(--turquoise)]">{booking.reference}</p><AdminStatusBadge status={booking.status} /></div>
          <h1 className="mt-2 font-display text-3xl font-bold md:text-4xl">{booking.fullName}</h1>
          <p className="mt-1 text-sm text-slate-500">Received {booking.createdAt} via {booking.source}</p>
        </div>
        <div className="flex flex-wrap gap-2">
          {booking.confirmationUrl && <a href={booking.confirmationUrl} target="_blank" rel="noopener noreferrer" className="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-5 py-2.5 text-sm font-bold text-emerald-800"><ExternalLink className="h-4 w-4" /> View confirmation</a>}
          <button onClick={() => confirm("Archive this booking? You can restore it later.") && router.delete(`/admin/bookings/${booking.reference}`)} className="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-600">
            <Archive className="h-4 w-4" /> Archive
          </button>
          {booking.feedbackUrl && (
            <a
              href={`https://wa.me/${whatsappNumber}?text=${feedbackMessage}`}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-2 rounded-full bg-[var(--gold)] px-5 py-2.5 text-sm font-bold text-slate-950"
            >
              <Star className="h-4 w-4" /> Request feedback
            </a>
          )}
          <a
            href={`https://wa.me/${whatsappNumber}?text=${whatsappMessage}`}
            target="_blank"
            rel="noopener noreferrer"
            className="inline-flex items-center gap-2 rounded-full bg-[#25D366] px-5 py-2.5 text-sm font-bold text-white"
          >
            <MessageCircle className="h-4 w-4" /> WhatsApp guest
          </a>
          {booking.email && (
            <a href={`mailto:${booking.email}?subject=${encodeURIComponent(`Booking ${booking.reference}`)}`} className="inline-flex items-center gap-2 rounded-full border bg-white px-5 py-2.5 text-sm font-bold">
              <Mail className="h-4 w-4" /> Email
            </a>
          )}
        </div>
      </div>

      <div className="mt-7 grid gap-6 lg:grid-cols-[minmax(0,1fr)_21rem]">
        <div className="grid gap-6">
          <section className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div className="grid sm:grid-cols-[11rem_minmax(0,1fr)]">
              {booking.tour.image
                ? <img src={booking.tour.image} alt={booking.tour.title} className="h-40 w-full object-cover sm:h-full" />
                : <div className="hidden bg-slate-100 sm:block" />}
              <div className="p-5 md:p-7">
            <h2 className="font-display text-xl font-bold">Trip details</h2>
            <dl className="mt-5 grid gap-5 text-sm sm:grid-cols-2">
              <Detail label="Tour"><Link href={`/tours/${booking.tour.slug}`} className="font-semibold text-[var(--ocean)]">{booking.tour.title}</Link></Detail>
              <Detail label="Travel date"><span className="inline-flex items-center gap-1.5"><CalendarDays className="h-4 w-4 text-slate-400" />{booking.travelDate}</span></Detail>
              <Detail label="Booking option"><span>{booking.bookingStyleLabel || (booking.bookingType === "shared" ? "Shared tour" : "Private arrangement")}</span></Detail>
              <Detail label="Guests"><span className="inline-flex items-center gap-1.5"><Users className="h-4 w-4 text-slate-400" />{booking.adults} adults, {booking.children} children{booking.childrenAges.length ? ` (ages ${booking.childrenAges.join(", ")})` : ""}</span></Detail>
              <Detail label="Website estimate">{estimatedPrice}</Detail>
              <Detail label="Final price">{quotedPrice}</Detail>
              <Detail label="Pickup area"><span className="capitalize">{booking.pickupZone?.replaceAll("_", " ") || "Not provided"}</span></Detail>
              <Detail label="Hotel / pickup">{booking.hotel || "Not provided"}</Detail>
              <Detail label="Preferred contact"><span className="capitalize">{booking.preferredContact}</span></Detail>
              <Detail label="WhatsApp">{booking.whatsapp}</Detail>
              <Detail label="Email">{booking.email || "Not provided"}</Detail>
            </dl>
            {booking.specialRequests && (
              <div className="mt-6 rounded-xl bg-slate-50 p-4">
                <p className="text-xs font-bold uppercase tracking-wider text-slate-500">Special requests</p>
                <p className="mt-2 whitespace-pre-wrap text-sm leading-6 text-slate-700">{booking.specialRequests}</p>
              </div>
            )}
              </div>
            </div>
          </section>

          <section className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm md:p-7">
            <h2 className="font-display text-xl font-bold">Booking timeline</h2>
            <div className="mt-5 grid gap-3 text-sm">
              <Timeline label="Request received" value={booking.createdAt} />
              <Timeline label="Guest contacted" value={booking.contactedAt} />
              <Timeline label="Booking confirmed" value={booking.confirmedAt} />
              <Timeline label="Booking cancelled" value={booking.cancelledAt} />
            </div>
          </section>
        </div>

        <aside>
          <form onSubmit={save} className="sticky top-24 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 className="font-display text-xl font-bold">Manage booking</h2>
            {booking.invoiceNumber && <div className="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-xs text-emerald-900"><p className="font-bold">{booking.invoiceNumber}</p><p className="mt-1">{booking.confirmationSentAt ? `Email sent ${booking.confirmationSentAt}` : "Confirmation created. Use WhatsApp if no email was sent."}</p></div>}
            <label className="mt-5 grid gap-1.5">
              <span className="text-xs font-bold uppercase tracking-wider text-slate-500">Status</span>
              <select value={data.status} onChange={(event) => setData("status", event.target.value)} className="rounded-xl border px-4 py-3 text-sm font-semibold capitalize">
                {statuses.map((status) => <option key={status} value={status}>{status}</option>)}
              </select>
              {errors.status && <span className="text-xs text-red-600">{errors.status}</span>}
            </label>
            <div className="mt-5 grid grid-cols-[1fr_6rem] gap-2">
              <label className="grid gap-1.5"><span className="flex items-center justify-between gap-2 text-xs font-bold uppercase tracking-wider text-slate-500"><span>Final quote</span>{booking.estimatedPrice !== null && <button type="button" onClick={() => { setData("quoted_price", booking.estimatedPrice!.toString()); if (booking.estimatedCurrency) setData("quoted_currency", booking.estimatedCurrency); }} className="normal-case tracking-normal text-[var(--ocean)] hover:underline">Use estimate</button>}</span><input type="number" min="0" step="0.01" value={data.quoted_price} onChange={(event) => setData("quoted_price", event.target.value)} placeholder="e.g. 150" className="rounded-xl border px-4 py-3 text-sm" /></label>
              <label className="grid gap-1.5"><span className="text-xs font-bold uppercase tracking-wider text-slate-500">Currency</span><select value={data.quoted_currency} onChange={(event) => setData("quoted_currency", event.target.value)} className="rounded-xl border px-2 py-3 text-sm">{["USD","TZS","EUR","GBP"].map((currency) => <option key={currency}>{currency}</option>)}</select></label>
            </div>
            {(errors.quoted_price || errors.quoted_currency) && <span className="mt-1 block text-xs text-red-600">{errors.quoted_price || errors.quoted_currency}</span>}
            <div className="mt-5 grid grid-cols-2 gap-2">
              <label className="grid gap-1.5"><span className="text-xs font-bold uppercase tracking-wider text-slate-500">Deposit</span><input type="number" min="0" step="0.01" value={data.deposit_amount} onChange={(event) => setData("deposit_amount", event.target.value)} placeholder="Optional" className="rounded-xl border px-3 py-3 text-sm" /></label>
              <label className="grid gap-1.5"><span className="text-xs font-bold uppercase tracking-wider text-slate-500">Amount paid</span><input type="number" min="0" step="0.01" value={data.amount_paid} onChange={(event) => setData("amount_paid", event.target.value)} className="rounded-xl border px-3 py-3 text-sm" /></label>
            </div>
            {(errors.deposit_amount || errors.amount_paid) && <span className="mt-1 block text-xs text-red-600">{errors.deposit_amount || errors.amount_paid}</span>}
            <label className="mt-5 grid gap-1.5">
              <span className="text-xs font-bold uppercase tracking-wider text-slate-500">Payment status</span>
              <select value={data.payment_status} onChange={(event) => setData("payment_status", event.target.value)} className="rounded-xl border px-4 py-3 text-sm font-semibold capitalize">
                {["unpaid", "partially_paid", "paid", "refunded"].map((status) => <option key={status} value={status}>{status.replaceAll("_", " ")}</option>)}
              </select>
              {errors.payment_status && <span className="text-xs text-red-600">{errors.payment_status}</span>}
            </label>
            <label className="mt-5 grid gap-1.5">
              <span className="text-xs font-bold uppercase tracking-wider text-slate-500">Internal notes</span>
              <textarea
                rows={7}
                value={data.admin_notes}
                onChange={(event) => setData("admin_notes", event.target.value)}
                placeholder="Private notes for the team"
                className="rounded-xl border px-4 py-3 text-sm outline-none focus:border-[var(--turquoise)]"
              />
              {errors.admin_notes && <span className="text-xs text-red-600">{errors.admin_notes}</span>}
            </label>
            <button disabled={processing} className="mt-5 w-full rounded-full bg-[var(--ocean)] px-5 py-3 font-bold text-white disabled:opacity-60">
              {processing ? "Saving…" : "Save booking"}
            </button>
            {booking.status !== "completed" && booking.status !== "cancelled" && <button type="button" disabled={processing} onClick={confirmBooking} className="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-full bg-[var(--gold)] px-5 py-3 font-bold text-slate-950 disabled:opacity-60">
              {booking.invoiceNumber ? <><Send className="h-4 w-4" /> {booking.email ? "Resend confirmation" : "Refresh confirmation link"}</> : <><CheckCircle2 className="h-4 w-4" /> {booking.email ? "Confirm & email" : "Confirm & create link"}</>}
            </button>}
          </form>
        </aside>
      </div>
    </AdminLayout>
  );
}

function Detail({ label, children }: { label: string; children: React.ReactNode }) {
  return <div><dt className="text-xs font-bold uppercase tracking-wider text-slate-400">{label}</dt><dd className="mt-1 text-slate-800">{children}</dd></div>;
}

function Timeline({ label, value }: { label: string; value: string | null }) {
  return <div className="flex justify-between gap-4 border-b border-slate-100 pb-3"><span className="text-slate-600">{label}</span><strong className={value ? "text-slate-900" : "text-slate-300"}>{value || "—"}</strong></div>;
}
