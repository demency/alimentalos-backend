<?php

namespace Alimentalos\Relationships\Procedures;

use Alimentalos\Relationships\Models\Photo;
use Illuminate\Support\Facades\Storage;

trait PhotoProcedure
{
    /**
     * Create photo.
     *
     * @return Photo
     */
    public function createInstance()
    {
        $uuid = uuid();
        $photo = Photo::create([
            'uuid' => $uuid,
            'photo_url' => 'photos/' . $uuid . '.' . uploaded('photo')->extension(),
            'ext' => uploaded('photo')->extension(),
            'is_public' => fill('is_public', true),
            'title' => fill('title', null),
            'body' => fill('body', null),
            'location' => rhas('coordinates') ? parser()->pointFromCoordinates(input('coordinates')) : null,
        ]);
        return $photo;
    }


    /**
     * Store photo.
     *
     * @param $uniqueName
     * @param $fileContent
     */
    public function storePhoto($uniqueName, $fileContent)
    {
        Storage::disk(static::DEFAULT_DISK)
            ->putFileAs(
                static::DEFAULT_PHOTOS_DISK_PATH,
                $fileContent,
                ($uniqueName . '.' . $fileContent->extension())
            );
    }
}
