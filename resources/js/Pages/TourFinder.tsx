import { useMemo, useState } from "react";
import { Head, Link } from "@inertiajs/react";
import { ArrowLeft, ArrowRight, Check, MessageCircle, RotateCcw, Sparkles } from "lucide-react";
import { Navbar } from "@/Components/Navbar";
import { Footer } from "@/Components/Footer";
import { TourCard } from "@/Components/TourCard";
import { WhatsAppFloatingButton } from "@/Components/WhatsAppFloatingButton";
import { getWhatsAppLink, useSiteConfig } from "@/config/site";
import type { TourCardData } from "@/types/tour";

type Answers = { interest: string; duration: string; group: string; budget: string };
type Option = { value: string; label: string; description: string };

const questions: Array<{ key: keyof Answers; eyebrow: string; title: string; options: Option[] }> = [
  { key: "interest", eyebrow: "Step 1", title: "What would you most like to experience?", options: [
    { value: "island", label: "Islands & ocean", description: "Boat trips, sandbanks and snorkeling" },
    { value: "nature", label: "Nature & wildlife", description: "Forests, caves and local wildlife" },
    { value: "culture", label: "Culture & local life", description: "Spices, food, history and villages" },
    { value: "beach", label: "Beaches & relaxation", description: "Beautiful coasts and relaxed days" },
    { value: "water", label: "Water sports", description: "Active ocean adventures" },
    { value: "safari", label: "Tanzania safari", description: "Mainland parks and wildlife journeys" },
    { value: "combo", label: "Combination package", description: "Several experiences in one plan" },
  ]},
  { key: "duration", eyebrow: "Step 2", title: "How much time would you like to spend?", options: [
    { value: "short", label: "A few hours", description: "Short or half-day experience" },
    { value: "day", label: "One full day", description: "A complete day experience" },
    { value: "flexible", label: "Flexible", description: "Show suitable options of any length" },
    { value: "multi", label: "Several days", description: "Safari or holiday package" },
  ]},
  { key: "group", eyebrow: "Step 3", title: "Who are you travelling with?", options: [
    { value: "solo", label: "Solo traveller", description: "One traveller" },
    { value: "couple", label: "Couple", description: "Two travellers" },
    { value: "family", label: "Family", description: "Adults travelling with children" },
    { value: "group", label: "Friends or group", description: "Three or more travellers" },
  ]},
  { key: "budget", eyebrow: "Step 4", title: "What pricing style suits you?", options: [
    { value: "any", label: "Show all", description: "I am open to the best match" },
    { value: "request", label: "Custom quote required", description: "Price based on my exact plans" },
    { value: "under100", label: "Up to $100 / adult", description: "Where a starting price is available" },
    { value: "100to250", label: "$100–$250 / adult", description: "Where a starting price is available" },
    { value: "over250", label: "$250+ / adult", description: "Premium or longer experiences" },
  ]},
];

