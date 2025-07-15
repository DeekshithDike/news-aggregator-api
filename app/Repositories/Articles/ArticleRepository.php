<?php

namespace App\Repositories\Articles;

use App\Models\Article;
use App\Repositories\Articles\Contracts\ArticleRepositoryInterface;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function save(array $data): void {
        Article::updateOrCreate(['url' => $data['url']], $data);
    }

    public function filter(array $filters) {
        $query = Article::query()
            ->select('id', 'title', 'description', 'url', 'source', 'published_at');

        // Single keyword in title or description
        if (!empty($filters['keyword'])) {
            $keyword = trim($filters['keyword']);
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // Filter by user preferred sources (newsapi, guardian, nytimes)
        if (!empty($filters['preferred_sources'])) {
            $sources = array_map('trim', explode(',', $filters['preferred_sources']));
            $query->whereIn('source', $sources);
        }

        // Date range filtering
        if (!empty($filters['published_from'])) {
            $query->whereDate('published_at', '>=', $filters['published_from']);
        }

        if (!empty($filters['published_to'])) {
            $query->whereDate('published_at', '<=', $filters['published_to']);
        }
        
        // Pagination
        $perPage = isset($filters['limit']) ? (int) $filters['limit'] : 20;
        $page = isset($filters['page']) ? (int) $filters['page'] : 1;

        return $query->orderByDesc('published_at')
                     ->paginate($perPage, page: $page);
    }
}
