import { Link } from "./AppLink";
import { LockKeyhole, Mail, MapPin, MessageCircle, Phone } from "lucide-react";
import { useSiteConfig } from "@/config/site";
import { SocialLinks } from "@/Components/SocialLinks";

export function Footer() {
  const site = useSiteConfig();
  const quickLinks = [
    { label: "Home", to: "/" },
    { label: "Tours", to: "/tours" },
    { label: "Safaris", to: "/safaris" },
    { label: "Transport", to: "/transport" },
    { label: "About", to: "/about" },
    { label: "Travel Guides", to: "/guides" },
    { label: "FAQ", to: "/faq" },
    { label: "Contact", to: "/contact" },
    { label: "Leave feedback", to: "/feedback" },
  ];
  const popularTours = [
    { label: "Safari Blue Zanzibar", to: "/tours/safari-blue" },
    { label: "Prison Island", to: "/tours/prison-island" },
    { label: "Nakupenda Beach", to: "/tours/nakupenda-beach" },
    { label: "Mnemba Island", to: "/tours/mnemba-island" },
    { label: "Spice Tour", to: "/tours/spice-tour" },
  ];

  return (
    <footer className="bg-[oklch(0.20_0.04_240)] text-white">
      <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
        <div>
          <img
            src={site.logoPath}
            alt={site.name}
            loading="lazy"
            decoding="async"
            className="h-24 max-w-full rounded-xl bg-white p-2 object-contain"
          />
          <p className="mt-3 text-sm text-white/70 leading-relaxed">
            {site.footerDescription}
          </p>
          <div className="mt-5"><SocialLinks /></div>
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
          <h4 className="font-semibold text-sm uppercase tracking-widest text-[var(--gold)]">Popular Tours</h4>
          <ul className="mt-4 space-y-2 text-sm text-white/80">
            {popularTours.map((tour) => (
              <li key={tour.label}><Link to={tour.to} className="hover:text-[var(--gold)] transition">{tour.label}</Link></li>
            ))}
          </ul>
        </div>

        <div>
          <h4 className="font-semibold text-sm uppercase tracking-widest text-[var(--gold)]">Contact</h4>
          <ul className="mt-4 space-y-3 text-sm text-white/80">
            <li className="flex items-start gap-2"><Phone className="h-4 w-4 mt-0.5 shrink-0" /> {site.phone}</li>
            <li className="flex items-start gap-2"><MessageCircle className="h-4 w-4 mt-0.5 shrink-0" /> WhatsApp Support 24/7</li>
            <li className="flex items-start gap-2"><Mail className="h-4 w-4 mt-0.5 shrink-0" /> {site.email}</li>
            <li className="flex items-start gap-2"><MapPin className="h-4 w-4 mt-0.5 shrink-0" /> {site.location}</li>
          </ul>
        </div>
      </div>
      <div className="border-t border-white/10">
        <div className="mx-auto flex max-w-7xl flex-col items-center justify-between gap-2 px-4 py-5 text-center text-xs text-white/60 sm:flex-row sm:px-6 sm:text-left lg:px-8">
          <p>© {new Date().getFullYear()} {site.name}. All rights reserved.</p>
          <div className="flex flex-wrap justify-center gap-x-4 gap-y-2 sm:justify-start">{[{label:"Privacy",to:"/privacy-policy"},{label:"Terms",to:"/terms-and-conditions"},{label:"Cancellation",to:"/booking-cancellation-policy"},{label:"Refunds",to:"/refund-policy"}].map(link=><Link key={link.to} to={link.to} className="transition hover:text-[var(--gold)]">{link.label}</Link>)}</div>
          <a href="/admin/login" target="_blank" rel="noopener noreferrer" className="inline-flex items-center gap-1.5 rounded-full border border-white/15 px-3 py-1.5 font-semibold text-white/70 transition hover:border-[var(--gold)]/60 hover:bg-white/5 hover:text-[var(--gold)]">
            <LockKeyhole className="h-3.5 w-3.5" /> Admin Login
          </a>
          <div>
            <p className="sm:text-right">
              Developed by{" "}
              <a
                href="https://www.myt.co.tz"
                target="_blank"
                rel="noopener noreferrer"
                className="font-medium text-white/80 transition hover:text-[var(--gold)]"
              >
                Mwambao Youth Technology
              </a>
            </p>
          </div>
        </div>
      </div>
    </footer>
  );
}
