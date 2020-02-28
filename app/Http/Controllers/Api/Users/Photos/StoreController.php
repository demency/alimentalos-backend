<?php

namespace App\Http\Controllers\Api\Users\Photos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Users\Photos\StoreRequest;
use App\Repositories\LocationRepository;
use App\Repositories\PhotoRepository;
use App\User;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\JsonResponse;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param StoreRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function __invoke(StoreRequest $request, User $user)
    {
        $photo = PhotoRepository::createPhotoViaRequest($request);
        $user = LocationRepository::updateModelLocation($request, $user);
        $photo->users()->attach($user->id);
        $photo->load('user', 'comment');
        return response()->json(
            $photo,
            200
        );
    }
}
