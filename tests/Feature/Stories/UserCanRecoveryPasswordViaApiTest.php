<?php


namespace Tests\Feature\Stories;


use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCanRecoveryPasswordViaApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * testUserCanRecoveryPasswordViaApi
     */
    public function testUserCanRecoveryPasswordViaApi()
    {
        $user = factory(User::class)->create();
        $response = $this->json('POST', '/api/password-recovery', [
            'email' => $user->email,
        ]);
        $response->assertOk();
    }
}
