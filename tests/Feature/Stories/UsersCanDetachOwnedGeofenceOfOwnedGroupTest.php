<?php


namespace Tests\Feature\Stories;


use App\Geofence;
use App\Group;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersCanDetachOwnedGeofenceOfOwnedGroupTest extends TestCase
{
    use RefreshDatabase;

    public function testUsersCanDetachOwnedGeofenceOfOwnedGroup()
    {
        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();
        $geofence = factory(Geofence::class)->create();
        $geofence->user_uuid = $user->uuid;
        $geofence->save();
        $group->user_uuid = $user->uuid;
        $group->save();
        $group->geofences()->attach($geofence);
        $response = $this->actingAs($user, 'api')->json(
            'POST',
            '/api/groups/' . $group->uuid . '/geofences/' . $geofence->uuid . '/detach',
            []
        );
        $response->assertOk();
        $this->assertDeleted('groupables', [
            'groupable_id' => $geofence->uuid,
            'groupable_type' => 'App\\Geofence',
            'group_uuid' => $group->uuid,
        ]);
    }
}