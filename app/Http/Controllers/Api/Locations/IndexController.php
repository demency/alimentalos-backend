<?php

namespace App\Http\Controllers\Api\Locations;

use App\Device;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Locations\IndexRequest;
use App\Http\Resources\LocationCollection;
use App\Pet;
use App\Repositories\HandleBindingRepository;
use App\Repositories\LocationRepository;
use App\Repositories\ModelLocationsRepository;
use App\User;
use Illuminate\Http\JsonResponse;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param IndexRequest $request
     * @return JsonResponse
     */
    public function __invoke(IndexRequest $request)
    {
        $models = HandleBindingRepository::bindResourceModelClass($request->input('type'))::whereIn(
            'uuid',
            explode(',', $request->input('identifiers'))
        )->get();

        $locations = LocationRepository::searchLocations( // Search locations
            $models, // of those devices
            $request->only('type', 'start_date', 'end_date', 'accuracy')
        );

        return response()->json(
            new LocationCollection($locations),
            200
        );
    }
}
