<?php

namespace App\Repositories\Articles\Contracts;

interface ArticleRepositoryInterface
{
    public function save(array $data): void;
    public function filter(array $filters);
}
