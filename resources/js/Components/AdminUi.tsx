import { Link } from "@inertiajs/react";
import { ReactNode } from "react";
import { LucideIcon } from "lucide-react";

export function AdminPageHeader({ eyebrow, title, description, actions }: { eyebrow: string; title: string; description: string; actions?: ReactNode }) {
  return (
    <div className="flex flex-wrap items-end justify-between gap-4">
      <div>
        <p className="text-xs font-bold uppercase tracking-[0.18em] text-[var(--turquoise)]">{eyebrow}</p>
        <h1 className="mt-1 font-display text-3xl font-bold text-slate-900 md:text-4xl">{title}</h1>
        <p className="mt-1 max-w-2xl text-sm text-slate-500">{description}</p>
      </div>
      {actions && <div className="flex flex-wrap gap-2">{actions}</div>}
    </div>
  );
}

export function AdminTableShell({ toolbar, children }: { toolbar?: ReactNode; children: ReactNode }) {
  return (
    <section className="mt-5 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      {toolbar && <div className="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 p-4">{toolbar}</div>}
      <div className="overflow-x-auto">{children}</div>
    </section>
  );
}

export const adminTableClass = "w-full min-w-[52rem] text-left text-sm";
export const adminTableHeadClass = "bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500";
export const adminTableHeaderCellClass = "px-5 py-3.5";
export const adminTableRowClass = "border-t border-slate-100 transition hover:bg-slate-50/70";
export const adminTableCellClass = "px-5 py-4 align-middle";

export function AdminFilterTabs({ items }: { items: Array<{ label: string; href: string; active: boolean }> }) {
  return (
    <nav className="mt-6 flex gap-1.5 overflow-x-auto rounded-2xl border border-slate-200 bg-white p-2 shadow-sm">
      {items.map((item) => (
        <Link key={item.href} href={item.href} className={`shrink-0 rounded-xl px-4 py-2 text-sm font-semibold transition ${item.active ? "bg-[var(--ocean)] text-white shadow-sm" : "text-slate-600 hover:bg-slate-50 hover:text-slate-900"}`}>
          {item.label}
        </Link>
      ))}
    </nav>
  );
}

export function AdminPagination({ links }: { links: Array<{ url: string | null; label: string; active: boolean }> }) {
  if (links.length <= 3) return null;
  return (
    <nav aria-label="Pagination" className="mt-6 flex flex-wrap justify-end gap-2">
      {links.map((link, index) => link.url ? (
        <Link key={index} href={link.url} preserveScroll className={`rounded-lg border px-3 py-2 text-sm font-semibold transition ${link.active ? "border-[var(--ocean)] bg-[var(--ocean)] text-white" : "border-slate-200 bg-white text-slate-600 hover:border-slate-300"}`} dangerouslySetInnerHTML={{ __html: link.label }} />
      ) : (
        <span key={index} className="rounded-lg border border-slate-100 bg-slate-50 px-3 py-2 text-sm text-slate-300" dangerouslySetInnerHTML={{ __html: link.label }} />
      ))}
    </nav>
  );
}

export function AdminEmptyState({ icon: Icon, title, description }: { icon: LucideIcon; title: string; description?: string }) {
  return (
    <div className="px-6 py-14 text-center">
      <span className="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-slate-100 text-slate-400"><Icon className="h-6 w-6" /></span>
      <p className="mt-3 font-bold text-slate-700">{title}</p>
      {description && <p className="mt-1 text-sm text-slate-500">{description}</p>}
    </div>
  );
}
