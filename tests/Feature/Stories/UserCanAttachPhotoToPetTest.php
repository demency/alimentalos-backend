<?php


namespace Tests\Feature\Stories;


use Alimentalos\Relationships\Models\Comment;
use Alimentalos\Relationships\Models\Pet;
use Alimentalos\Relationships\Models\Photo;
use Alimentalos\Relationships\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserCanAttachPhotoToPetTest extends TestCase
{
    use RefreshDatabase;

    final public function testUserCanAttachPhotoToPet()
    {
        Storage::fake('public');
        $user = factory(User::class)->create();
        $pet = factory(Pet::class)->create();
        $photo = factory(Photo::class)->create();

        $photo->user_uuid = $user->uuid;
        $photo->save();
        $pet->user_uuid = $user->uuid;
        $pet->photo_uuid = $photo->uuid;
        $pet->save();
        $response = $this->actingAs($user, 'api')->json('POST', '/api/pets/' . $pet->uuid . '/photos/' . $photo->uuid . '/attach');
        $response->assertOk();
        $this->assertDatabaseHas('photoables', [
            'photoable_type' => 'Alimentalos\\Relationships\\Models\\Pet',
            'photoable_id' => $pet->uuid,
            'photo_uuid' => $photo->uuid,
        ]);
    }
}