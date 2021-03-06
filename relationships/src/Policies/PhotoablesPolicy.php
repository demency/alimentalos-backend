<?php


namespace Alimentalos\Relationships\Policies;


use Alimentalos\Contracts\Resource;
use Alimentalos\Relationships\Models\Photo;
use Alimentalos\Relationships\Models\User;
use Illuminate\Support\Str;

trait PhotoablesPolicy
{
    /**
     * Determine whether the user can attach photo to the user.
     *
     * @param User $user
     * @param Resource $model
     * @param Photo $photo
     * @return mixed
     * @throws \ReflectionException
     */
    public function attachPhoto(User $user, Resource $model, Photo $photo)
    {
        return $user->is_admin ||
            users()->isProperty($photo, $user) &&
            $user->can('view', $model) &&
            $user->can('view', $photo) &&
            !in_array($model->id, $photo->{Str::lower(Str::plural((new \ReflectionClass($model))->getShortName()))}->pluck('uuid')->toArray());
    }

    /**
     * Determine whether the user can detach photo to the user.
     *
     * @param User $user
     * @param Resource $model
     * @param Photo $photo
     * @return mixed
     * @throws \ReflectionException
     */
    public function detachPhoto(User $user, Resource $model, Photo $photo)
    {
        return $user->is_admin ||
            users()->isProperty($photo, $user) &&
            $user->can('view', $model) &&
            $user->can('view', $photo) &&
            in_array($model->uuid, $photo->{Str::lower(Str::plural((new \ReflectionClass($model))->getShortName()))}->pluck('uuid')->toArray());
    }
}
