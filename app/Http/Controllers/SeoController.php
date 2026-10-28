<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Tour;
use App\Models\TravelGuide;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        return response()
            ->view('sitemap', [
                'pages' => [
                    ['url' => route('home'), 'priority' => '1.0', 'frequency' => 'daily'],
                    ['url' => route('tours.index'), 'priority' => '0.9', 'frequency' => 'weekly'],
                    ['url' => route('tour-finder'), 'priority' => '0.8', 'frequency' => 'weekly'],
                    ['url' => route('safaris.index'), 'priority' => '0.9', 'frequency' => 'weekly'],
                    ['url' => route('transport.index'), 'priority' => '0.9', 'frequency' => 'weekly'],
                    ['url' => route('about'), 'priority' => '0.7', 'frequency' => 'monthly'],
                    ['url' => route('contact'), 'priority' => '0.7', 'frequency' => 'monthly'],
                    ['url' => route('faq'), 'priority' => '0.7', 'frequency' => 'monthly'],
                    ['url' => route('guides.index'), 'priority' => '0.8', 'frequency' => 'weekly'],
                    ['url' => route('feedback.create'), 'priority' => '0.4', 'frequency' => 'monthly'],
                    ['url' => route('legal.privacy'), 'priority' => '0.3', 'frequency' => 'yearly'],
                    ['url' => route('legal.terms'), 'priority' => '0.3', 'frequency' => 'yearly'],
                    ['url' => route('legal.cancellation'), 'priority' => '0.3', 'frequency' => 'yearly'],
                    ['url' => route('legal.refunds'), 'priority' => '0.3', 'frequency' => 'yearly'],
                ],
                'guides' => TravelGuide::query()->published()->orderByDesc('updated_at')->get(['slug', 'updated_at']),
                'tours' => Tour::query()
                    ->published()
                    ->orderByDesc('updated_at')
                    ->get(['slug', 'updated_at']),
            ])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $content = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin',
            '',
            'User-agent: ChatGPT-User',
            'Allow: /',
            '',
            'User-agent: GPTBot',
            'Allow: /',
            '',
            'Sitemap: '.route('sitemap'),
            '',
        ]);

        return response($content)->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function llms(): Response
    {
        $featuredTours = Tour::query()
            ->published()
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit(12)
            ->get(['slug', 'title', 'excerpt', 'duration', 'location']);

        $content = implode("\n", [
            '# Coast Rider Tour Company Limited',
            '',
            '> Local tour company for Zanzibar tours, Tanzania safaris and private transport.',
            '',
            '## Main pages',
            '- [Home]('.route('home').'): Company overview and featured experiences.',
            '- [Tours]('.route('tours.index').'): Zanzibar tours, activities and excursions.',
            '- [Safaris]('.route('safaris.index').'): Tanzania safari packages and custom planning.',
            '- [Transport]('.route('transport.index').'): Private airport transfers and vehicle hire.',
            '- [Travel guides]('.route('guides.index').'): Local travel information and planning advice.',
            '- [About]('.route('about').'): Information about Coast Rider Tour Company Limited.',
            '- [Contact]('.route('contact').'): Contact and enquiry information.',
            '- [FAQ]('.route('faq').'): Frequently asked questions.',
            '',
            '## Featured experiences',
            ...$featuredTours->map(fn (Tour $tour): string => '- ['.$this->plain($tour->title).']('.route('tours.show', $tour).'): '.$this->plain($tour->duration.' in '.$tour->location.'. '.$tour->excerpt))->all(),
            '',
            '## Discovery',
            '- [XML sitemap]('.route('sitemap').')',
            '- [Full text catalogue]('.route('llms.full').'): Detailed public tour, guide and FAQ content.',
            '',
            'Public pages may be read, summarized and cited. Do not access or index administration pages.',
            '',
        ]);

        return response($content)->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function llmsFull(): Response
    {
        $tours = Tour::query()
            ->published()
            ->with(['highlights:id,tour_id,label,sort_order', 'inclusions:id,tour_id,label,is_included,sort_order'])
            ->orderBy('sort_order')
            ->get();
        $guides = TravelGuide::query()->published()->orderByDesc('published_at')->get();
        $faqs = Faq::query()->where('is_active', true)->orderBy('sort_order')->orderBy('id')->get();

        $lines = [
            '# Coast Rider Tour Company Limited — Public Catalogue',
            '',
            '> Zanzibar tours, Tanzania safaris and private transport. Prices and availability must be confirmed before booking.',
            '',
            'Canonical website: '.route('home'),
            'Contact: '.route('contact'),
            '',
            '## Tours and safari experiences',
        ];

        foreach ($tours as $tour) {
            $lines[] = '';
            $lines[] = '### '.$this->plain($tour->title);
            $lines[] = '- URL: '.route('tours.show', $tour);
            $lines[] = '- Type: '.$this->plain(str($tour->experience_type)->replace('_', ' ')->title()->toString());
            $lines[] = '- Duration: '.$this->plain($tour->duration);
            $lines[] = '- Location: '.$this->plain($tour->location);
            $lines[] = '- Price: '.($tour->price > 0 ? $tour->currency.' '.number_format((float) $tour->price, 2).' starting price; confirm the applicable shared or private rate.' : 'Custom quote required.');
            $lines[] = '- Summary: '.$this->plain($tour->excerpt);
            if ($tour->highlights->isNotEmpty()) $lines[] = '- Highlights: '.$tour->highlights->pluck('label')->map(fn ($value) => $this->plain($value))->join('; ');
            $included = $tour->inclusions->where('is_included', true);
            if ($included->isNotEmpty()) $lines[] = '- Typical inclusions: '.$included->pluck('label')->map(fn ($value) => $this->plain($value))->join('; ');
        }

        $lines[] = '';
        $lines[] = '## Travel guides';
        foreach ($guides as $guide) {
            $lines[] = '';
            $lines[] = '### '.$this->plain($guide->title);
            $lines[] = '- URL: '.route('guides.show', $guide);
            $lines[] = '- Category: '.$this->plain($guide->category);
            $lines[] = '- Summary: '.$this->plain($guide->excerpt);
            $lines[] = '';
            $lines[] = $this->plain($guide->content);
        }

        $lines[] = '';
        $lines[] = '## Frequently asked questions';
        foreach ($faqs as $faq) {
            $lines[] = '';
            $lines[] = '### '.$this->plain($faq->question);
            $lines[] = $this->plain($faq->answer);
        }

        $lines[] = '';
        $lines[] = 'This file contains public information only. Current availability, final price and exact inclusions require direct confirmation from Coast Rider Tour.';
        $lines[] = '';

        return response(implode("\n", $lines))->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    private function plain(mixed $value): string
    {
        return trim(preg_replace('/\s+/u', ' ', strip_tags((string) $value)) ?? '');
    }
}
