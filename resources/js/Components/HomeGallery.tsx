type GalleryImage = { path: string; alt: string };

export function InstagramIcon({ className = "h-5 w-5" }: { className?: string }) {
  return (
    <svg viewBox="0 0 24 24" aria-hidden="true" className={`${className} fill-none stroke-current`} strokeWidth="2">
      <rect x="3" y="3" width="18" height="18" rx="5" />
      <circle cx="12" cy="12" r="4" />
      <circle cx="17.5" cy="6.5" r="1" className="fill-current stroke-none" />
    </svg>
  );
}

export function HomeGallery({ images, instagramUrl }: { images: GalleryImage[]; instagramUrl: string }) {
  const visible = images.slice(0, 6);

  if (!visible.length) return null;

  return (
    <div className="grid grid-cols-2 gap-2.5 sm:gap-4 lg:grid-cols-3">
      {visible.map((image, index) => (
        <a
          key={`${image.path}-${index}`}
          href={instagramUrl}
          target="_blank"
          rel="noopener noreferrer"
          aria-label={`View ${image.alt} on Coast Rider Tour Instagram`}
          className="group relative aspect-square overflow-hidden rounded-xl bg-muted sm:rounded-2xl"
        >
          <img src={image.path} alt={image.alt} loading="lazy" decoding="async" className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" />
          <span className="absolute inset-0 bg-[#071a2d]/0 transition duration-300 group-hover:bg-[#071a2d]/45" />
          <span className="absolute inset-0 grid place-items-center opacity-0 transition duration-300 group-hover:opacity-100 group-focus-visible:opacity-100">
            <span className="grid h-12 w-12 place-items-center rounded-full border border-white/40 bg-white/15 text-white shadow-lg backdrop-blur-sm"><InstagramIcon className="h-6 w-6" /></span>
          </span>
        </a>
      ))}
    </div>
  );
}
