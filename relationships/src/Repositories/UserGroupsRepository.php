<?php


namespace Alimentalos\Relationships\Repositories;

use Alimentalos\Relationships\Asserts\UserGroupAssert;
use Alimentalos\Relationships\Models\Group;
use Alimentalos\Relationships\Models\User;

class UserGroupsRepository
{
    use UserGroupAssert;

    /**
     * Invite User into Group.
     *
     * @param User $user
     * @param Group $group
     */
    public function invite(User $user, Group $group)
    {
        $this->hasRejected($user, $group) ?
            $user->groups()->updateExistingPivot($group->uuid, $this->attributes()) :
            $user->groups()->attach($group->uuid, $this->attributes());
    }

    /**
     * Retrieve user invitation attach default attributes.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'status' => Group::PENDING_STATUS,
            'is_admin' => fill('is_admin', false)
        ];
    }
}
