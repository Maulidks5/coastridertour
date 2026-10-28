import { Link } from "./AppLink";
import { LockKeyhole, Mail, MapPin, MessageCircle, Phone } from "lucide-react";
import { getWhatsAppLink, useSiteConfig } from "@/config/site";
import { SocialLinks } from "@/Components/SocialLinks";

export function Footer() {
  const site = useSiteConfig();
  const quickLinks = [
    { label: "Zanzibar Tours", to: "/tours" },
    { label: "Tanzania Safaris", to: "/safaris" },
    { label: "Private Transport", to: "/transport" },
    { label: "About", to: "/about" },
    { label: "Contact", to: "/contact" },
  ];

  return (
    <footer className="bg-[oklch(0.20_0.04_240)] text-white">
      <div className="mx-auto grid max-w-7xl grid-cols-1 gap-10 px-4 py-12 sm:px-6 md:grid-cols-[minmax(0,1.35fr)_minmax(10rem,0.65fr)_minmax(14rem,0.9fr)] lg:px-8">
        <div>
          <img
            src={site.logoPath}
            alt={site.name}
            loading="lazy"
            decoding="async"
            className="h-16 max-w-full rounded-lg bg-white p-1.5 object-contain"
          />
          <p className="mt-3 text-sm text-white/70 leading-relaxed">
            {site.footerDescription}
          </p>
          <div className="mt-5"><SocialLinks compact /></div>
        </div>

        <div>
          <h4 className="font-semibold text-sm uppercase tracking-widest text-[var(--gold)]">Quick Links</h4>
          <ul className="mt-4 space-y-2 text-sm text-white/80">
            {quickLinks.map((link) => (
              <li key={link.label}><Link to={link.to} className="hover:text-[var(--gold)] transition">{link.label}</Link></li>
            ))}
          </ul>
        </div>

        <div>
          <h4 className="font-semibold text-sm uppercase tracking-widest text-[var(--gold)]">Contact</h4>
          <ul className="mt-4 space-y-3 text-sm text-white/80">
            <li><a href={`tel:${site.phone.replace(/\s/g, "")}`} className="flex items-start gap-2 transition hover:text-[var(--gold)]"><Phone className="mt-0.5 h-4 w-4 shrink-0" /> {site.phone}</a></li>
            <li><a href={getWhatsAppLink(undefined, site)} target="_blank" rel="noopener noreferrer" className="flex items-start gap-2 transition hover:text-[var(--gold)]"><MessageCircle className="mt-0.5 h-4 w-4 shrink-0" /> WhatsApp support</a></li>
            <li><a href={`mailto:${site.email}`} className="flex items-start gap-2 transition hover:text-[var(--gold)]"><Mail className="mt-0.5 h-4 w-4 shrink-0" /> {site.email}</a></li>
            <li className="flex items-start gap-2"><MapPin className="h-4 w-4 mt-0.5 shrink-0" /> {site.location}</li>
          </ul>
        </div>
      </div>
      <div className="border-t border-white/10">
        <div className="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-4 py-5 text-center text-xs text-white/60 sm:px-6 lg:flex-row lg:text-left lg:px-8">
          <p>© {new Date().getFullYear()} {site.name}. All rights reserved.</p>
          <div className="flex flex-wrap justify-center gap-x-4 gap-y-2 md:justify-end">{[{label:"Privacy",to:"/privacy-policy"},{label:"Terms",to:"/terms-and-conditions"},{label:"Booking policy",to:"/booking-cancellation-policy"}].map(link=><Link key={link.to} to={link.to} className="transition hover:text-[var(--gold)]">{link.label}</Link>)}</div>
          <div className="flex items-center gap-3">
            <a href="https://www.myt.co.tz" target="_blank" rel="noopener noreferrer" className="transition hover:text-[var(--gold)]">
              Designed by <span className="font-semibold text-white/80">MYT</span>
            </a>
            <span className="h-3 w-px bg-white/20" aria-hidden="true" />
            <a href="/admin/login" target="_blank" rel="noopener noreferrer" aria-label="Admin login" title="Admin login" className="inline-flex items-center transition hover:text-[var(--gold)]">
              <LockKeyhole className="h-3.5 w-3.5" />
            </a>
          </div>
        </div>
      </div>
    </footer>
  );
}
