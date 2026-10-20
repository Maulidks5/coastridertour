import { usePage } from "@inertiajs/react";

export type SiteConfig = {
  name: string;
  tagline: string;
  footerDescription: string;
  seoTitle: string;
  seoDescription: string;
  logoPath: string;
  ctaImagePath: string;
  whatsappNumber: string;
  whatsappMessage: string;
  email: string;
  phone: string;
  location: string;
  social: {
    facebook: string;
    instagram: string;
    tiktok: string;
    twitter: string;
    youtube: string;
  };
};

export const SITE_CONFIG: SiteConfig = {
  name: "Coast Rider Tour Company Limited",
  tagline: "Tours, Safaris & Reliable Transport",
  footerDescription: "Your trusted local partner for Zanzibar tours, Tanzania safaris and reliable private transport.",
  seoTitle: "Coast Rider Tour Company Limited — Zanzibar Tours & Tanzania Safaris",
  seoDescription: "Explore Zanzibar, discover Tanzania safaris and book reliable private transport with Coast Rider Tour Company Limited.",
  logoPath: "/brand/coast-rider-logo.png",
  ctaImagePath: "/images/coast-rider-placeholder.jpg",
  whatsappNumber: "255671921474",
  whatsappMessage: "Hello Coast Rider Tour, I would like help planning my trip.",
  email: "info@coastridertour.com",
  phone: "+255 671 921 474",
  location: "Zanzibar, Tanzania",
  social: {
    facebook: "https://www.facebook.com/share/1EnsuYC6DD/",
    instagram: "https://www.instagram.com/coastridertour/",
    tiktok: "https://www.tiktok.com/@coastridertour",
    twitter: "#",
    youtube: "#",
  },
};

export function useSiteConfig(): SiteConfig {
  return (usePage().props.site as SiteConfig | undefined) ?? SITE_CONFIG;
}

export const getWhatsAppLink = (customMessage?: string, config: SiteConfig = SITE_CONFIG) => {
  const msg = encodeURIComponent(customMessage || config.whatsappMessage);
  return `https://wa.me/${config.whatsappNumber}?text=${msg}`;
};
