<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public $base_endpoint = "/api/auth";

    public function test_user_registration(): void
    {
        $password = fake()->password(10);
        $user_mock = [
            "name" => fake()->name(),
            "email" => fake()->email(),
            "password" => $password,
            "password_confirmation" => $password
        ];
        $response = $this->post("$this->base_endpoint/register", $user_mock);
        $response->assertStatus(200)->assertJsonFragment([
            'message' => 'User register successfully.',
        ]);
    }

    public function test_user_registration_validation(): void
    {
        $user_mock = ["name" => "", "email" => "", "password" => ""];
        $response = $this->post("$this->base_endpoint/register", $user_mock);
        $response->assertStatus(400)->assertJson([
            "errors" => [
                "name" => ["The name field is required."],
                "email" => ["The email field is required."],
                "password" => ["The password field is required."]
            ]
        ]);
    }

    public function test_user_login(): void
    {
        $user = User::factory()->create();
        $user_mock = ["email" => $user->email, "password" => "Password1$"];
        $response = $this->post("$this->base_endpoint/login", $user_mock);
        $response->assertStatus(200)->assertJsonFragment([
            'message' => 'User login successfully.'
        ]);
    }
}
