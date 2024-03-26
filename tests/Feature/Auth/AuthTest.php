<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_register()
    {
        $this->postJson(route('user.signup'), [
            'name' => "John Doe",
            'email' => 'test@test.com',
            'password' => 'secret123',
        ])->assertCreated();

        $this->assertDatabaseHas('users', ['name' => 'John Doe']);
    }

    public function test_user_can_login_with_email_and_password_and_get_token()
    {
        $user = $this->createUser();

        $response = $this->postJson(route('user.getToken'), [
            'email' => $user->email,
            'password' => 'password'
        ])->assertOk();

        $this->assertArrayHasKey('token', $response->json('payload'));
    }

    public function test_user_email_is_incorrect()
    {
        $this->postJson(route('user.getToken'), [
            'email'     => 'test@test.com',
            'password'  => 'password'
        ])->assertStatus(404);
    }

    public function test_user_password_is_incorrect()
    {
        $user = $this->createUser();

        $this->postJson(route('user.getToken'), [
            'email'     => $user->email,
            'password'  => 'some_string'
        ])->assertStatus(422);
    }

    public function test_user_can_logout()
    {
        $this->createAuthUser();
        $this->postJson(route('user.logout'))->assertOk();
    }
}
