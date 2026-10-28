import { Link } from "@inertiajs/react";
import { useMemo, useState } from "react";
import { AdminLayout } from "@/Components/AdminLayout";
import { AdminEmptyState, AdminPageHeader, AdminTableShell, adminTableCellClass, adminTableClass, adminTableHeadClass, adminTableHeaderCellClass, adminTableRowClass } from "@/Components/AdminUi";
import { ExternalLink, Pencil, Plus, Search, Sparkles } from "lucide-react";
import { tourPriceSummary } from "@/lib/tourPricing";
import type { TourPricing } from "@/types/tour";

type Props = {
  catalogue: "tour" | "safari";
  tours: Array<{
    slug: string;
    title: string;
    category: string;
    type: string;
    price: number;
    currency: string;
    pricing: TourPricing;
    isPublished: boolean;
    isPopular: boolean;
  }>;
};

export default function AdminTours({ tours, catalogue }: Props) {
  const isSafari = catalogue === "safari";
  const [query, setQuery] = useState("");
  const filteredTours = useMemo(() => {
    const term = query.trim().toLowerCase();
    return term
      ? tours.filter((tour) => `${tour.title} ${tour.category} ${tour.type}`.toLowerCase().includes(term))
      : tours;
  }, [query, tours]);
  const publishedCount = tours.filter((tour) => tour.isPublished).length;

  return (
    <AdminLayout title={isSafari ? "Safaris" : "Tours"}>
      <AdminPageHeader eyebrow="Website catalogue" title={isSafari ? "Safari management" : "Tour management"} description={isSafari ? "Manage safari packages, starting prices, images and visibility." : "Manage Zanzibar tours, activities, pricing and visibility."} actions={<>
          <a href={isSafari ? "/safaris" : "/tours"} target="_blank" rel="noopener noreferrer" className="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm hover:border-slate-300">View {isSafari ? "safaris" : "tours"} <ExternalLink className="h-4 w-4" /></a>
          <Link href={`/admin/tours/create?type=${catalogue}`} className="inline-flex items-center gap-2 rounded-xl bg-[var(--ocean)] px-4 py-2.5 text-sm font-bold text-white shadow-sm"><Plus className="h-4 w-4" /> Add {isSafari ? "safari" : "tour"}</Link>
      </>} />

      <div className="mt-6 grid gap-3 sm:grid-cols-2">
        <div className="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
          <p className="text-xs font-bold uppercase tracking-wider text-slate-400">{isSafari ? "All safaris" : "All tours"}</p>
          <p className="mt-1 font-display text-3xl font-bold">{tours.length}</p>
        </div>
        <div className="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
          <p className="text-xs font-bold uppercase tracking-wider text-slate-400">Published</p>
          <p className="mt-1 font-display text-3xl font-bold text-emerald-700">{publishedCount}</p>
        </div>
      </div>

      <AdminTableShell toolbar={<>
          <label className="relative block w-full max-w-md">
            <Search className="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" />
            <input value={query} onChange={(event) => setQuery(event.target.value)} placeholder={isSafari ? "Search safaris or parks…" : "Search tours or categories…"} className="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-10 pr-4 text-sm outline-none focus:border-[var(--turquoise)] focus:bg-white" />
          </label>
          <span className="text-xs font-semibold text-slate-500">{filteredTours.length} results</span>
      </>}>
          {filteredTours.length ? <table className={adminTableClass}>
            <thead className={adminTableHeadClass}>
              <tr><th className={adminTableHeaderCellClass}>Service</th><th className={adminTableHeaderCellClass}>Category</th><th className={adminTableHeaderCellClass}>Price</th><th className={adminTableHeaderCellClass}>Visibility</th><th className={adminTableHeaderCellClass}><span className="sr-only">Action</span></th></tr>
            </thead>
            <tbody>
              {filteredTours.map((tour) => (
                <tr key={tour.slug} className={adminTableRowClass}>
                  <td className={adminTableCellClass}>
                    <p className="font-bold text-slate-900">{tour.title}</p>
                    <p className="mt-1 flex items-center gap-1 text-xs capitalize text-slate-500">{tour.type.replace("_", " ")}{tour.isPopular && <><span>·</span><Sparkles className="h-3 w-3 text-amber-500" /> Popular</>}</p>
                  </td>
                  <td className={`${adminTableCellClass} text-slate-600`}>{tour.category}</td>
                  <td className={adminTableCellClass}>{(() => { const summary = tourPriceSummary(tour.price, tour.currency, tour.pricing); return <><p className="font-bold text-slate-900">{summary.label}</p><p className="mt-1 text-xs text-slate-500">{summary.caption}</p></>; })()}</td>
                  <td className={adminTableCellClass}>
                    <span className={`rounded-full px-2.5 py-1 text-xs font-bold ${tour.isPublished ? "bg-emerald-100 text-emerald-700" : "bg-slate-100 text-slate-500"}`}>
                      {tour.isPublished ? "Published" : "Draft"}
                    </span>
                  </td>
                  <td className={`${adminTableCellClass} text-right`}>
                    <Link href={`/admin/tours/${tour.slug}/edit`} className="inline-flex items-center gap-1.5 rounded-lg border border-[var(--ocean)] px-3 py-2 text-xs font-bold text-[var(--ocean)] transition hover:bg-[var(--ocean)] hover:text-white"><Pencil className="h-3.5 w-3.5" /> Edit</Link>
                  </td>
                </tr>
              ))}
            </tbody>
          </table> : <AdminEmptyState icon={Search} title={`No service matches “${query}”`} description={`Try a different ${isSafari ? "safari" : "tour"} name or category.`} />}
      </AdminTableShell>
    </AdminLayout>
  );
}
