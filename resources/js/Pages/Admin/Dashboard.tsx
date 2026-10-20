import { AdminLayout } from "@/Components/AdminLayout";
import { AdminStatusBadge } from "@/Components/AdminStatusBadge";
import { Link } from "@inertiajs/react";
import { ArrowRight, BookOpen, CalendarCheck2, CalendarClock, ClipboardList, FileText, GalleryHorizontalEnd, Settings2 } from "lucide-react";

type Props = {
  stats: {
    publishedTours: number;
    pendingBookings: number;
    upcomingBookings: number;
    totalBookings: number;
  };
  recentBookings: Array<{
    reference: string;
    name: string;
    tour: string;
    travelDate: string;
    status: string;
  }>;
};

export default function AdminDashboard({ stats, recentBookings }: Props) {
  const cards = [
    { label: "Published tours", value: stats.publishedTours, icon: BookOpen, tone: "bg-sky-50 text-sky-700" },
    { label: "Pending requests", value: stats.pendingBookings, icon: CalendarClock, tone: "bg-amber-50 text-amber-700" },
    { label: "Upcoming trips", value: stats.upcomingBookings, icon: CalendarCheck2, tone: "bg-emerald-50 text-emerald-700" },
    { label: "All bookings", value: stats.totalBookings, icon: ClipboardList, tone: "bg-violet-50 text-violet-700" },
  ];
  const contentShortcuts = [
    { label: "Homepage hero", description: "Edit the cinematic hero image, heading and calls to action.", href: "/admin/cms/hero-slides", icon: GalleryHorizontalEnd },
    { label: "Tours & safaris", description: "Add services, prices, details and gallery images.", href: "/admin/tours", icon: BookOpen },
    { label: "Page content", description: "Edit Tours, Safaris, About and Contact headings.", href: "/admin/cms/settings?section=pages", icon: FileText },
    { label: "Site settings", description: "Update company, contact, social and SEO details.", href: "/admin/cms/settings?section=general", icon: Settings2 },
  ];

  return (
    <AdminLayout title="Dashboard">
      <div className="flex flex-wrap items-end justify-between gap-4">
        <div>
          <p className="text-xs font-bold uppercase tracking-[0.18em] text-[var(--turquoise)]">Overview</p>
          <h1 className="mt-1 font-display text-3xl font-bold text-slate-900 md:text-4xl">Good to see you</h1>
          <p className="mt-1 text-sm text-slate-500">Here is what is happening with Coast Rider Tour today.</p>
        </div>
        <Link href="/admin/bookings?status=pending" className="inline-flex items-center gap-2 rounded-xl bg-[var(--ocean)] px-4 py-2.5 text-sm font-bold text-white shadow-sm transition hover:brightness-110">
          Review requests <ArrowRight className="h-4 w-4" />
        </Link>
      </div>

      <div className="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        {cards.map(({ label, value, icon: Icon, tone }) => (
          <div key={label} className="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div className="flex items-start justify-between gap-3">
              <div>
                <p className="text-sm font-medium text-slate-500">{label}</p>
                <p className="mt-2 font-display text-4xl font-bold text-slate-900">{value}</p>
              </div>
              <span className={`grid h-11 w-11 place-items-center rounded-xl ${tone}`}><Icon className="h-5 w-5" /></span>
            </div>
          </div>
        ))}
      </div>

      <section className="mt-8">
        <div>
          <h2 className="font-display text-2xl font-bold">Manage website</h2>
          <p className="mt-1 text-sm text-slate-500">Quick access to the content your visitors see.</p>
        </div>
        <div className="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
          {contentShortcuts.map(({ label, description, href, icon: Icon }) => (
            <Link key={href} href={href} className="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-[var(--turquoise)] hover:shadow-md">
              <span className="grid h-10 w-10 place-items-center rounded-xl bg-[var(--sand)] text-[var(--ocean)]"><Icon className="h-5 w-5" /></span>
              <h3 className="mt-4 font-bold text-slate-900">{label}</h3>
              <p className="mt-1 text-xs leading-5 text-slate-500">{description}</p>
              <span className="mt-4 inline-flex items-center gap-1 text-xs font-bold text-[var(--ocean)]">Manage <ArrowRight className="h-3.5 w-3.5 transition group-hover:translate-x-0.5" /></span>
            </Link>
          ))}
        </div>
      </section>

      <section className="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div className="flex items-center justify-between gap-4 border-b border-slate-200 px-5 py-4">
          <div>
            <h2 className="font-display text-xl font-bold">Recent bookings</h2>
            <p className="mt-0.5 text-xs text-slate-500">Latest requests from your website</p>
          </div>
          <Link href="/admin/bookings" className="text-sm font-bold text-[var(--ocean)] hover:underline">View all</Link>
        </div>
        {recentBookings.length ? (
          <div className="overflow-x-auto">
            <table className="w-full text-left text-sm">
              <thead className="bg-slate-50 text-xs uppercase text-slate-500">
                <tr><th className="px-5 py-3">Reference</th><th className="px-5 py-3">Guest</th><th className="px-5 py-3">Tour</th><th className="px-5 py-3">Date</th><th className="px-5 py-3">Status</th></tr>
              </thead>
              <tbody>
                {recentBookings.map((booking) => (
                  <tr key={booking.reference} className="border-t border-slate-100 transition hover:bg-slate-50/70">
                    <td className="px-5 py-4"><Link href={`/admin/bookings/${booking.reference}`} className="font-bold text-[var(--ocean)] hover:underline">{booking.reference}</Link></td>
                    <td className="px-5 py-4">{booking.name}</td>
                    <td className="px-5 py-4">{booking.tour}</td>
                    <td className="px-5 py-4">{booking.travelDate}</td>
                    <td className="px-5 py-4"><AdminStatusBadge status={booking.status} /></td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        ) : <div className="px-5 py-12 text-center"><CalendarCheck2 className="mx-auto h-9 w-9 text-slate-300" /><p className="mt-3 text-sm font-semibold text-slate-600">No bookings yet</p><p className="mt-1 text-xs text-slate-400">New website requests will appear here.</p></div>}
      </section>
    </AdminLayout>
  );
}
