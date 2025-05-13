<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service for interacting with the New York Times Best Sellers API.
 */
class NYTService
{
    /**
     * NYT API base URI.
     */
    protected string $baseUri;

    /**
     * NYT API key.
     */
    protected string $apiKey;

    /**
     * NYTService constructor.
     */
    public function __construct()
    {
        $this->baseUri = config('nyt.base_uri');
        $this->apiKey = config('nyt.api_key');
    }

    /**
     * Fetch best sellers from NYT API with optional filters.
     *
     * @param  array  $filters  Query parameters: author, title, isbn, offset
     * @return array|null NYT API response or error structure
     */
    public function getBestSellers(array $filters = []): ?array
    {
        $endpoint = '/lists/best-sellers/history.json';
        $query = array_filter([
            'api-key' => $this->apiKey,
            'author' => $filters['author'] ?? null,
            'title' => $filters['title'] ?? null,
            'isbn' => $filters['isbn'] ?? null,
            'offset' => $filters['offset'] ?? null,
        ]);

        $cacheKey = 'nyt:'.md5(json_encode($query));

        return Cache::remember($cacheKey, 3600, function () use ($endpoint, $query) {
            try {
                $response = Http::get($this->baseUri.$endpoint, $query);
                if ($response->successful()) {
                    return $response->json();
                }
                Log::error('NYT API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [
                    'error' => 'NYT API error',
                    'status' => $response->status(),
                    'body' => $response->json(),
                ];
            } catch (\Exception $e) {
                Log::error('NYT API exception', [
                    'message' => $e->getMessage(),
                ]);

                return [
                    'error' => 'NYT API exception',
                    'message' => $e->getMessage(),
                ];
            }
        });
    }
}
