<?php

namespace Demency\Relationships\Asserts;

use Demency\Relationships\Models\Group;
use Demency\Relationships\Models\User;

trait GroupAssert
{
    /**
     * Assert group has the user as administrator.
     *
     * @param object|User $user
     * @param object|Group $group
     * @return bool
     */
    public function hasAdministrator(Group $group, User $user)
    {
        return $user->uuid === $group->user_uuid || $user->groups()->whereIn('status', [Group::ACCEPTED_STATUS, Group::ATTACHED_STATUS])->where('is_admin', true)->exists();
    }
}
