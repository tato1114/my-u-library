<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseMigrations;
    public $base_endpoint = "/api/auth";

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_user_registration(): void
    {
        Sanctum::actingAs(
            User::factory()->create()->assignRole('librarian')
        );

        $password = fake()->password(10);
        $user_mock = User::factory()->make()->toArray();
        $user_mock["role"] = "user";
        $user_mock["password"] = $password;
        $user_mock["password_confirmation"] = $password;
        $response = $this->post("$this->base_endpoint/register", $user_mock);
        $response->assertStatus(Response::HTTP_OK)->assertJsonFragment([
            'message' => 'User register successfully.',
        ]);
    }

    public function test_user_registration_validation(): void
    {
        Sanctum::actingAs(
            User::factory()->create()->assignRole('librarian')
        );

        $response = $this->post("$this->base_endpoint/register", []);
        $response->assertStatus(Response::HTTP_BAD_REQUEST)->assertJson([
            "errors" => [
                "role" => ["The role field is required."],
                "first_name" => ["The first name field is required."],
                "last_name" => ["The last name field is required."],
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
