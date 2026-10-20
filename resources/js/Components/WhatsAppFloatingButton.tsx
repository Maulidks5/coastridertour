import { FormEvent, useEffect, useState } from "react";
import { ArrowLeft, ArrowRight, Binoculars, Bot, CarFront, CircleHelp, MessageCircle, Palmtree, Send, X } from "lucide-react";
import { getWhatsAppLink, useSiteConfig } from "@/config/site";

type Service = "tour" | "safari" | "transport" | "question";
type Details = { name: string; date: string; guests: string; interest: string; pickup: string; destination: string; time: string; flight: string; message: string };

const choices = [
  { id: "tour" as const, title: "Book a Zanzibar tour", icon: Palmtree, color: "bg-sky-50 text-sky-700" },
  { id: "safari" as const, title: "Plan a Tanzania safari", icon: Binoculars, color: "bg-amber-50 text-amber-700" },
  { id: "transport" as const, title: "Request transport price", icon: CarFront, color: "bg-emerald-50 text-emerald-700" },
  { id: "question" as const, title: "Ask a general question", icon: CircleHelp, color: "bg-violet-50 text-violet-700" },
];

const empty: Details = { name: "", date: "", guests: "", interest: "", pickup: "", destination: "", time: "", flight: "", message: "" };

export function WhatsAppFloatingButton() {
  const site = useSiteConfig();
  const [open, setOpen] = useState(false);
  const [step, setStep] = useState<1 | 2 | 3>(1);
  const [service, setService] = useState<Service | null>(null);
  const [details, setDetails] = useState<Details>(empty);

  useEffect(() => {
    function close(event: KeyboardEvent) { if (event.key === "Escape") setOpen(false); }
    window.addEventListener("keydown", close);
    return () => window.removeEventListener("keydown", close);
  }, []);

  function choose(value: Service) { setService(value); setStep(2); }
  function update(key: keyof Details, value: string) { setDetails((current) => ({ ...current, [key]: value })); }
  function review(event: FormEvent) { event.preventDefault(); setStep(3); }
  function reset() { setStep(1); setService(null); setDetails(empty); }

  const message = buildMessage(service, details);

  return <>
    {open && <div className="fixed inset-x-3 bottom-24 z-[60] ml-auto flex max-h-[min(42rem,calc(100vh-7rem))] w-auto max-w-[24rem] flex-col overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_28px_80px_-20px_rgb(3_37_67/0.5)] sm:inset-x-auto sm:right-6 sm:w-[24rem]" role="dialog" aria-modal="false" aria-label="Coast Rider Travel Assistant">
      <div className="flex items-center gap-3 bg-[var(--ocean)] px-5 py-4 text-white">
        <span className="relative grid h-11 w-11 shrink-0 place-items-center rounded-full bg-white/12"><Bot className="h-5 w-5" /><span className="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-[var(--ocean)] bg-[#25D366]" /></span>
        <div className="min-w-0 flex-1"><p className="font-display text-sm font-extrabold">Coast Rider Assistant</p><p className="mt-0.5 text-[11px] text-white/65">Online · Guided trip planning</p></div>
        <button type="button" onClick={() => setOpen(false)} aria-label="Close travel assistant" className="grid h-9 w-9 place-items-center rounded-full text-white/70 transition hover:bg-white/10 hover:text-white"><X className="h-5 w-5" /></button>
      </div>

      <div className="flex min-h-0 flex-1 flex-col overflow-y-auto bg-[#f7fafc] p-4 sm:p-5">
        <div className="mb-4 flex items-center gap-2">{[1, 2, 3].map((item) => <span key={item} className={`h-1.5 flex-1 rounded-full ${item <= step ? "bg-[var(--gold)]" : "bg-slate-200"}`} />)}</div>

        {step === 1 && <div>
          <Bubble>Hello! 👋 How can we help plan your trip today?</Bubble>
          <div className="mt-4 grid gap-2.5">{choices.map(({ id, title, icon: Icon, color }) => <button key={id} type="button" onClick={() => choose(id)} className="group flex items-center gap-3 rounded-2xl border border-slate-200 bg-white p-3.5 text-left text-sm font-bold text-slate-800 transition hover:border-[var(--turquoise)] hover:shadow-sm"><span className={`grid h-10 w-10 shrink-0 place-items-center rounded-xl ${color}`}><Icon className="h-5 w-5" /></span><span className="flex-1">{title}</span><ArrowRight className="h-4 w-4 text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-[var(--ocean)]" /></button>)}</div>
          <p className="mt-4 text-center text-[10px] leading-4 text-slate-400">No payment is required. We will prepare your message before opening WhatsApp.</p>
        </div>}

        {step === 2 && service && <form onSubmit={review} className="grid gap-3.5">
          <button type="button" onClick={() => setStep(1)} className="mb-1 inline-flex w-fit items-center gap-1 text-xs font-bold text-slate-500"><ArrowLeft className="h-3.5 w-3.5" /> Change service</button>
          <Bubble>{questionFor(service)}</Bubble>
          <Field label="Your name"><input required value={details.name} onChange={(e) => update("name", e.target.value)} className={input} placeholder="Your full name" /></Field>
          {service !== "question" && <div className="grid grid-cols-2 gap-3"><Field label="Travel date"><input required type="date" min={new Date().toISOString().split("T")[0]} value={details.date} onChange={(e) => update("date", e.target.value)} className={input} /></Field><Field label="Guests"><input required type="number" min="1" max="100" value={details.guests} onChange={(e) => update("guests", e.target.value)} className={input} placeholder="2" /></Field></div>}
          {(service === "tour" || service === "safari") && <Field label={service === "tour" ? "Tour or experience" : "Parks or safari interest"}><input required value={details.interest} onChange={(e) => update("interest", e.target.value)} className={input} placeholder={service === "tour" ? "e.g. Mnemba, Safari Blue" : "e.g. Serengeti, Ngorongoro"} /></Field>}
          {service === "transport" && <><Field label="Pickup location"><input required value={details.pickup} onChange={(e) => update("pickup", e.target.value)} className={input} placeholder="Airport, hotel or address" /></Field><Field label="Destination"><input required value={details.destination} onChange={(e) => update("destination", e.target.value)} className={input} /></Field><div className="grid grid-cols-2 gap-3"><Field label="Pickup time"><input type="time" value={details.time} onChange={(e) => update("time", e.target.value)} className={input} /></Field><Field label="Flight number"><input value={details.flight} onChange={(e) => update("flight", e.target.value)} className={input} placeholder="Optional" /></Field></div></>}
          <Field label={service === "question" ? "Your question" : "Anything else?"}><textarea required={service === "question"} rows={3} value={details.message} onChange={(e) => update("message", e.target.value)} className={input} placeholder="Special requests, hotel, budget or other details…" /></Field>
          <button className="mt-1 inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-[var(--ocean)] px-5 text-sm font-bold text-white">Review details <ArrowRight className="h-4 w-4" /></button>
        </form>}

        {step === 3 && service && <div>
          <button type="button" onClick={() => setStep(2)} className="mb-3 inline-flex items-center gap-1 text-xs font-bold text-slate-500"><ArrowLeft className="h-3.5 w-3.5" /> Edit details</button>
          <Bubble>Perfect! Your message is ready. Continue to WhatsApp and our local team will personally assist you.</Bubble>
          <div className="mt-4 whitespace-pre-line rounded-2xl border border-slate-200 bg-white p-4 text-xs leading-5 text-slate-600">{message}</div>
          <a href={getWhatsAppLink(message, site)} target="_blank" rel="noopener noreferrer" onClick={() => setOpen(false)} className="mt-4 inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#25D366] px-5 text-sm font-bold text-white shadow-sm"><Send className="h-4 w-4" /> Continue on WhatsApp</a>
          <button type="button" onClick={reset} className="mt-2 w-full py-2 text-xs font-bold text-slate-400 hover:text-[var(--ocean)]">Start again</button>
        </div>}
      </div>
    </div>}

    <button type="button" onClick={() => setOpen((value) => !value)} aria-label={open ? "Close travel assistant" : "Open Coast Rider travel assistant"} aria-expanded={open} className={`fixed bottom-6 right-6 z-[61] grid h-15 w-15 place-items-center rounded-full text-white shadow-[0_14px_35px_-8px_rgb(3_37_67/0.55)] transition-all hover:scale-105 ${open ? "rotate-90 bg-[var(--ocean)]" : "bg-[#25D366]"}`}>
      {open ? <X className="h-6 w-6" /> : <><MessageCircle className="h-7 w-7" /><span className="absolute right-0 top-0 h-4 w-4 rounded-full border-2 border-white bg-[var(--gold)]" /></>}
    </button>
  </>;
}

