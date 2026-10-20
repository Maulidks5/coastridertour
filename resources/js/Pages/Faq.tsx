import { useState } from "react";
import { Head, Link } from "@inertiajs/react";
import { ChevronDown, MessageCircle } from "lucide-react";
import { Footer } from "@/Components/Footer";
import { Navbar } from "@/Components/Navbar";
import { WhatsAppFloatingButton } from "@/Components/WhatsAppFloatingButton";
import { getWhatsAppLink, useSiteConfig } from "@/config/site";

type Item = { id: number; category: string; question: string; answer: string };
type Props = { groups: Item[][] };

export default function Faq({ groups }: Props) {
  const site = useSiteConfig();
  const [open, setOpen] = useState<number | null>(groups[0]?.[0]?.id ?? null);

  return <div className="min-h-screen bg-background">
    <Head title={`Frequently asked questions · ${site.name}`}>
      <meta name="description" content="Answers about tour bookings, safaris, transport, payments, pickup and travelling with Coast Rider Tour." />
    </Head>
    <Navbar />
    <main>
      <section className="bg-[var(--ocean)] text-white">
        <div className="mx-auto max-w-7xl px-4 py-16 sm:px-6 md:py-24 lg:px-8">
          <p className="text-xs font-bold uppercase tracking-[0.22em] text-[var(--gold)]">Travel information</p>
          <h1 className="mt-4 max-w-3xl font-display text-4xl font-bold md:text-6xl">Frequently asked questions</h1>
          <p className="mt-5 max-w-2xl text-base leading-8 text-white/70">Quick answers to help you book and enjoy your Zanzibar or Tanzania experience with confidence.</p>
        </div>
      </section>
      <section className="mx-auto max-w-4xl px-4 py-14 sm:px-6 md:py-20">
        {groups.length > 0 ? <div className="space-y-10">
          {groups.map((items) => <section key={items[0].category}>
            <p className="mb-4 text-xs font-bold uppercase tracking-[0.2em] text-[var(--turquoise)]">{items[0].category}</p>
            <div className="divide-y divide-border overflow-hidden rounded-2xl border border-border bg-card shadow-[var(--shadow-card)]">
              {items.map((item) => {
                const expanded = open === item.id;
                return <article key={item.id}>
                  <button type="button" onClick={() => setOpen(expanded ? null : item.id)} aria-expanded={expanded} className="flex w-full items-center justify-between gap-4 px-5 py-5 text-left font-bold md:px-6">
                    <span>{item.question}</span><ChevronDown className={`h-5 w-5 shrink-0 text-[var(--ocean)] transition-transform ${expanded ? "rotate-180" : ""}`} />
                  </button>
                  {expanded && <div className="px-5 pb-5 text-sm leading-7 text-muted-foreground md:px-6"><p className="whitespace-pre-line">{item.answer}</p></div>}
                </article>;
              })}
            </div>
          </section>)}
        </div> : <div className="rounded-2xl border border-dashed border-border p-10 text-center text-muted-foreground">Travel answers are being prepared.</div>}
        <div className="mt-12 rounded-3xl bg-[var(--sand)] p-7 text-center md:p-10">
          <MessageCircle className="mx-auto h-8 w-8 text-[var(--turquoise)]" />
          <h2 className="mt-4 font-display text-2xl font-bold md:text-3xl">Still have a question?</h2>
          <p className="mt-2 text-sm text-muted-foreground">Our local team can help with your dates, hotel and preferred experiences.</p>
          <div className="mt-6 flex flex-wrap justify-center gap-3">
            <a href={getWhatsAppLink("Hello Coast Rider Tour, I have a question about planning my trip.", site)} target="_blank" rel="noopener noreferrer" className="rounded-full bg-[#25D366] px-6 py-3 text-sm font-bold text-white">Ask on WhatsApp</a>
            <Link href="/contact" className="rounded-full border border-[var(--ocean)] px-6 py-3 text-sm font-bold text-[var(--ocean)]">Contact us</Link>
          </div>
        </div>
      </section>
    </main>
    <Footer />
    <WhatsAppFloatingButton />
  </div>;
}