export default function TourFinder({ tours }: { tours: TourCardData[] }) {
  const site = useSiteConfig();
  const [step, setStep] = useState(0);
  const [answers, setAnswers] = useState<Answers>({ interest: "", duration: "", group: "", budget: "" });
  const complete = step >= questions.length;
  const current = questions[Math.min(step, questions.length - 1)];

  const matches = useMemo(() => {
    const interestMatch = (tour: TourCardData) => {
      const category = (tour.category ?? "").toLowerCase();
      const title = tour.title.toLowerCase();
      if (answers.interest === "safari") return tour.experienceType === "safari";
      if (answers.interest === "water") return tour.experienceType === "water_sport";
      if (answers.interest === "combo") return title.includes(" & ") || title.includes("escape") || title.includes("package");
      if (answers.interest === "nature") return category.includes("nature");
      if (answers.interest === "culture") return category.includes("culture");
      if (answers.interest === "beach") return category.includes("beach");
      return category.includes("island") || category.includes("ocean");
    };
    const durationMatch = (tour: TourCardData) => {
      const duration = tour.duration.toLowerCase();
      if (answers.duration === "flexible") return true;
      if (answers.duration === "short") return duration.includes("half") || duration.includes("hour");
      if (answers.duration === "day") return duration.includes("full day") || duration === "full day";
      return duration.includes("day") && !duration.includes("full day") || duration.includes("multi");
    };
    const budgetMatch = (tour: TourCardData) => {
      if (answers.budget === "any") return true;
      if (answers.budget === "request") return tour.price === 0;
      if (answers.budget === "under100") return tour.price > 0 && tour.price <= 100;
      if (answers.budget === "100to250") return tour.price >= 100 && tour.price <= 250;
      return tour.price >= 250;
    };
    const scored = tours.map((tour) => ({
      tour,
      score: (interestMatch(tour) ? 5 : 0) + (durationMatch(tour) ? 3 : 0) + (budgetMatch(tour) ? 2 : 0),
    })).sort((a, b) => b.score - a.score);
    const strict = scored.filter(({ tour }) => interestMatch(tour) && durationMatch(tour) && budgetMatch(tour));
    return (strict.length >= 3 ? strict : scored.filter((item) => item.score >= 5)).slice(0, 6).map(({ tour }) => tour);
  }, [answers, tours]);

  const select = (value: string) => {
    setAnswers((currentAnswers) => ({ ...currentAnswers, [current.key]: value }));
    setStep((currentStep) => currentStep + 1);
  };
  const restart = () => { setAnswers({ interest: "", duration: "", group: "", budget: "" }); setStep(0); };
  const message = `Hello Coast Rider Tour, I used your Tour Finder. I am interested in ${answers.interest}, prefer ${answers.duration} duration, travel as a ${answers.group}, and selected ${answers.budget} pricing. Please help me choose and quote the best option.`;

  return <div className="min-h-screen bg-background">
    <Head title={`Find Your Perfect Tour | ${site.name}`}><meta name="description" content="Answer four quick questions and discover Zanzibar tours, Tanzania safaris and combination packages matched to your plans." /></Head>
    <Navbar />
    <main>
      <section className="relative overflow-hidden bg-[var(--ocean)] text-white">
        <img src="/images/experiences/hero-ocean.webp" alt="Explore Zanzibar experiences" className="absolute inset-0 h-full w-full object-cover" />
        <div className="absolute inset-0 bg-[#052f53]/72" />
        <div className="relative mx-auto flex min-h-[24rem] max-w-4xl flex-col items-center justify-center px-4 py-10 text-center sm:min-h-[28rem] sm:px-6 md:py-14">
          <span className="mx-auto grid h-12 w-12 place-items-center rounded-2xl bg-[var(--gold)] text-[var(--ocean)]"><Sparkles className="h-6 w-6" /></span>
          <p className="mt-5 text-xs font-bold uppercase tracking-[.2em] text-[var(--gold)]">Smart Tour Finder</p>
          <h1 className="mt-2 font-display text-4xl font-bold md:text-5xl">Find the right experience for your trip</h1>
          <p className="mx-auto mt-4 max-w-2xl text-sm leading-7 text-white/70">Four quick questions. Get matched with the right experience.</p>
        </div>
      </section>

      {!complete ? <section className="mx-auto max-w-4xl px-4 py-12 sm:px-6 md:py-16">
        <div className="mb-8 flex items-center gap-3"><div className="h-2 flex-1 overflow-hidden rounded-full bg-slate-100"><div className="h-full rounded-full bg-[var(--turquoise)] transition-all" style={{ width: `${((step + 1) / questions.length) * 100}%` }} /></div><span className="text-xs font-bold text-muted-foreground">{step + 1}/{questions.length}</span></div>
        <p className="text-xs font-bold uppercase tracking-[.2em] text-[var(--turquoise)]">{current.eyebrow}</p>
        <h2 className="mt-2 font-display text-3xl font-bold md:text-4xl">{current.title}</h2>
        <div className="mt-7 grid gap-3 sm:grid-cols-2">{current.options.map((option) => <button key={option.value} type="button" onClick={() => select(option.value)} className="group flex min-h-24 items-center justify-between gap-4 rounded-2xl border border-border bg-white p-5 text-left shadow-sm transition hover:-translate-y-0.5 hover:border-[var(--turquoise)] hover:shadow-[var(--shadow-card)]"><span><span className="block font-display text-lg font-bold group-hover:text-[var(--ocean)]">{option.label}</span><span className="mt-1 block text-sm text-muted-foreground">{option.description}</span></span><ArrowRight className="h-5 w-5 shrink-0 text-[var(--turquoise)]" /></button>)}</div>
        {step > 0 && <button type="button" onClick={() => setStep((value) => value - 1)} className="mt-7 inline-flex items-center gap-2 text-sm font-bold text-[var(--ocean)]"><ArrowLeft className="h-4 w-4" /> Previous question</button>}
      </section> : <section className="mx-auto max-w-7xl px-4 py-12 sm:px-6 md:py-16 lg:px-8">
        <div className="flex flex-col justify-between gap-5 sm:flex-row sm:items-end"><div><p className="text-xs font-bold uppercase tracking-[.2em] text-[var(--turquoise)]">Your matches</p><h2 className="mt-2 font-display text-3xl font-bold md:text-4xl">Recommended for your trip</h2><p className="mt-2 text-sm text-muted-foreground">These are starting ideas. Our local team can customise timing, pickup and inclusions.</p></div><button type="button" onClick={restart} className="inline-flex items-center gap-2 text-sm font-bold text-[var(--ocean)]"><RotateCcw className="h-4 w-4" /> Start again</button></div>
        <div className="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">{matches.map((tour) => <TourCard key={tour.slug} tour={tour} />)}</div>
        <div className="mt-10 flex flex-col items-start justify-between gap-5 rounded-3xl bg-[var(--sand)] p-6 sm:flex-row sm:items-center md:p-8"><div className="flex gap-3"><Check className="mt-0.5 h-6 w-6 shrink-0 text-[var(--turquoise)]" /><div><h3 className="font-display text-xl font-bold">Want a personal recommendation?</h3><p className="mt-1 text-sm text-muted-foreground">Send your Finder choices to our local team and receive a tailored suggestion and price.</p></div></div><a href={getWhatsAppLink(message, site)} target="_blank" rel="noopener noreferrer" className="inline-flex shrink-0 items-center gap-2 rounded-full bg-[#25D366] px-6 py-3 font-bold text-white"><MessageCircle className="h-5 w-5" /> Send choices</a></div>
      </section>}
    </main>
    <Footer /><WhatsAppFloatingButton />
  </div>;
}
