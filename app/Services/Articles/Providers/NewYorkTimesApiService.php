<?php

namespace App\Services\Articles\Providers;

use Illuminate\Support\Facades\Http;
use App\Services\Articles\Contracts\ArticleProviderInterface;

class NewYorkTimesApiService implements ArticleProviderInterface
{
    public function fetchArticles(): array {
        try {
            $response = Http::get(config('services.nytimes.base_url') . '/topstories' . '/' . config('services.nytimes.version') . '/home.json', [
                'api-key' => config('services.nytimes.key'),
            ]);

            if (!$response->successful()) {
                logger()->error('NewsAPI fetch failed: ' . $response->body());
                return [];
            }

            $articles = [];
            $data = $response->json();

            foreach ($data['results'] as $item) {
                $articles[] = [
                    'title' => $item['title'],
                    'description' => $item['abstract'] ?? null,
                    'url' => $item['url'],
                    'source' => 'nytimes',
                    'category' => $item['section'] ?? 'NA',
                    'author' => $item['byline'] ?? 'NA',
                    'published_at' => date('Y:m:d H:m:s', strtotime($item['published_date'])),
                ];
            }

            return $articles;
        } catch (\Throwable $e) {
            logger()->error('NewYorkTimesApi fetch failed: ' . $e->getMessage());
            return [];
        }
    }
}
