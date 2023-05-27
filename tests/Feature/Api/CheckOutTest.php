<?php

namespace Tests\Feature\Api;

use App\Models\Book;
use App\Models\CheckOut;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class CheckOutTest extends TestCase
{
    use DatabaseMigrations;

    private $user;
    private $librarian;
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->user = User::factory()->create()->assignRole('user');
        $this->librarian = User::factory()->create()->assignRole('librarian');
    }

    public function test_user_can_see_list_of_check_outs(): void
    {
        CheckOut::factory(10)->create(['user_id' => $this->user->id]);
        Sanctum::actingAs(
            $this->user
        );

        $response = $this->getJson(route('check_outs.index'));
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'current_page',
                'data' => [
                    [
                        'book_id',
                        'user_id',
                        'book' => [
                            'id',
                            'title',
                            'slug',
                            'author',
                            'published_year',
                            'genre',
                            'stock'
                        ]
                    ]
                ]
            ]);
        $ids_count = collect($response['data'])->pluck('user_id')->unique()->count();
        $this->assertEquals(1, $ids_count);
    }

    public function test_user_can_see_single_check_out(): void
    {
        Sanctum::actingAs(
            $this->user
        );

        $check_out = CheckOut::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson(route('check_outs.show', ['check_out' => $check_out->id]));
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'book_id',
                'user_id',
                'book' => [
                    'id',
                    'title',
                    'slug',
                    'author',
                    'published_year',
                    'genre',
                    'stock'
                ]
            ]);
    }

    public function test_user_can_store_a_check_out(): void
    {
        Sanctum::actingAs(
            $this->user
        );

        $book = Book::inRandomOrder()->first();
        $book_default_stock = $book->stock;
        $check_out = CheckOut::factory()->make(['book_id' => $book->id, 'return_date' => null]);

        $response = $this->postJson(route('check_outs.store', ['book' => $book->id]), $check_out->toArray());
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'book_id',
                'user_id',
                'book' => [
                    'id',
                    'title',
                    'slug',
                    'author',
                    'published_year',
                    'genre',
                    'stock'
                ]
            ]);

        $book->refresh();
        $check_out->refresh();

        $this->assertLessThan($book_default_stock, $book->stock);
        $this->assertDatabaseHas('check_outs', [
            'book_id' => $check_out->book_id,
            'user_id' => $this->user->id,
            'status' => 'borrowed'
        ]);
        $this->assertNull($check_out->return_date);
    }

    public function test_librarian_can_see_list_of_check_outs(): void
    {
        Sanctum::actingAs(
            $this->librarian
        );

        $response = $this->getJson(route('check_outs.index'));
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'current_page',
                'data' => [
                    [
                        'book_id',
                        'user_id',
                        'book' => [
                            'id',
                            'title',
                            'slug',
                            'author',
                            'published_year',
                            'genre',
                            'stock'
                        ]
                    ]
                ]
            ]);
        $ids_count = collect($response['data'])->pluck('user_id')->unique()->count();
        $this->assertGreaterThan(1, $ids_count);
    }

    public function test_librarian_can_see_single_check_out(): void
    {
        Sanctum::actingAs(
            $this->librarian
        );

        $check_out = CheckOut::inRandomOrder()->first();

        $response = $this->getJson(route('check_outs.show', ['check_out' => $check_out->id]));
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'book_id',
                'user_id',
                'book' => [
                    'id',
                    'title',
                    'slug',
                    'author',
                    'published_year',
                    'genre',
                    'stock'
                ]
            ]);
    }

    public function test_librarian_can_return_a_check_out_book(): void
    {
        Sanctum::actingAs(
            $this->librarian
        );

        $book = Book::inRandomOrder()->first();
        $book_default_stock = $book->stock;
        $check_out = CheckOut::factory()->create(['book_id' => $book->id, 'status' => 'borrowed']);

        $response = $this->putJson(route('check_outs.update', ['check_out' => $check_out->id]));
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'book_id',
                'user_id',
                'book' => [
                    'id',
                    'title',
                    'slug',
                    'author',
                    'published_year',
                    'genre',
                    'stock'
                ]
            ]);

        $book->refresh();

        $this->assertGreaterThan($book_default_stock, $book->stock);
        $this->assertDatabaseHas('check_outs', [
            'book_id' => $check_out->book_id,
            'user_id' => $check_out->user_id,
            'status' => 'returned'
        ]);

        $check_out->refresh();
        $this->assertNotNull($check_out->return_date);
    }
}
