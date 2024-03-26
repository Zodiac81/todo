<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();
//        $this->withoutExceptionHandling();
    }
    public function createUser($args = [])
    {
        return User::factory()->create($args);
    }

    public function createAuthUser()
    {
        $user = $this->createUser();
        Sanctum::actingAs($user, ['*']);
        return $user;
    }
}
