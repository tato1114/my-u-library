<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class BookTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    public function test_user_can_see_list_of_books(): void
    {
        Sanctum::actingAs(
            User::factory()->create()->assignRole('user')
        );

        $response = $this->getJson(route('books.index'));
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(([
                'current_page',
                'data' => [['id', 'title', 'slug', 'author', 'published_year', 'genre', 'stock']]
            ]));
    }

    public function test_user_can_see_single_book(): void
    {
        Sanctum::actingAs(
            User::factory()->create()->assignRole('user')
        );

        $book = Book::inRandomOrder()->first();

        $response = $this->getJson(route('books.show', ['book' => $book->id]));
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure((['id', 'title', 'slug', 'author', 'published_year', 'genre', 'stock']));
    }

    public function test_user_cant_store_a_book(): void
    {
        Sanctum::actingAs(
            User::factory()->create()->assignRole('user')
        );

        $book = Book::factory()->make();

        $response = $this->postJson(route('books.store'), $book->toArray());
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_user_cant_update_a_book(): void
    {
        Sanctum::actingAs(
            User::factory()->create()->assignRole('user')
        );

        $book = Book::inRandomOrder()->first();
        $mock_book = Book::factory()->make();

        $response = $this->putJson(route('books.update', ['book' => $book->id]), $mock_book->toArray());
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_user_cant_delete_a_book(): void
    {
        Sanctum::actingAs(
            User::factory()->create()->assignRole('user')
        );

        $book = Book::inRandomOrder()->first();

        $response = $this->deleteJson(route('books.destroy', ['book' => $book->id]));
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_librarian_can_see_list_of_books(): void
    {
        Sanctum::actingAs(
            User::factory()->create()->assignRole('librarian')
        );

        $response = $this->get(route('books.index'));
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'current_page',
                'data' => [['id', 'title', 'slug', 'author', 'published_year', 'genre', 'stock']]
            ]);
    }

    public function test_librarian_can_see_single_book(): void
    {
        Sanctum::actingAs(
            User::factory()->create()->assignRole('librarian')
        );

        $book = Book::inRandomOrder()->first();

        $response = $this->getJson(route('books.show', ['book' => $book->id]));
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure((['id', 'title', 'slug', 'author', 'published_year', 'genre', 'stock']));
    }

    public function test_librarian_can_store_single_book(): void
    {
        Sanctum::actingAs(
            User::factory()->create()->assignRole('librarian')
        );

        $book = Book::factory()->make();

        $response = $this->postJson(route('books.store'), $book->toArray());
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure((['id', 'title', 'slug', 'author', 'published_year', 'genre', 'stock']));
    }

    public function test_librarian_can_update_a_book(): void
    {
        Sanctum::actingAs(
            User::factory()->create()->assignRole('librarian')
        );

        $book = Book::inRandomOrder()->first();
        $mock_book = Book::factory()->make();

        $response = $this->putJson(route('books.update', ['book' => $book->id]), $mock_book->toArray());
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure((['id', 'title', 'slug', 'author', 'published_year', 'genre', 'stock']));
    }

    public function test_librarian_can_delete_a_book(): void
    {
        Sanctum::actingAs(
            User::factory()->create()->assignRole('librarian')
        );

        $book = Book::inRandomOrder()->first();

        $response = $this->deleteJson(route('books.destroy', ['book' => $book->id]));
        $response->assertStatus(Response::HTTP_OK);
    }
}
