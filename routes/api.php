<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Articles\ArticleController;

// Prefix starts with /api (E.g. https://example.com/api)
Route::prefix('v1')->group(function () {
    Route::get('/articles', [ArticleController::class, 'index']);
});