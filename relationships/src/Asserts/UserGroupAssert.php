<?php


namespace Alimentalos\Relationships\Asserts;


use Alimentalos\Relationships\Models\Group;
use Alimentalos\Relationships\Models\User;

trait UserGroupAssert
{
    /**
     * Assert user has group in rejected status.
     *
     * @param User $user
     * @param Group $group
     * @return bool
     */
    public function hasRejected(User $user, Group $group)
    {
        return $user->groups()
            ->where('group_uuid', $group->uuid)
            ->where('status', Group::REJECTED_STATUS)
            ->exists();
    }
}
