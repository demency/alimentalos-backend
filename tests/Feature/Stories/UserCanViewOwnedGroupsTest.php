<?php


namespace Tests\Feature\Stories;


use Alimentalos\Relationships\Models\Group;
use Alimentalos\Relationships\Models\Photo;
use Alimentalos\Relationships\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCanViewOwnedGroupsTest extends TestCase
{
    use RefreshDatabase;

    final public function testUserCanViewOwnedGroups()
    {
        $user = User::factory()->create();
        $group = Group::factory()->create();
        $group->photo_uuid = Photo::factory()->create()->uuid;
        $group->user_uuid = $user->uuid;
        $group->save();
        $user->groups()->attach($group->uuid, [
            'status' => Group::ACCEPTED_STATUS,
            'is_admin' => false,
        ]);
        $response = $this->actingAs($user, 'api')->json('GET', '/api/users/' . $user->uuid . '/groups');
        $response->assertOk();
        $response->assertJsonStructure([
            'current_page',
            'data' => [
                [
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
                        'updated_at',
                    ]
                ],
            ],
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total',
        ]);
        $response->assertJsonFragment([
            'uuid' => $group->uuid,
            'user_uuid' => $user->uuid,
        ]);
    }
}
