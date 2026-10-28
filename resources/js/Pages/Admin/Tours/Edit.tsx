import { FormEvent } from "react";
import { Link, useForm } from "@inertiajs/react";
import { ArrowLeft, ExternalLink, Save } from "lucide-react";
import { AdminLayout } from "@/Components/AdminLayout";
import { AdminTourContentEditor } from "@/Components/AdminTourContentEditor";
import { AdminTourGalleryEditor } from "@/Components/AdminTourGalleryEditor";

type TourForm = {
  tour_category_id: number;
  experience_type: string;
  title: string;
  slug: string;
  excerpt: string;
  description: string;
  duration: string;
  location: string;
  price: number;
  shared_available: boolean;
  shared_adult_price: number | "";
  shared_child_price: number | "";
  shared_infant_price: number | "";
  private_available: boolean;
  private_pricing_model: "group_total" | "per_person_tiered" | "quote_only";
  private_1_person_price: number | "";
  private_2_person_price: number | "";
  private_3_person_price: number | "";
  private_4_plus_person_price: number | "";
  private_child_price: number | "";
  private_infant_price: number | "";
  private_max_online_guests: number;
  private_1_2_price: number | "";
  private_3_4_price: number | "";
  private_5_6_price: number | "";
  private_7_10_price: number | "";
  private_child_supplement: number | "";
  large_group_quote: boolean;
  pricing_note: string;
  currency: string;
  minimum_age: number | "";
  difficulty: string;
  pickup_details: string;
  meta_title: string;
  meta_description: string;
  cover_image: File | null;
  is_featured: boolean;
  is_popular: boolean;
  is_published: boolean;
};

type Props = {
  tour: TourForm & {
    id: number;
    cover_image_path: string | null;
    highlights: string[];
    included: string[];
    not_included: string[];
    itinerary: Array<{ time: string | null; title: string; description: string | null }>;
    faqs: Array<{ question: string; answer: string }>;
    images: Array<{ id: number; path: string; alt_text: string | null; sort_order: number }>;
  };
  categories: Array<{ id: number; name: string }>;
};

