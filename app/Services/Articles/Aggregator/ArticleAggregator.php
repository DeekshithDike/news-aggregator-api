<?php

namespace App\Services\Articles\Aggregator;

class ArticleAggregator
{
    protected array $providers;
    
    
    public function __construct(array $providers)
    {
        $this->providers = $providers;
    }

    public function fetchAll(): array {
        $all = [];
        foreach ($this->providers as $provider) {
            $all = array_merge($all, $provider->fetchArticles());
        }
        return $all;
    }
}
