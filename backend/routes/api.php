<?php

use App\Http\Controllers\Api\AnimeController;
use Illuminate\Support\Facades\Route;

Route::apiResource('animes', AnimeController::class);

Route::get('genres', function () {
    return response()->json([
        'data' => collect([
            'Action',
            'Adventure',
            'Comedy',
            'Drama',
            'Fantasy',
            'Horror',
            'Romance',
            'Sci-Fi',
            'Slice of Life',
            'Supernatural',
            'Thriller',
        ])->map(fn (string $name, int $index): array => [
            'mal_id' => $index + 1,
            'name' => $name,
        ])->values(),
    ]);
});
