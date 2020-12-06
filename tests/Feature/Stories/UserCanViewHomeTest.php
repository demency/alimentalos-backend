<?php


namespace Tests\Feature\Stories;


use Alimentalos\Relationships\Models\Alert;
use Alimentalos\Relationships\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCanViewHomeTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanHome()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/home');
        $response->assertOk();
    }

    public function testUserRedirectedIfVisitLogin()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user)->get('/login');
        $response->assertRedirect('home');
    }
}
