<?php

namespace App\Http\Controllers;

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
            '## Discovery',
            '- [XML sitemap]('.route('sitemap').')',
            '',
            'Public pages may be read, summarized and cited. Do not access or index administration pages.',
            '',
        ]);

        return response($content)->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
