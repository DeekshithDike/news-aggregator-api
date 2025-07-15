<?php

namespace App\Services\Articles\Providers;

use Illuminate\Support\Facades\Http;
use App\Services\Articles\Contracts\ArticleProviderInterface;

class GuardianApiService implements ArticleProviderInterface
{
    public function fetchArticles(): array {
        try {
            $response = Http::get(config('services.guardian.base_url') . '/search', [
                'api-key' => config('services.guardian.key'),
                'show-fields' => 'trailText',
            ]);

            $articles = [];
            if ($response->successful()) {
                $data = $response->json();

                foreach ($data['response']['results'] as $item) {
                    $articles[] = [
                        'title' => $item['webTitle'],
                        'description' => $item['fields']['trailText'],
                        'url' => $item['webUrl'],
                        'source' => 'guardian',
                        'published_at' => date('Y:m:d H:m:s', strtotime($item['webPublicationDate'])),
                    ];
                }
            }

            return $articles;
        } catch (\Throwable $e) {
            logger()->error('GuardianApi fetch failed: ' . $e->getMessage());
            return [];
        }
    }
}