function Bubble({ children }: { children: React.ReactNode }) { return <div className="max-w-[90%] rounded-2xl rounded-tl-sm bg-white p-4 text-sm font-medium leading-6 text-slate-700 shadow-sm ring-1 ring-slate-100">{children}</div>; }
function Field({ label, children }: { label: string; children: React.ReactNode }) { return <label className="grid gap-1.5"><span className="text-[10px] font-bold uppercase tracking-wider text-slate-500">{label}</span>{children}</label>; }
const input = "w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm outline-none transition focus:border-[var(--turquoise)] focus:ring-2 focus:ring-[var(--turquoise)]/10";

function questionFor(service: Service) {
  return { tour: "Great choice! Tell us which Zanzibar experience you are interested in.", safari: "Let's shape your safari. Share the parks or experience you have in mind.", transport: "Let's arrange your ride. Tell us your route and travel details for a personalised price.", question: "Of course. Tell us your question and our local team will help." }[service];
}

function buildMessage(service: Service | null, data: Details) {
  const label = { tour: "Zanzibar tour", safari: "Tanzania safari", transport: "private transport", question: "general enquiry" }[service || "question"];
  const lines = [`Hello Coast Rider Tour, my name is ${data.name}.`, `I need help with: ${label}.`];
  if (data.date) lines.push(`Travel date: ${data.date}.`);
  if (data.guests) lines.push(`Guests/passengers: ${data.guests}.`);
  if (data.interest) lines.push(`Interested in: ${data.interest}.`);
  if (data.pickup) lines.push(`Pickup: ${data.pickup}.`);
  if (data.destination) lines.push(`Destination: ${data.destination}.`);
  if (data.time) lines.push(`Pickup time: ${data.time}.`);
  if (data.flight) lines.push(`Flight number: ${data.flight}.`);
  if (data.message) lines.push(`Additional details: ${data.message}`);
  return lines.join("\n");
}
