<?php

namespace App\Services\Articles\Providers;

use Illuminate\Support\Facades\Http;
use App\Services\Articles\Contracts\ArticleProviderInterface;

class NewsApiService implements ArticleProviderInterface
{
    public function fetchArticles(): array {
        try {
            $categories = config('services.newsapi.categories');
            $allArticles = [];
            
            foreach ($categories as $category) {
                $response = Http::get(config('services.newsapi.base_url') . '/' . config('services.newsapi.version') . '/top-headlines', [
                    'country' => 'us',
                    'category' => $category,
                    'apiKey' => config('services.newsapi.key')
                ]);

                if (!$response->successful()) {
                    logger()->error("NewsAPI fetch failed for category {$category}: " . $response->body());
                    continue;
                }

                $data = $response->json();

                foreach ($data['articles'] as $item) {
                    $allArticles[] = [
                        'title' => $item['title'],
                        'description' => $item['description'] ?? null,
                        'url' => $item['url'],
                        'source' => 'newsapi',
                        'category' => $category,
                        'author' => $item['author'] ?? 'NA',
                        'published_at' => date('Y-m-d H:i:s', strtotime($item['publishedAt'])),
                    ];
                }
            }

            return $allArticles;
        } catch (\Throwable $e) {
            logger()->error('NewsAPI fetch failed: ' . $e->getMessage());
            return [];
        }
    }
}