export default function AdminTourEdit({ tour, categories }: Props) {
  const { data, setData, post, processing, errors } = useForm<TourForm>({
    tour_category_id: tour.tour_category_id,
    experience_type: tour.experience_type,
    title: tour.title,
    slug: tour.slug,
    excerpt: tour.excerpt,
    description: tour.description,
    duration: tour.duration,
    location: tour.location,
    price: tour.price,
    shared_available: tour.shared_available,
    shared_adult_price: tour.shared_adult_price ?? "",
    shared_child_price: tour.shared_child_price ?? "",
    shared_infant_price: tour.shared_infant_price ?? "",
    private_available: tour.private_available,
    private_pricing_model: tour.private_pricing_model ?? "group_total",
    private_1_person_price: tour.private_1_person_price ?? "",
    private_2_person_price: tour.private_2_person_price ?? "",
    private_3_person_price: tour.private_3_person_price ?? "",
    private_4_plus_person_price: tour.private_4_plus_person_price ?? "",
    private_child_price: tour.private_child_price ?? "",
    private_infant_price: tour.private_infant_price ?? "",
    private_max_online_guests: tour.private_max_online_guests ?? 10,
    private_1_2_price: tour.private_1_2_price ?? "",
    private_3_4_price: tour.private_3_4_price ?? "",
    private_5_6_price: tour.private_5_6_price ?? "",
    private_7_10_price: tour.private_7_10_price ?? "",
    private_child_supplement: tour.private_child_supplement ?? "",
    large_group_quote: tour.large_group_quote,
    pricing_note: tour.pricing_note ?? "",
    currency: tour.currency,
    minimum_age: tour.minimum_age ?? "",
    difficulty: tour.difficulty ?? "",
    pickup_details: tour.pickup_details ?? "",
    meta_title: tour.meta_title ?? "",
    meta_description: tour.meta_description ?? "",
    cover_image: null,
    is_featured: tour.is_featured,
    is_popular: tour.is_popular,
    is_published: tour.is_published,
  });

  function submit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    post(`/admin/tours/${tour.slug}`, { forceFormData: true, preserveScroll: true });
  }

  const isSafari = tour.experience_type === "safari";

  return (
    <AdminLayout title={`Edit ${tour.title}`}>
      <Link href={`/admin/tours?type=${isSafari ? "safari" : "tour"}`} className="inline-flex items-center gap-1.5 text-sm font-semibold text-[var(--ocean)]"><ArrowLeft className="h-4 w-4" /> All {isSafari ? "safaris" : "tours"}</Link>
      <div className="mt-3 flex flex-wrap items-end justify-between gap-4">
        <div>
          <p className="text-xs font-bold uppercase tracking-[0.18em] text-[var(--turquoise)]">Content editor</p>
          <h1 className="mt-1 font-display text-3xl font-bold md:text-4xl">{tour.title}</h1>
          <p className="mt-1 text-sm text-slate-500">Update {isSafari ? "safari" : "tour"} information, pricing, visibility and media.</p>
        </div>
        <a href={`/tours/${tour.slug}`} target="_blank" rel="noopener noreferrer" className="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700 shadow-sm">
          Preview page <ExternalLink className="h-4 w-4" />
        </a>
      </div>

      <form onSubmit={submit} className="mt-6 grid gap-6">
        <section className="grid gap-5 rounded-2xl border border-slate-200 bg-white p-5 md:p-7">
          <div className="grid gap-5 md:grid-cols-2">
            <Field label="Title" error={errors.title}><input value={data.title} onChange={(e) => setData("title", e.target.value)} className={input} /></Field>
            <Field label="Slug" error={errors.slug}><input value={data.slug} onChange={(e) => setData("slug", e.target.value)} className={input} /></Field>
            <Field label="Category" error={errors.tour_category_id}>
              <select value={data.tour_category_id} onChange={(e) => setData("tour_category_id", Number(e.target.value))} className={input}>
                {categories.map((category) => <option key={category.id} value={category.id}>{category.name}</option>)}
              </select>
            </Field>
            <Field label="Experience type" error={errors.experience_type}>
              <select value={data.experience_type} onChange={(e) => setData("experience_type", e.target.value)} className={input}>
                <option value="zanzibar_tour">Zanzibar tour</option><option value="water_sport">Water sport</option><option value="safari">Safari</option>
              </select>
            </Field>
          </div>
          <Field label="Short description" error={errors.excerpt}><textarea rows={3} value={data.excerpt} onChange={(e) => setData("excerpt", e.target.value)} className={input} /></Field>
          <Field label="Full description" error={errors.description}><textarea rows={6} value={data.description} onChange={(e) => setData("description", e.target.value)} className={input} /></Field>
        </section>

        <section className="grid gap-5 rounded-2xl border border-slate-200 bg-white p-5 md:grid-cols-3 md:p-7">
          <Field label="Duration" error={errors.duration}><input value={data.duration} onChange={(e) => setData("duration", e.target.value)} className={input} /></Field>
          <Field label="Location" error={errors.location}><input value={data.location} onChange={(e) => setData("location", e.target.value)} className={input} /></Field>
          <Field label="Currency" error={errors.currency}>
            <select value={data.currency} onChange={(e) => setData("currency", e.target.value)} className={input}><option>USD</option><option>TZS</option><option>EUR</option></select>
          </Field>
          <Field label="Minimum age" error={errors.minimum_age}><input type="number" min="0" value={data.minimum_age} onChange={(e) => setData("minimum_age", e.target.value ? Number(e.target.value) : "")} className={input} /></Field>
          <Field label="Difficulty" error={errors.difficulty}>
            <select value={data.difficulty} onChange={(e) => setData("difficulty", e.target.value)} className={input}><option value="">Not applicable</option><option>Easy</option><option>Moderate</option><option>Advanced</option><option>Varies</option></select>
          </Field>
          <div className="md:col-span-3"><Field label="Pickup details" error={errors.pickup_details}><textarea rows={3} value={data.pickup_details} onChange={(e) => setData("pickup_details", e.target.value)} className={input} /></Field></div>
        </section>

        <section className="grid gap-6 rounded-2xl border border-slate-200 bg-white p-5 md:p-7">
          <div><p className="text-xs font-bold uppercase tracking-[0.18em] text-[var(--turquoise)]">Booking form pricing</p><h2 className="mt-1 font-display text-2xl font-bold">How guests can book this tour</h2><p className="mt-1 text-sm text-slate-500">These options use the same wording travellers see in the booking form. Enable only the choices you actually offer. All amounts below are in <strong>{data.currency}</strong>.</p></div>
          <div className="rounded-2xl border border-sky-200 bg-sky-50 p-4"><p className="text-[10px] font-bold uppercase tracking-wider text-sky-700">Customer view</p><div className="mt-2 flex flex-wrap gap-2">{data.shared_available && <span className="rounded-full bg-white px-3 py-1.5 text-xs font-bold text-[var(--ocean)] shadow-sm">Join a group{data.shared_adult_price !== "" ? ` · from ${data.currency} ${data.shared_adult_price} / adult` : " · price missing"}</span>}{data.private_available && <span className="rounded-full bg-white px-3 py-1.5 text-xs font-bold text-[var(--ocean)] shadow-sm">Only my group · {data.private_pricing_model === "per_person_tiered" ? "per-person tiers" : data.private_pricing_model === "group_total" ? "group price" : "custom quote"}</span>}{!data.shared_available && !data.private_available && <span className="text-xs font-semibold text-rose-700">Enable at least one booking option.</span>}</div><p className="mt-2 text-[11px] text-sky-800">If only one option is enabled, customers will not be asked to choose—it is selected automatically.</p></div>
          <div className="rounded-2xl border border-slate-200 p-5">
            <label className="flex items-center justify-between gap-4"><span><strong className="block">Join a group</strong><span className="text-xs text-slate-500">Customer joins other travellers · prices are per person</span></span><input type="checkbox" checked={data.shared_available} onChange={(e) => setData("shared_available", e.target.checked)} className="h-5 w-5" /></label>
            {data.shared_available && <div className="mt-5 grid gap-4 sm:grid-cols-3">
              <PriceField label="Adult price" value={data.shared_adult_price} error={errors.shared_adult_price} onChange={(value) => setData("shared_adult_price", value)} />
              <PriceField label="Child price (3–12)" value={data.shared_child_price} error={errors.shared_child_price} onChange={(value) => setData("shared_child_price", value)} />
              <PriceField label="Infant price (0–2)" value={data.shared_infant_price} error={errors.shared_infant_price} onChange={(value) => setData("shared_infant_price", value)} />
            </div>}
          </div>
          <div className="rounded-2xl border border-slate-200 p-5">
            <label className="flex items-center justify-between gap-4"><span><strong className="block">Only my group</strong><span className="text-xs text-slate-500">Private experience with clear, flexible pricing</span></span><input type="checkbox" checked={data.private_available} onChange={(e) => setData("private_available", e.target.checked)} className="h-5 w-5" /></label>
            {data.private_available && <><Field label="Private pricing method" error={errors.private_pricing_model}><select value={data.private_pricing_model} onChange={(e) => setData("private_pricing_model", e.target.value as TourForm["private_pricing_model"])} className={`${input} mt-5`}><option value="per_person_tiered">Per person by adult group size (recommended)</option><option value="group_total">One total price by total group size (legacy)</option><option value="quote_only">Custom quote only</option></select></Field>
            {data.private_pricing_model === "per_person_tiered" && <><p className="mt-4 text-xs text-slate-500">Like the excursion sheet: select the adult rate from the number of adults, then add children and infants separately.</p><div className="mt-3 grid gap-4 sm:grid-cols-2 lg:grid-cols-4"><PriceField label="1 adult · per person" value={data.private_1_person_price} error={errors.private_1_person_price} onChange={(value) => setData("private_1_person_price", value)} /><PriceField label="2 adults · per person" value={data.private_2_person_price} error={errors.private_2_person_price} onChange={(value) => setData("private_2_person_price", value)} /><PriceField label="3 adults · per person" value={data.private_3_person_price} error={errors.private_3_person_price} onChange={(value) => setData("private_3_person_price", value)} /><PriceField label="4+ adults · per person" value={data.private_4_plus_person_price} error={errors.private_4_plus_person_price} onChange={(value) => setData("private_4_plus_person_price", value)} /><PriceField label="Child 3–12 · per child" value={data.private_child_price} error={errors.private_child_price} onChange={(value) => setData("private_child_price", value)} /><PriceField label="Infant 0–2" value={data.private_infant_price} error={errors.private_infant_price} onChange={(value) => setData("private_infant_price", value)} /><Field label="Maximum guests online" error={errors.private_max_online_guests}><input type="number" min="4" max="100" value={data.private_max_online_guests} onChange={(e) => setData("private_max_online_guests", Number(e.target.value))} className={input} /></Field></div></>}
            {data.private_pricing_model === "group_total" && <div className="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
              <PriceField label="1–2 guests" value={data.private_1_2_price} error={errors.private_1_2_price} onChange={(value) => setData("private_1_2_price", value)} />
              <PriceField label="3–4 guests" value={data.private_3_4_price} error={errors.private_3_4_price} onChange={(value) => setData("private_3_4_price", value)} />
              <PriceField label="5–6 guests" value={data.private_5_6_price} error={errors.private_5_6_price} onChange={(value) => setData("private_5_6_price", value)} />
              <PriceField label="7–10 guests" value={data.private_7_10_price} error={errors.private_7_10_price} onChange={(value) => setData("private_7_10_price", value)} />
            </div>}{data.private_pricing_model === "group_total" && <div className="mt-4"><PriceField label="Additional child fee (optional)" value={data.private_child_supplement} error={errors.private_child_supplement} onChange={(value) => setData("private_child_supplement", value)} /></div>}<label className="mt-4 flex min-h-12 items-center gap-2 rounded-xl bg-slate-50 px-4 text-sm font-semibold"><input type="checkbox" checked={data.large_group_quote} onChange={(e) => setData("large_group_quote", e.target.checked)} /> Request a quote above the online guest limit</label></>}
          </div>
          <Field label="Public pricing note" error={errors.pricing_note}><textarea rows={2} value={data.pricing_note} onChange={(e) => setData("pricing_note", e.target.value)} className={input} placeholder="e.g. Final price depends on pickup location and is confirmed before payment." /></Field>
        </section>

        <section className="grid gap-5 rounded-2xl border border-slate-200 bg-white p-5 md:p-7">
          {tour.cover_image_path && <img src={tour.cover_image_path} alt="" className="aspect-video max-w-md rounded-xl object-cover" />}
          <Field label="Replace cover image" error={errors.cover_image}><input type="file" accept="image/jpeg,image/png,image/webp" onChange={(e) => setData("cover_image", e.target.files?.[0] ?? null)} className={input} /></Field>
          <div className="flex flex-wrap gap-5">
            {[
              ["is_published", "Published"],
              ["is_popular", "Popular"],
              ["is_featured", isSafari ? "Show in Safari Ideas" : "Featured"],
            ].map(([key, label]) => (
              <label key={key} className="flex items-center gap-2 text-sm font-semibold">
                <input type="checkbox" checked={data[key as "is_published" | "is_popular" | "is_featured"]} onChange={(e) => setData(key as "is_published" | "is_popular" | "is_featured", e.target.checked)} />
                {label}
              </label>
            ))}
          </div>
        </section>

        <section className="grid gap-5 rounded-2xl border border-slate-200 bg-white p-5 md:p-7">
          <Field label="SEO title" error={errors.meta_title}><input value={data.meta_title} onChange={(e) => setData("meta_title", e.target.value)} className={input} /></Field>
          <Field label="SEO description" error={errors.meta_description}><textarea rows={3} value={data.meta_description} onChange={(e) => setData("meta_description", e.target.value)} className={input} /></Field>
        </section>

        <div className="sticky bottom-4 z-20 flex items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white/95 p-3 shadow-lg backdrop-blur">
          <p className="hidden pl-2 text-xs text-slate-500 sm:block">Save before editing structured content or gallery below.</p>
          <button disabled={processing} className="ml-auto inline-flex items-center gap-2 rounded-xl bg-[var(--ocean)] px-6 py-3 font-bold text-white disabled:opacity-60">
            <Save className="h-4 w-4" /> {processing ? "Saving…" : "Save changes"}
          </button>
        </div>
      </form>

      <div className="my-10 h-px bg-slate-200" />
      <AdminTourContentEditor
        tourSlug={tour.slug}
        highlights={tour.highlights}
        included={tour.included}
        notIncluded={tour.not_included}
        itinerary={tour.itinerary}
        faqs={tour.faqs}
      />

      <div className="my-10 h-px bg-slate-200" />
      <AdminTourGalleryEditor tourSlug={tour.slug} images={tour.images} />
    </AdminLayout>
  );
}

const input = "w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-[var(--turquoise)]";
function PriceField({ label, value, error, onChange }: { label: string; value: number | ""; error?: string; onChange: (value: number | "") => void }) { return <Field label={label} error={error}><input type="number" min="0" step="0.01" value={value} onChange={(e) => onChange(e.target.value === "" ? "" : Number(e.target.value))} className={input} /></Field>; }

function Field({ label, error, children }: { label: string; error?: string; children: React.ReactNode }) {
  return <label className="grid gap-1.5"><span className="text-xs font-bold uppercase tracking-wider text-slate-600">{label}</span>{children}{error && <span className="text-xs text-red-600">{error}</span>}</label>;
}
