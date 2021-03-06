<?php

namespace Alimentalos\Relationships\Resources;

use Alimentalos\Relationships\Models\Photo;
use Alimentalos\Relationships\Rules\Coordinate;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait PhotoResource
{
    /**
     * @return array
     */
    public function fields() : array
    {
        return [
            'uuid',
            'user_uuid',
            'ext',
            'photo_url',
            'is_public',
            'created_at',
            'updated_at',
            'love_reactant_id',
        ];
    }

    /**
     * Update photo via request.
     *
     * @return Photo
     */
    public function updateViaRequest()
    {
        return photos()->update($this);
    }

    /**
     * Get available photo reactions.
     *
     * @return string
     */
    public function getAvailableReactions()
    {
        return 'Love,Pray,Like,Dislike,Sad,Hate';
    }

    /**
     * Update photo validation rules.
     *
     * @return array
     */
    public function updateRules()
    {
        return [];
    }

    /**
     * Store photo validation rules.
     *
     * @return array
     */
    public function storeRules()
    {
        return [
            'photo' => 'required',
            'is_public' => 'required|boolean',
            'coordinates' => [new Coordinate()],
        ];
    }

    /**
     * Get photo relationships using lazy loading.
     *
     * @return array
     */
    public function getLazyRelationshipsAttribute()
    {
        return ['user', 'comment'];
    }

    /**
     * Get photo instances.
     *
     * @return LengthAwarePaginator
     */
    public function getInstances()
    {
        return Photo::latest()->paginate(20);
    }
}
