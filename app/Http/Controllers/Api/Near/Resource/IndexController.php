<?php

namespace App\Http\Controllers\Api\Near\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Near\Resource\IndexRequest;

use Illuminate\Http\JsonResponse;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param IndexRequest $request
     * @param $resource
     * @return JsonResponse
     */
    public function __invoke(IndexRequest $request, $resource)
    {
        return response()->json(binder()::bindNearModel($resource, $request->input('coordinates'))->paginate(20),200);
    }
}
