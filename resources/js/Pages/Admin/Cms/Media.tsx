import { FormEvent, useState } from "react";
import { useForm } from "@inertiajs/react";
import { ImagePlus, Save, UploadCloud, Video } from "lucide-react";
import { AdminLayout } from "@/Components/AdminLayout";
import { AdminPageHeader } from "@/Components/AdminUi";

type VideoSettings = {
  homepage_video_enabled: string;
  homepage_video_path: string;
  homepage_video_poster_path: string;
  homepage_video_title: string;
  homepage_video_description: string;
  homepage_video_primary_label: string;
  homepage_video_primary_url: string;
  homepage_video_secondary_label: string;
  homepage_video_secondary_url: string;
};
type Props = { media: { logo_path: string; cta_image_path: string; about_image_path: string } & Partial<VideoSettings> };

export default function CmsMedia({ media }: Props) {
  const { data, setData, post, processing, errors } = useForm({
    logo: null as File | null,
    cta_image: null as File | null,
    about_image: null as File | null,
    homepage_video: null as File | null,
    homepage_video_poster: null as File | null,
    homepage_video_enabled: media.homepage_video_enabled !== "0",
    homepage_video_title: media.homepage_video_title ?? "A glimpse of the wild, before you arrive",
    homepage_video_description: media.homepage_video_description ?? "From Zanzibar's coast to Tanzania's iconic parks, every journey is planned with local care and private comfort.",
    homepage_video_primary_label: media.homepage_video_primary_label ?? "Explore Tanzania safaris",
    homepage_video_primary_url: media.homepage_video_primary_url ?? "/safaris",
    homepage_video_secondary_label: media.homepage_video_secondary_label ?? "Explore Zanzibar tours",
    homepage_video_secondary_url: media.homepage_video_secondary_url ?? "/tours",
  });

  function submit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    post("/admin/cms/media", { forceFormData: true, preserveScroll: true });
  }

  return (
    <AdminLayout title="Website media">
      <AdminPageHeader eyebrow="Website content" title="Website media" description="Replace key images and manage the featured homepage video. Keep videos short and compressed for a fast website." />
      <form onSubmit={submit} className="mt-6 grid gap-5 lg:grid-cols-2">
        <MediaField title="Website logo" hint="Square or transparent image recommended." current={media.logo_path} field="logo" file={data.logo} error={errors.logo} onChange={(file) => setData("logo", file)} />
        <MediaField title="CTA background" hint="Landscape photo with a clear central subject." current={media.cta_image_path} field="cta_image" file={data.cta_image} error={errors.cta_image} onChange={(file) => setData("cta_image", file)} />
        <MediaField title="About page image" hint="A real team, guide, driver or guest experience is recommended." current={media.about_image_path} field="about_image" file={data.about_image} error={errors.about_image} onChange={(file) => setData("about_image", file)} />
        <section className="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
          <div className="flex flex-wrap items-center justify-between gap-4 border-b border-slate-200 px-5 py-5 sm:px-7">
            <div><p className="text-xs font-bold uppercase tracking-wider text-[var(--turquoise)]">Homepage section</p><h2 className="mt-1 font-display text-2xl font-bold">Featured video</h2><p className="mt-1 text-sm text-slate-500">Upload MP4 or WebM up to 50 MB. A 20–40 second video works best.</p></div>
            <label className="inline-flex cursor-pointer items-center gap-3 text-sm font-bold text-slate-700"><input type="checkbox" checked={data.homepage_video_enabled} onChange={(event) => setData("homepage_video_enabled", event.target.checked)} className="h-4 w-4 rounded border-slate-300 text-[var(--ocean)]" /> Show on homepage</label>
          </div>
          <div className="grid gap-5 p-5 sm:p-7 lg:grid-cols-2">
            <VideoField current={media.homepage_video_path ?? ""} file={data.homepage_video} error={errors.homepage_video} onChange={(file) => setData("homepage_video", file)} />
            <MediaField title="Video poster image" hint="The image displayed before a visitor's connection loads the video." current={media.homepage_video_poster_path ?? "/images/experiences/hero-safari.webp"} field="homepage_video_poster" file={data.homepage_video_poster} error={errors.homepage_video_poster} onChange={(file) => setData("homepage_video_poster", file)} />
            <TextField label="Heading" value={data.homepage_video_title} onChange={(value) => setData("homepage_video_title", value)} error={errors.homepage_video_title} />
            <TextField label="Description" textarea value={data.homepage_video_description} onChange={(value) => setData("homepage_video_description", value)} error={errors.homepage_video_description} />
            <TextField label="Primary button text" value={data.homepage_video_primary_label} onChange={(value) => setData("homepage_video_primary_label", value)} error={errors.homepage_video_primary_label} />
            <TextField label="Primary button link" value={data.homepage_video_primary_url} onChange={(value) => setData("homepage_video_primary_url", value)} error={errors.homepage_video_primary_url} />
            <TextField label="Secondary button text" value={data.homepage_video_secondary_label} onChange={(value) => setData("homepage_video_secondary_label", value)} error={errors.homepage_video_secondary_label} />
            <TextField label="Secondary button link" value={data.homepage_video_secondary_url} onChange={(value) => setData("homepage_video_secondary_url", value)} error={errors.homepage_video_secondary_url} />
          </div>
        </section>
        <div className="sticky bottom-4 z-20 flex justify-end rounded-2xl border border-slate-200 bg-white/95 p-3 shadow-lg backdrop-blur lg:col-span-2">
          <button disabled={processing} className="inline-flex items-center gap-2 rounded-xl bg-[var(--ocean)] px-6 py-3 text-sm font-bold text-white disabled:opacity-50"><Save className="h-4 w-4" />{processing ? "Saving…" : "Save media"}</button>
        </div>
      </form>
    </AdminLayout>
  );
}

