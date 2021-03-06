<?php


namespace Tests\Feature\Stories;


use Alimentalos\Relationships\Models\Device;
use Alimentalos\Relationships\Models\Geofence;
use Alimentalos\Relationships\Models\Group;
use Alimentalos\Relationships\Models\Photo;
use Alimentalos\Relationships\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCanViewGroupsOfOwnedGeofenceTest extends TestCase
{
    use RefreshDatabase;

    final public function testUserCanViewGroupOfDevice()
    {
        $user = User::factory()->create();
        $geofence = Geofence::factory()->create();
        $group = Group::factory()->create();
        $group->user_uuid = $user->uuid;
        $group->photo_uuid = Photo::factory()->create()->uuid;
        $group->save();
        $group->users()->attach($user, [
            'is_admin' => true,
            'status' => Group::ACCEPTED_STATUS
        ]);
        $geofence->user_uuid = $user->uuid;
        $geofence->save();
        $geofence->groups()->attach($group, [
            'status' => Group::ACCEPTED_STATUS
        ]);
        $this->assertDatabaseHas('groupables', [
            'groupable_type' => 'Alimentalos\\Relationships\\Models\\Geofence',
            'groupable_id' => $geofence->uuid,
            'group_uuid' => $group->uuid,
        ]);
        $response = $this->actingAs($user, 'api')->json(
            'GET',
            '/api/geofences/' . $geofence->uuid . '/groups',
            []
        );
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => [
                [
                    'uuid',
                    'user',
                    'photo',
                    'description',
                    'is_public'
                ]
            ]
        ]);
        // Assert User UUID
        $response->assertJsonFragment([
            'user_uuid' => $user->uuid,
        ]);
        // Assert Group UUID
        $response->assertJsonFragment([
            'group_uuid' => $group->uuid,
        ]);
        // Assert Photo UUID
        $response->assertJsonFragment([
            'photo_uuid' => json_decode($response->getContent())->data[0]->photo->uuid,
        ]);
        $response->assertJsonStructure([
            'current_page',
            'data' => [[
                'uuid',
                'user_uuid',
                'photo_uuid',
                'name',
                'description',
                'is_public',
                'photo_url',
                'created_at',
                'updated_at',
                'pivot' => [
                    'groupable_id',
                    'group_uuid',
                    'groupable_type',
                    'is_admin',
                    'status',
                    'sender_uuid',
                    'created_at',
                    'updated_at'
                ],
            ]],
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total'
        ]);
    }
}
