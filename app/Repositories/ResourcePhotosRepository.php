<?php

namespace App\Repositories;

use App\Contracts\Resource;
use App\Models\Photo;

class ResourcePhotosRepository
{
    /**
     * Create resource photo.
     *
     * @param Resource $resource
     * @return Photo
     */
    public function create(Resource $resource)
    {
        $photo = photos()->create();
        $resource = resourceLocations()->updateLocation($resource);
        $resource->photos()->attach($photo->uuid);
        return $photo;
    }
}