function VideoField({ current, file, error, onChange }: { current: string; file: File | null; error?: string; onChange: (file: File | null) => void }) {
  const [preview, setPreview] = useState<string | null>(null);
  return (
    <label className="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div className="relative aspect-video bg-slate-950">
        {current || preview ? <video src={preview ?? current} muted controls className="h-full w-full object-cover" /> : <span className="grid h-full place-items-center text-slate-400"><Video className="h-10 w-10" /></span>}
        <span className="pointer-events-none absolute inset-0 grid place-items-center bg-slate-950/0 text-white opacity-0 transition group-hover:bg-slate-950/40 group-hover:opacity-100"><UploadCloud className="h-8 w-8" /></span>
      </div>
      <div className="p-5"><h2 className="font-display text-xl font-bold">Homepage video</h2><p className="mt-1 min-h-10 text-xs leading-5 text-slate-500">MP4 or WebM, maximum 50 MB. Keep it short, clear and without copyrighted music.</p><span className="mt-4 inline-flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-[var(--ocean)]"><Video className="h-4 w-4" />{file ? file.name : "Choose video"}</span><input type="file" accept="video/mp4,video/webm" className="sr-only" onChange={(event) => { const next = event.target.files?.[0] ?? null; onChange(next); if (next) setPreview(URL.createObjectURL(next)); }} />{error && <span className="mt-2 block text-xs text-rose-600">{error}</span>}</div>
    </label>
  );
}

function TextField({ label, value, textarea = false, error, onChange }: { label: string; value: string; textarea?: boolean; error?: string; onChange: (value: string) => void }) {
  return <label className="grid gap-1.5"><span className="text-xs font-bold uppercase tracking-wider text-slate-600">{label}</span>{textarea ? <textarea rows={3} value={value} onChange={(event) => onChange(event.target.value)} className="rounded-xl border border-slate-200 px-4 py-3 text-sm leading-6 outline-none focus:border-[var(--turquoise)]" /> : <input value={value} onChange={(event) => onChange(event.target.value)} className="rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-[var(--turquoise)]" />}{error && <span className="text-xs text-rose-600">{error}</span>}</label>;
}

function MediaField({ title, hint, current, field, file, error, onChange }: { title: string; hint: string; current: string; field: string; file: File | null; error?: string; onChange: (file: File | null) => void }) {
  const [preview, setPreview] = useState<string | null>(null);
  return (
    <label className="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
      <div className="relative aspect-[4/3] bg-slate-100">
        <img src={preview ?? current} alt="" className="h-full w-full object-cover" />
        <span className="absolute inset-0 grid place-items-center bg-slate-950/0 text-white opacity-0 transition group-hover:bg-slate-950/40 group-hover:opacity-100"><UploadCloud className="h-8 w-8" /></span>
      </div>
      <div className="p-5">
        <h2 className="font-display text-xl font-bold">{title}</h2>
        <p className="mt-1 min-h-10 text-xs leading-5 text-slate-500">{hint}</p>
        <span className="mt-4 inline-flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-[var(--ocean)]"><ImagePlus className="h-4 w-4" />{file ? file.name : "Choose image"}</span>
        <input type="file" name={field} accept="image/jpeg,image/png,image/webp" className="sr-only" onChange={(event) => {
          const next = event.target.files?.[0] ?? null;
          onChange(next);
          if (next) setPreview(URL.createObjectURL(next));
        }} />
        {error && <span className="mt-2 block text-xs text-rose-600">{error}</span>}
      </div>
    </label>
  );
}
