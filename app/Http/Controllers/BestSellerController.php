<?php

namespace App\Http\Controllers;

use App\Http\Requests\BestSellerRequest;
use App\Services\NYTService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *     title="NYT Best Sellers API",
 *     version="1.0.0",
 *     description="API wrapper for the New York Times Best Sellers History API"
 * )
 */

/**
 * Controller for handling NYT Best Sellers API requests.
 */
class BestSellerController extends Controller
{
    /**
     * NYTService instance.
     */
    protected NYTService $nytService;

    /**
     * Inject NYTService.
     */
    public function __construct(NYTService $nytService)
    {
        $this->nytService = $nytService;
    }

    /**
     * Handle the incoming request for best sellers.
     *
     * @OA\Get(
     *     path="/api/v1/best-sellers",
     *     summary="Get NYT best sellers history",
     *     tags={"Best Sellers"},
     *     @OA\Parameter(
     *         name="author",
     *         in="query",
     *         description="Author name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="title",
     *         in="query",
     *         description="Book title",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="isbn",
     *         in="query",
     *         description="Book ISBN",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         description="Pagination offset",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=0)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="results", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=502,
     *         description="NYT API error"
     *     )
     * )
     *
     * @param \App\Http\Requests\BestSellerRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(BestSellerRequest $request): JsonResponse
    {
        $result = $this->nytService->getBestSellers($request->validated());
        if (isset($result['error'])) {
            $status = $result['status'] ?? 502;

            return response()->json([
                'error' => $result['error'],
                'details' => $result['body'] ?? $result['message'] ?? null,
            ], $status);
        }

        return response()->json($result);
    }
}
