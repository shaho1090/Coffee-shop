<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserLoginLogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var string[]
     */
    private $registerData;

    public function setUp(): void
    {
        parent::setUp();

        $this->registerData = [
            'name' => 'test user',
            'email' => 'test.user@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ];
    }

    public function register_a_user()
    {
        $this->postJson(route('user-register.store'), $this->registerData);

        $this->assertDatabaseHas('users', [
            'name' => $this->registerData['name'],
            'email' => $this->registerData['email'],
        ]);
    }

    public function register_a_user_and_logout()
    {
        $this->register_a_user();

        $this->postJson(route('user-login.destroy'));
    }

    public function test_a_registered_user_can_logout()
    {
        $this->withoutExceptionHandling();

        $this->register_a_user();

        Sanctum::actingAs(User::where('email', $this->registerData['email'])->first());

        $this->postJson(route('user-login.destroy'))->assertJsonFragment([
            "massage" => "logged out successfully!"
        ]);
    }

    public function test_a_registered_user_can_login_and_can_pass_sanctum_middleware()
    {
        $this->register_a_user_and_logout();

        $this->assertDatabaseHas('users', [
            'name' => $this->registerData['name'],
            'email' => $this->registerData['email'],
        ]);

        $loginData = [
            'email' => $this->registerData['email'],
            'password' => $this->registerData['password']
        ];

        Sanctum::actingAs(User::where('email', $this->registerData['email'])->first());

        $this->postJson(route('user-login.store'), $loginData);

        $this->getJson('/api/user')->assertJsonFragment([
            "name" => $this->registerData['name'],
            "email" => $this->registerData['email'],
        ]);
    }
}

