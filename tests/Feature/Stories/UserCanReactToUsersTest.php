<?php


namespace Tests\Feature\Stories;


use Alimentalos\Relationships\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCanReactToUsersTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanReactToUsers()
    {
        $this->artisan('love:reaction-type-add --name=Hate --mass=-4')
            ->assertExitCode(0);
        $this->artisan('love:reaction-type-add --name=Sad --mass=-2')
            ->assertExitCode(0);
        $this->artisan('love:reaction-type-add --name=Dislike --mass=-1')
            ->assertExitCode(0);
        $this->artisan('love:reaction-type-add --name=Like --mass=1')
            ->assertExitCode(0);
        $this->artisan('love:reaction-type-add --name=Pray --mass=2')
            ->assertExitCode(0);
        $this->artisan('love:reaction-type-add --name=Love --mass=5')
            ->assertExitCode(0);
        $this->artisan('love:reaction-type-add --name=Follow --mass=1')
            ->assertExitCode(0);
        $user = User::factory()->create();
        $userB = User::factory()->create();
        $response = $this->actingAs($user, 'api')
            ->json('POST', '/api/users/' . $userB->uuid . '/reactions', [
                'type' => 'Follow',
            ]);
        $response->assertOk();
        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/users/' . $userB->uuid . '/reactions');
        $response->assertOk();
        $response->assertJsonStructure([
            'reactable' => [
                'follow',
            ]
        ]);
        $response->assertJsonFragment([
            'follow' => true
        ]);
        $response = $this->actingAs($user, 'api')
            ->json('POST', '/api/users/' . $userB->uuid . '/reactions', [
                'type' => 'Follow',
            ]);
        $response->assertOk();
        $response = $this->actingAs($user, 'api')
            ->json('GET', '/api/users/' . $userB->uuid . '/reactions');
        $response->assertOk();
        $response->assertJsonStructure([
            'reactable' => [
                'follow',
            ]
        ]);
        $response->assertJsonFragment([
            'follow' => false
        ]);
    }
}
