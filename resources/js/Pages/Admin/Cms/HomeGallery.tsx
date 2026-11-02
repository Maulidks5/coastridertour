import { FormEvent, useState } from "react";
import { router, useForm } from "@inertiajs/react";
import { Eye, EyeOff, ImagePlus, Save, Trash2, UploadCloud } from "lucide-react";
import { AdminLayout } from "@/Components/AdminLayout";
import { AdminPageHeader } from "@/Components/AdminUi";

type TourOption = { id: number; title: string; slug: string };
type GalleryImage = { id: number; path: string; alt_text: string | null; tour_id: number | null; is_active: boolean };
type Props = { images: GalleryImage[]; tours: TourOption[] };

export default function HomeGallery({ images, tours }: Props) {
  const upload = useForm({ images: [] as File[] });

  function submit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    upload.post("/admin/cms/home-gallery", { forceFormData: true, preserveScroll: true, onSuccess: () => upload.reset("images") });
  }

  return (
    <AdminLayout title="Homepage gallery">
      <AdminPageHeader eyebrow="Homepage visual" title="Travel gallery" description="Upload real Coast Rider moments for the homepage. New uploads always display first; use 6–8 strong landscape or portrait images." />
      <form onSubmit={submit} className="mt-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div className="grid gap-5 p-5 sm:p-7 lg:grid-cols-[1fr_auto] lg:items-center">
          <div><p className="font-display text-xl font-bold">Add gallery images</p><p className="mt-1 text-sm leading-6 text-slate-500">Upload up to 12 JPEG, PNG or WebP images at once (15 MB each). Images are resized and converted to WebP automatically.</p></div>
          <label className="inline-flex cursor-pointer items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-3 text-sm font-bold text-[var(--ocean)]"><ImagePlus className="h-4 w-4" />{upload.data.images.length ? `${upload.data.images.length} selected` : "Choose images"}<input type="file" multiple accept="image/jpeg,image/png,image/webp" className="sr-only" onChange={(event) => upload.setData("images", Array.from(event.target.files ?? []))} /></label>
        </div>
        {upload.errors.images && <p className="px-5 pb-4 text-sm text-rose-600 sm:px-7">{upload.errors.images}</p>}
        <div className="border-t border-slate-200 bg-slate-50 px-5 py-4 text-right sm:px-7"><button disabled={upload.processing || !upload.data.images.length} className="inline-flex items-center gap-2 rounded-xl bg-[var(--ocean)] px-5 py-3 text-sm font-bold text-white disabled:opacity-50"><UploadCloud className="h-4 w-4" />{upload.processing ? "Uploading…" : "Upload to gallery"}</button></div>
      </form>

      <div className="mt-6 grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
        {images.map((image) => <GalleryCard key={image.id} image={image} tours={tours} />)}
      </div>
      {!images.length && <div className="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-6 py-12 text-center"><ImagePlus className="mx-auto h-9 w-9 text-slate-400" /><p className="mt-3 font-display text-xl font-bold">Your gallery is ready for its first moments</p><p className="mt-1 text-sm text-slate-500">Start with the best Zanzibar, safari, guest and vehicle photos.</p></div>}
    </AdminLayout>
  );
}

function GalleryCard({ image, tours }: { image: GalleryImage; tours: TourOption[] }) {
  const form = useForm({ alt_text: image.alt_text ?? "", tour_id: image.tour_id?.toString() ?? "", is_active: image.is_active });
  const [editing, setEditing] = useState(false);
  function save() { form.patch(`/admin/cms/home-gallery/${image.id}`, { preserveScroll: true, onSuccess: () => setEditing(false) }); }
  return <article className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"><img src={image.path} alt={image.alt_text ?? ""} className="aspect-[4/3] w-full object-cover" />
    <div className="p-4"><div className="flex items-center justify-between gap-3"><span className={`rounded-full px-2.5 py-1 text-xs font-bold ${form.data.is_active ? "bg-emerald-50 text-emerald-700" : "bg-slate-100 text-slate-500"}`}>{form.data.is_active ? "Visible" : "Hidden"}</span><div className="flex gap-1"><button type="button" onClick={() => { form.setData("is_active", !form.data.is_active); form.patch(`/admin/cms/home-gallery/${image.id}`, { preserveScroll: true }); }} title="Toggle visibility" className="grid h-9 w-9 place-items-center rounded-lg text-slate-500 hover:bg-slate-100">{form.data.is_active ? <Eye className="h-4 w-4" /> : <EyeOff className="h-4 w-4" />}</button><button type="button" onClick={() => { if (confirm("Remove this image from the homepage gallery?")) router.delete(`/admin/cms/home-gallery/${image.id}`, { preserveScroll: true }); }} title="Remove image" className="grid h-9 w-9 place-items-center rounded-lg text-rose-500 hover:bg-rose-50"><Trash2 className="h-4 w-4" /></button></div></div>
      <button type="button" onClick={() => setEditing(!editing)} className="mt-3 text-xs font-bold text-[var(--ocean)]">{editing ? "Close editor" : "Edit details & tour link"}</button>
      {editing && <div className="mt-3 grid gap-3 border-t border-slate-100 pt-3"><label className="grid gap-1 text-xs font-bold text-slate-600">Image description<input value={form.data.alt_text} onChange={(event) => form.setData("alt_text", event.target.value)} className="rounded-lg border border-slate-200 px-3 py-2 text-sm font-normal outline-none focus:border-[var(--turquoise)]" /></label><label className="grid gap-1 text-xs font-bold text-slate-600">Related tour<select value={form.data.tour_id} onChange={(event) => form.setData("tour_id", event.target.value)} className="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-normal outline-none focus:border-[var(--turquoise)]"><option value="">No tour link</option>{tours.map((tour) => <option key={tour.id} value={tour.id}>{tour.title}</option>)}</select></label><p className="text-xs leading-5 text-slate-500">When a customer opens this image, they can go directly to this tour.</p><button type="button" disabled={form.processing} onClick={save} className="inline-flex items-center justify-center gap-2 rounded-lg bg-[var(--ocean)] px-3 py-2.5 text-sm font-bold text-white"><Save className="h-4 w-4" /> Save</button></div>}
    </div></article>;
}
