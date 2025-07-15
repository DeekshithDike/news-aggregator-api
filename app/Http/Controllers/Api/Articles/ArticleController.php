<?php

namespace App\Http\Controllers\Api\Articles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Articles\Contracts\ArticleRepositoryInterface;
use App\Http\Resources\Articles\ArticleResource;
use App\Http\Requests\Articles\ArticleFilterRequest;
use App\Traits\ApiResponse;

class ArticleController extends Controller
{
    use ApiResponse;
    
    public function index(ArticleFilterRequest $request, ArticleRepositoryInterface $repository) {
        try {
            $articles = $repository->filter($request->validated());

            return $this->successResponse(
                ArticleResource::collection($articles),
                'Articles fetched successfully',
                [
                    'current_page' => $articles->currentPage(),
                    'last_page' => $articles->lastPage(),
                    'per_page' => $articles->perPage(),
                    'total' => $articles->total(),
                    'has_more_pages' => $articles->hasMorePages(),
                    'next_page_url' => $articles->nextPageUrl(),
                    'prev_page_url' => $articles->previousPageUrl(),
                ]
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Failed to fetch articles',
                [$e->getMessage()],
                500
            );
        }
    }
}
