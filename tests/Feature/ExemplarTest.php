<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\ExemplarSeeder;
use Database\Seeders\BookSeeder;
use Database\Seeders\CategorySeeder;
use App\Models\Exemplar;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use App\Services\Misc;

class ExemplarTest extends TestCase {
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();
        (new CategorySeeder)->run();
        (new ExemplarSeeder)->run();
    }

    public function testPublicUserCanShowAnExemplar(): void {
        $first = Exemplar::first();
        $response = $this->getJson('/api/exemplars/' . strval($first->id));
        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(6, 'data')
            ->assertJson([
                'data' => [
                    'id' => $first->id,
                    'borrowable' => $first->borrowable,
                    'book_id' => $first->book_id, 
                    'book_name' => $first->book->name, 
                    'condition_value' => $first->condition->value, 
                    'condition_name' => $first->condition->name
                ]
            ]);
    }

    public function testPublicUserCannotListInexistantExemplars(): void {
        $id = ExemplarSeeder::$bookList[count(ExemplarSeeder::$bookList) - 1] + 10000;
        $response = $this->getJson('/api/exemplars/list/' . strval($id));
        $response->assertStatus(404);
    }

    public function testPublicUserListsABookWithoutExemplarsCorrectly(): void {
        $category = Category::first();
        $book = Book::factory()->create([ 'category_id' => $category->id ]);
        $response = $this->getJson('/api/exemplars/list/' . strval($book->id));
        $response->assertStatus(204);
    }

    public function testPublicUserListsABookWithExemplarsCorrectly(): void {
        $book = Book::first();
        $exemplar = Exemplar::factory()->create([ 'book_id' => $book->id ]);
        $response = $this->getJson('/api/exemplars/list/' . strval($book->id));
        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(6, 'data.0')
            ->assertJsonFragment([
                'id' => $exemplar->id,
                'borrowable' => intval($exemplar->borrowable),
                'book_id' => $exemplar->book_id, 
                'book_name' => $exemplar->book->name, 
                'condition_value' => $exemplar->condition->value, 
                'condition_name' => $exemplar->condition->name
            ]);
    }

    public function testUnauthenticatedUserCannotCreateAnExemplar(): void {
        $book = Book::first();
        $response = $this->postJson('/api/exemplars/', [
            'book_id' => $book->id,
            'condition' => Misc::condition()
        ]);
        $response->assertStatus(401);
    }

    public function testAdminCanCreateAnExemplar(): void {
        $book = Book::first();
        $user = User::factory()->create([
            'is_admin' => true
        ]);
        $condition = Misc::condition();
        $response = $this->actingAs($user)->postJson('/api/exemplars/', [
            'book_id' => $book->id,
            'condition' => $condition
        ]);
        $id = $response['data']['id'];
        $response->assertStatus(201);
        $this->assertDatabaseHas('exemplars', [
            'id' => $id,
            'book_id' => $book->id,
            'condition' => $condition,
            'borrowable' => true
        ]);
    }

    public function testNonAdminCannotCreateAnExemplar(): void {
        $book = Book::first();
        $user = User::factory()->create([
            'is_admin' => false
        ]);
        $condition = Misc::condition();
        $response = $this->actingAs($user)->postJson('/api/exemplars/', [
            'book_id' => $book->id,
            'condition' => $condition
        ]);
        $response->assertStatus(422);
    }

}