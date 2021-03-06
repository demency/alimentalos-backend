<?php

namespace App\Http\Controllers\Near\Resource;

use Alimentalos\Contracts\Resource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Near\Resource\IndexRequest;
use Illuminate\Http\JsonResponse;

class IndexController extends Controller
{
    /**
     * @OA\Get(
     *      path="/near/{type}",
     *      operationId="getNearResources",
     *      tags={"Near"},
     *      summary="Get near resources.",
     *      description="Returns the near located resources paginated by a default quantity, payload includes pagination links and stats.",
     *      @OA\Parameter(
     *          name="coordinates",
     *          description="Comma-separated latitude and longitude",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *         name="type",
     *         in="path",
     *         description="Resource type that need to be considered",
     *         required=true,
     *         @OA\Schema(
     *         type="string",
     *           @OA\Items(
     *               type="string",
     *               enum={"users", "devices", "pets", "alerts", "photos", "geofences", "places"},
     *               default="pets"
     *           ),
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Near resources retrieved successfully"
     *       ),
     *      @OA\Response(response=400, description="Bad request")
     * )
     * Retrieve paginated near instances.
     *
     * @param IndexRequest $request
     * @param $resource
     * @return JsonResponse
     */
    public function __invoke(IndexRequest $request, Resource $resource)
    {
        $class = get_class($resource);
        $resources = finder()->findNearResources($resource, input('coordinates'));
        $resources->whereNotNull($class::DEFAULT_LOCATION_FIELD);
        return response()->json($resources->paginate(20),200);
    }
}
