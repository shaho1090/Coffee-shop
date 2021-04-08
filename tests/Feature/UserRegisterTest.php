<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserRegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_user_can_register_and_get_token_in_the_response()
    {
        $registerData = [
            'name' => 'test user',
            'email' => 'test.user@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ];

        $this->postJson(route('user-register.store'), $registerData);

        $this->assertDatabaseHas('users', [
            'name' => $registerData['name'],
            'email' => $registerData['email'],
        ]);
    }

    public function test_the_name_is_required_for_registering()
    {
        $registerData = [
            'name' => '',
            'email' => 'test.user@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ];

        $this->postJson(route('user-register.store'), $registerData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "name" => [
                    0 => "The name field is required."
                ]
            ]);

        $this->assertDatabaseMissing('users', [
            'email' => $registerData['email'],
        ]);
    }

    public function test_the_name_should_be_greater_than_2_characters()
    {
        $registerData = [
            'name' => 'ab',
            'email' => 'test.user@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ];

        $this->postJson(route('user-register.store'), $registerData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "name" => [
                    0 => "The name must be at least 3 characters."
                ]
            ]);

        $this->assertDatabaseMissing('users', [
            'email' => $registerData['email'],
        ]);
    }

    public function test_the_name_should_be_string()
    {
        $registerData = [
            'name' => 111111111111,
            'email' => 'test.user@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ];

        $this->postJson(route('user-register.store'), $registerData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "name" => [
                    0 => "The name must be a string."
                ]
            ]);

        $this->assertDatabaseMissing('users', [
            'email' => $registerData['email'],
        ]);
    }

    public function test_the_name_should_be_less_than_151_character()
    {
        $registerData = [
            'name' => Str::random(151),
            'email' => 'test.user@gmail.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ];

        $this->postJson(route('user-register.store'), $registerData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "name" => [
                    0 => "The name must not be greater than 150 characters."
                ]
            ]);

        $this->assertDatabaseMissing('users', [
            'email' => $registerData['email'],
        ]);
    }

    public function test_the_email_is_required()
    {
        $registerData = [
            'name' => 'test user',
            'email' => '',
            'password' => '123456',
            'password_confirmation' => '123456'
        ];

        $this->postJson(route('user-register.store'), $registerData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "email" => [
                    0 => "The email field is required."
                ]
            ]);

        $this->assertDatabaseMissing('users', [
            'email' => $registerData['email'],
        ]);
    }

    public function test_the_email_should_be_unique()
    {
        $user = User::factory()->create();

        $registerData = [
            'name' => 'test user',
            'email' => $user->email,
            'password' => '123456',
            'password_confirmation' => '123456'
        ];

        $this->postJson(route('user-register.store'), $registerData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "email" => [
                    0 => "The email has already been taken."
                ]
            ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'test user',
        ]);
    }

    public function test_the_email_should_be_entered_in_correct_format()
    {
        $registerData = [
            'name' => 'test user',
            'email' => 'asdsdfsdf',
            'password' => '123456',
            'password_confirmation' => '123456'
        ];

        $this->postJson(route('user-register.store'), $registerData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "email" => [
                    0 => "The email must be a valid email address."
                ]
            ]);

        $this->assertDatabaseMissing('users', [
            'email' => $registerData['email'],
        ]);
    }

    public function test_the_email_should_be_less_than_151_character()
    {
        $registerData = [
            'name' => 'test user',
            'email' => Str::random(141).'@gmail.com',
            'password' => '123456',
             'password_confirmation' => '123456'
        ];

        $this->postJson(route('user-register.store'), $registerData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "email" => [
                    0 => "The email must not be greater than 150 characters."
                ]
            ]);

        $this->assertDatabaseMissing('users', [
            'email' => $registerData['email'],
        ]);
    }

    public function test_the_email_should_be_string()
    {
        $registerData = [
            'name' => 'test user',
            'email' => 1111111111111,
            'password' => '123456',
            'password_confirmation' => '123456'
        ];

        $this->postJson(route('user-register.store'), $registerData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "email" => [
                    0 => "The email must be a string."
                ]
            ]);

        $this->assertDatabaseMissing('users', [
            'email' => $registerData['email'],
        ]);
    }

    public function test_the_password_is_required()
    {
        $registerData = [
            'name' => 'test user',
            'email' => 'tes.user@gmail.com',
            'password' => ''
        ];

        $this->postJson(route('user-register.store'), $registerData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "password" => [
                    0 => "The password field is required."
                ]
            ]);

        $this->assertDatabaseMissing('users', [
            'email' => $registerData['email'],
        ]);
    }

    public function test_the_password_should_be_confirmed()
    {
        $registerData = [
            'name' => 'test user',
            'email' => 'tes.user@gmail.com',
            'password' => '123456',
            'password_confirmation' => '2345345'
        ];

        $this->postJson(route('user-register.store'), $registerData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "password" => [
                    0 => "The password confirmation does not match."
                ]
            ]);

        $this->assertDatabaseMissing('users', [
            'email' => $registerData['email'],
        ]);
    }

    public function test_the_password_should_be_greater_than_6_character()
    {
        $registerData = [
            'name' => 'test user',
            'email' => 'tes.user@gmail.com',
            'password' => '12345',
            'password_confirmation' => '12345'
        ];

        $this->postJson(route('user-register.store'), $registerData)
            ->assertJsonFragment([
                "message" => "The given data was invalid.",
                "password" => [
                    0 => "The password must be at least 6 characters."
                ]
            ]);

        $this->assertDatabaseMissing('users', [
            'email' => $registerData['email'],
        ]);
    }

}
