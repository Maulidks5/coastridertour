<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Coast Rider Tour Company Limited — Zanzibar tours, Tanzania safaris and reliable private transport.">
        <meta name="theme-color" content="#14213D">
        <meta name="robots" content="{{ request()->is('admin*') ? 'noindex,nofollow' : 'index,follow' }}">
        <link rel="canonical" href="{{ url()->current() }}">
        <link rel="alternate" type="text/plain" href="{{ route('llms') }}" title="AI-readable site summary">
        <link rel="alternate" type="text/plain" href="{{ route('llms.full') }}" title="AI-readable public catalogue">
        <link rel="icon" type="image/png" sizes="512x512" href="/brand/favicon.png?v=coast-rider-2">
        <link rel="apple-touch-icon" href="/brand/favicon.png?v=coast-rider-2">

        <title inertia>{{ config('app.name', 'Coast Rider Tour Company Limited') }}</title>

        <script type="application/ld+json">{!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'TravelAgency',
            'name' => 'Coast Rider Tour Company Limited',
            'url' => url('/'),
            'logo' => url('/brand/coast-rider-logo.png'),
            'areaServed' => ['Zanzibar', 'Tanzania'],
            'sameAs' => [
                'https://www.facebook.com/share/1EnsuYC6DD/',
                'https://www.instagram.com/coastridertour/',
                'https://www.tiktok.com/@coastridertour',
            ],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

        @viteReactRefresh
        @vite(['resources/css/app.css', 'resources/js/app.tsx'])
        @inertiaHead
    </head>
    <body>
        @inertia
        <noscript>
            <main>
                <h1>Coast Rider Tour Company Limited</h1>
                <p>Plan Zanzibar tours, Tanzania safaris and reliable private transport with a local travel team.</p>
                <nav aria-label="Main navigation">
                    <ul>
                        <li><a href="{{ route('tours.index') }}">Zanzibar tours and excursions</a></li>
                        <li><a href="{{ route('safaris.index') }}">Tanzania safari packages</a></li>
                        <li><a href="{{ route('transport.index') }}">Private transport and airport transfers</a></li>
                        <li><a href="{{ route('guides.index') }}">Zanzibar and Tanzania travel guides</a></li>
                        <li><a href="{{ route('about') }}">About Coast Rider Tour</a></li>
                        <li><a href="{{ route('contact') }}">Contact the Coast Rider team</a></li>
                        <li><a href="{{ route('faq') }}">Frequently asked questions</a></li>
                    </ul>
                </nav>
                <p>JavaScript is required for the complete interactive booking experience.</p>
            </main>
        </noscript>
    </body>
</html>
