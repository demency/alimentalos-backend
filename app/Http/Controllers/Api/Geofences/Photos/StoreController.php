<?php

namespace App\Http\Controllers\Api\Geofences\Photos;

use App\Geofence;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Geofences\Photos\StoreRequest;
use App\Repositories\PhotoRepository;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param StoreRequest $request
     * @param Geofence $geofence
     * @return JsonResponse
     */
    public function __invoke(StoreRequest $request, Geofence $geofence)
    {
        // TODO - Remove unnecessary complexity
        $photo = PhotoRepository::createPhoto(
            $request->user('api'),
            $request->file('photo'),
            $request->input('title'),
            $request->input('body'),
            $request->input('is_public'),
            $request->input('coordinates')
        );

        $photo->geofences()->attach($geofence->id);

        $photo->load('user', 'comment');

        return response()->json(
            $photo,
            200
        );
    }
}
