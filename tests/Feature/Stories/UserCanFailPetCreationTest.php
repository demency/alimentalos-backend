<?php


namespace Tests\Feature\Stories;


use Alimentalos\Relationships\Models\Pet;
use Alimentalos\Relationships\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserCanFailPetCreationTest extends TestCase
{
    use RefreshDatabase;

    final public function testUserCanFailPetCreation()
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $pet = Pet::factory()->make();
        $response = $this->actingAs($user, 'api')->json('POST', '/api/pets', [
            'photo' => UploadedFile::fake()->image('photo1.jpg'),
            'description' => $pet->description,
            'hair_color' => $pet->hair_color,
            'right_eye_color' => $pet->right_eye_color,
            'left_eye_color' => $pet->left_eye_color,
            'size' => $pet->size,
            'born_at' => $pet->born_at,
            'is_public' => true
        ]);
        $response->assertStatus(422);
    }
}
