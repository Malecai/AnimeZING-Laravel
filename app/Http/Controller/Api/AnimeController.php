<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Anime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AnimeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = min(max($request->integer('limit', 12), 1), 50);
        $query = Anime::query()->latest('id');

        if ($request->filled('q')) {
            $query->where('title', 'like', '%' . $request->string('q') . '%');
        }

        if ($request->filled('genre')) {
            $genre = $request->string('genre')->toString();
            $query->whereJsonContains('genres', $genre);
        }

        $paginator = $query->paginate($perPage);

        return response()->json([
            'data' => $paginator->getCollection()->map(fn (Anime $anime): array => $this->resource($anime))->values(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'has_next_page' => $paginator->hasMorePages(),
                'last_visible_page' => $paginator->lastPage(),
            ],
        ]);
    }

    public function show(Anime $anime): JsonResponse
    {
        return response()->json(['data' => $this->resource($anime)]);
    }

    public function store(Request $request): JsonResponse
    {
        $anime = Anime::create($this->validated($request));

        return response()->json(['data' => $this->resource($anime)], 201);
    }

    public function update(Request $request, Anime $anime): JsonResponse
    {
        $anime->update($this->validated($request));

        return response()->json(['data' => $this->resource($anime->refresh())]);
    }

    public function destroy(Anime $anime): JsonResponse
    {
        $anime->delete();

        return response()->json(null, 204);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'genres' => ['required', 'array', 'min:1'],
            'genres.*' => ['required', 'string', 'max:80'],
            'score' => ['required', 'numeric', 'between:1,10'],
            'image_url' => ['required', 'url:http,https', 'max:2048'],
            'synopsis' => ['required', 'string', 'max:10000'],
        ]);
    }

    private function resource(Anime $anime): array
    {
        return [
            'mal_id' => $anime->id,
            'title' => $anime->title,
            'images' => [
                'jpg' => [
                    'image_url' => $anime->image_url,
                    'large_image_url' => $anime->image_url,
                ],
            ],
            'score' => (string) $anime->score,
            'type' => 'TV',
            'episodes' => null,
            'status' => 'Finished Airing',
            'year' => $anime->created_at?->year,
            'synopsis' => $anime->synopsis,
            'genres' => collect($anime->genres ?? [])->map(fn (string $genre, int $index): array => [
                'mal_id' => $index + 1,
                'name' => $genre,
            ])->values()->all(),
        ];
    }
}
