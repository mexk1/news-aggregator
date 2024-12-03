<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\News\Contracts\NewsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{
    public function __construct(
        private readonly NewsRepository $newsRepository
    )
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     * 
     * @OA\Get(
     *     path="/api/news/search",
     *     summary="Search news",
     *     tags={"News"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="categories",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="authors",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="from",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="to",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/News"))
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */
    public function search(Request $request): JsonResponse|Response
    {
        try {
            $from = $request->get('from');
            $to = $request->get('to');

            if ($from) {
                $from = \DateTimeImmutable::createFromFormat('Y-m-d', $from);
            }

            if ($to) {
                $to = \DateTimeImmutable::createFromFormat('Y-m-d', $to);
            }

            $result = $this->newsRepository->search(
                page: $request->get('page', 1),
                perPage: $request->get('perPage', 10),
                query: $request->get('query'),
                categories: explode(',', $request->get('categories') ?? ''),
                authors: explode(',', $request->get('authors') ?? ''),
                from: $from,
                to: $to,
            );

            return response()->json($result);
        } catch (\Exception $exception) {
            Log::error($exception->getMessage(), compact('exception'));
        }

        return response()->json([], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    
    /**
     * Retrieve the details of a news item by its ID.
     *
     * @OA\Get(
     *     path="/api/news/{id}",
     *     summary="Get news details",
     *     description="Fetch the details of a news item by its ID.",
     *     operationId="getNewsDetails",
     *     tags={"News"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the news item",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/News"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="News item not found"
     *     )
     * )
     *
     * @param string $id ID of the news item
     * @return JsonResponse|Response JSON response containing the news item details or an error message
     */
    public function details(string $id): JsonResponse|Response
    {
        return response()->json($this->newsRepository->getById((int) $id));
    }
}
