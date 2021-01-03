<?php


namespace Tests\Feature\Stories;


use Alimentalos\Relationships\Models\Geofence;
use Alimentalos\Relationships\Models\User;
use Grimzy\LaravelMysqlSpatial\Types\LineString;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Grimzy\LaravelMysqlSpatial\Types\Polygon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChildCanViewOwnerGeofencesTest extends TestCase
{
    use RefreshDatabase;

    public function testChildCanViewOwnerGeofences()
    {
        $user = User::factory()->create();
        $owner = User::factory()->create();
        $geofence = new Geofence();
        $geofence->name = "Geofence";
        $geofence->user_uuid = $owner->uuid;
        $geofence->uuid = uuid();
        $geofence->shape = new Polygon([new LineString([
            new Point(0, 0),
            new Point(0, 5),
            new Point(5, 5),
            new Point(5, 0),
            new Point(0, 0)
        ])]);
        $geofence->save();
        $user->user_uuid = $owner->uuid;
        $user->save();
        $response = $this->actingAs($user, 'api')->json('GET', '/api/geofences');
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
                ]
            ]
        ]);
        $response->assertOk();
    }
}
