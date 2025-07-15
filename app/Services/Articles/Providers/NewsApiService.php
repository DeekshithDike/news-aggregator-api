<?php

namespace App\Services\Articles\Providers;

use Illuminate\Support\Facades\Http;
use App\Services\Articles\Contracts\ArticleProviderInterface;

class NewsApiService implements ArticleProviderInterface
{
    public function fetchArticles(): array {
        try {
            $response = Http::get(config('services.newsapi.base_url') . '/' . config('services.newsapi.version') . '/top-headlines', [
                'country' => 'us',
                'apiKey' => config('services.newsapi.key')
            ]);

            $articles = [];
            if ($response->successful()) {
                $data = $response->json();

                foreach ($data['articles'] as $item) {
                    $articles[] = [
                        'title' => $item['title'],
                        'description' => $item['description'],
                        'url' => $item['url'],
                        'source' => 'newsapi',
                        'published_at' => date('Y:m:d H:m:s', strtotime($item['publishedAt'])),
                    ];
                }
            }

            return $articles;
        } catch (\Throwable $e) {
            logger()->error('NewsAPI fetch failed: ' . $e->getMessage());
            return [];
        }
    }
}
