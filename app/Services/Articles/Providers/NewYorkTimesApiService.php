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

            $articles = [];
            if ($response->successful()) {
                $data = $response->json();

                foreach ($data['results'] as $item) {
                    $articles[] = [
                        'title' => $item['title'],
                        'description' => $item['abstract'],
                        'url' => $item['url'],
                        'source' => 'nytimes',
                        'published_at' => date('Y:m:d H:m:s', strtotime($item['published_date'])),
                    ];
                }
            }

            return $articles;
        } catch (\Throwable $e) {
            logger()->error('NewYorkTimesApi fetch failed: ' . $e->getMessage());
            return [];
        }
    }
}
