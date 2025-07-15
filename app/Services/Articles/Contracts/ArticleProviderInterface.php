<?php

namespace App\Services\Articles\Contracts;

interface ArticleProviderInterface
{
    public function fetchArticles(): array;
}
