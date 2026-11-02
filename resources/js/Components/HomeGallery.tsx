type GalleryImage = { path: string; alt: string };

export function HomeGallery({ images }: { images: GalleryImage[] }) {
  if (!images.length) return null;

  return (
    <div className="grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-4">
      {images.slice(0, 8).map((image, index) => (
        <figure key={`${image.path}-${index}`} className={`group relative overflow-hidden rounded-2xl bg-muted ${index === 0 ? "col-span-2 row-span-2 aspect-square lg:aspect-auto" : "aspect-square"}`}>
          <img src={image.path} alt={image.alt} loading="lazy" decoding="async" className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" />
          <figcaption className="absolute inset-x-0 bottom-0 translate-y-full bg-gradient-to-t from-[#071a2d]/85 to-transparent px-4 pb-4 pt-12 text-xs font-semibold text-white transition duration-300 group-hover:translate-y-0">{image.alt}</figcaption>
        </figure>
      ))}
    </div>
  );
}
