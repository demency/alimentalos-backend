<?php


namespace Tests\Feature\Stories;


use App\Models\Comment;
use App\Models\Geofence;
use App\Models\Group;
use App\Models\Pet;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserCanDetachPhotoToGeofenceTest extends TestCase
{
    use RefreshDatabase;

    final public function testUserCanAttachPhotoToGeofence()
    {
        $user = User::factory()->create();
        $geofence = Geofence::factory()->create();
        $photo = Photo::factory()->create();

        $geofence->user_uuid = $user->uuid;
        $photo->user_uuid = $user->uuid;
        $photo->save();
        $geofence->save();
        $geofence->photos()->attach($photo->uuid);
        $response = $this->actingAs($user, 'api')->json('POST', '/api/geofences/' . $geofence->uuid . '/photos/' . $photo->uuid . '/detach');
        $response->assertOk();
        $response->assertExactJson(['message' => 'Resource detached to photo successfully']);
        $this->assertDeleted('photoables', [
            'photoable_type' => 'App\\Models\\Geofence',
            'photoable_id' => $geofence->uuid,
            'photo_uuid' => $photo->uuid,
        ]);
    }
}
