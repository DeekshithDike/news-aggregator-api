<?php

namespace App\Console\Commands\Articles;

use Illuminate\Console\Command;
use App\Repositories\Articles\Contracts\ArticleRepositoryInterface;
use App\Services\Articles\Aggregator\ArticleAggregator;
use App\Services\Articles\Providers\NewsApiService;
use App\Services\Articles\Providers\GuardianApiService;
use App\Services\Articles\Providers\NewYorkTimesApiService;

class FetchArticlesCommand extends Command
{
    protected $signature = 'fetch:articles';
    protected $description = 'Fetch latest articles from external news sources';

    
    public function handle(ArticleRepositoryInterface $repo)
    {
        $aggregator = new ArticleAggregator([
            new NewsApiService(),
            new GuardianApiService(),
            new NewYorkTimesApiService(),
        ]);

        foreach ($aggregator->fetchAll() as $article) {
            $repo->save($article);
        }

        $this->info("Articles fetched and stored successfully.");
    }
}
