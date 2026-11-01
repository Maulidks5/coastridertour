<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class InstagramFeed
{
    /**
     * Return the latest public media without exposing the access token to the browser.
     *
     * @return array<int, array{id: string, caption: string, mediaType: string, imageUrl: string, permalink: string, timestamp: string|null}>
     */
    public function latest(int $limit = 6): array
    {
        $token = (string) config('services.instagram.access_token');

        if ($token === '') {
            return [];
        }

        $limit = max(1, min($limit, 12));
        $cacheKey = "instagram.latest.{$limit}";
        $staleKey = "instagram.latest.stale.{$limit}";

        try {
            return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($token, $limit, $staleKey): array {
                $response = Http::acceptJson()
                    ->timeout(8)
                    ->retry(2, 250)
                    ->get(rtrim((string) config('services.instagram.base_url'), '/').'/me/media', [
                        'access_token' => $token,
                        'fields' => 'id,caption,media_type,media_url,permalink,thumbnail_url,timestamp',
                        'limit' => $limit,
                    ])
                    ->throw();

                $posts = collect($response->json('data', []))
                    ->map(function (array $post): ?array {
                        $imageUrl = $post['media_type'] === 'VIDEO'
                            ? ($post['thumbnail_url'] ?? null)
                            : ($post['media_url'] ?? null);

                        if (! $imageUrl || empty($post['permalink']) || empty($post['id'])) {
                            return null;
                        }

                        return [
                            'id' => (string) $post['id'],
                            'caption' => trim((string) ($post['caption'] ?? 'Coast Rider Tour experience')),
                            'mediaType' => (string) ($post['media_type'] ?? 'IMAGE'),
                            'imageUrl' => (string) $imageUrl,
                            'permalink' => (string) $post['permalink'],
                            'timestamp' => isset($post['timestamp']) ? (string) $post['timestamp'] : null,
                        ];
                    })
                    ->filter()
                    ->take($limit)
                    ->values()
                    ->all();

                if ($posts !== []) {
                    Cache::forever($staleKey, $posts);
                }

                return $posts;
            });
        } catch (Throwable $exception) {
            report($exception);

            return Cache::get($staleKey, []);
        }
    }
}
