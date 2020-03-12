<?php


namespace Tests\Feature\Stories;


use App\Geofence;
use App\Group;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCanViewGeofenceListOfGroupTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanViewGeofenceListOfGroup()
    {
        $user = factory(User::class)->create();
        $group = factory(Group::class)->create();
        $geofence = factory(Geofence::class)->create();
        $group->user_uuid = $user->uuid;
        $group->save();
        $geofence->user_uuid = $user->uuid;
        $geofence->save();
        $group->geofences()->attach($geofence);
        $response = $this->actingAs($user, 'api')->json('GET', '/api/groups/' . $group->uuid . '/geofences');
        $response->assertJsonStructure([
            'data' => [
                [
                    'uuid',
                    'user_uuid',
                    'photo_uuid',
                    'name',
                    'description',
                    'shape' => [
                        'type',
                        'coordinates'
                    ],
                    'is_public',
                    'created_at',
                    'updated_at',
                    'pivot' => [
                        'groupable_id',
                        'groupable_type',
                        'group_uuid',
                    ]
                ]
            ]
        ]);
        $response->assertOk();
    }
}
