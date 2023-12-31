<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\BookSeeder;
use Database\Seeders\CategorySeeder;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use App\Services\Misc;

class BookTest extends TestCase {
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();
        (new CategorySeeder)->run();
    }

    public function testUnauthenticatedUserCannotListAllBooks(): void {
        $response = $this->getJson('/api/books');
        $response->assertStatus(401);
    }

    public function testAdminCanListAllBooks(): void {
        $user = User::factory()->create([
            'is_admin' => true
        ]);
        $response = $this->actingAs($user)->getJson('/api/books');
        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(CategorySeeder::HOW_MANY_TO_SEED * BookSeeder::HOW_MANY_TO_SEED, 'data')
            ->assertJsonStructure(['data' => [
                ['*' => 'id', 'name', 'rating_value', 'rating_name', 'category_id', 'category_name']
            ]]);
    }

    public function testNonAdminCannotListAllBooks(): void {
        $user = User::factory()->create([
            'is_admin' => false
        ]);
        $response = $this->actingAs($user)->getJson('/api/books');
        $response->assertStatus(403);
    }

    public function testPublicUserCanShowABook(): void {
        $first = Book::first();
        $response = $this->getJson('/api/books/' . strval($first->id));
        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(6, 'data')
            ->assertJson([
                'data' => [
                    'id' => $first->id,
                    'name' => $first->name,
                    'rating_value' => $first->rating->value, 
                    'rating_name' => $first->rating->name, 
                    'category_id' => $first->category_id, 
                    'category_name' => $first->category->name
                ]
            ]);
    }

    public function testUnauthenticatedUserCannotCreateABook(): void {
        $category = Category::first();
        $response = $this->postJson('/api/books/', [
            'name' => fake()->text(20),
            'rating' => Misc::rating(),
            'category_id' => $category->id
        ]);
        $response->assertStatus(401);
    }

    public function testAdminCanCreateABook(): void {
        $category = Category::first();
        $user = User::factory()->create([
            'is_admin' => true
        ]);
        $name = fake()->text(20);
        $rating = Misc::rating();
        $response = $this->actingAs($user)->postJson('/api/books/', [
            'name' => $name,
            'rating' => $rating,
            'category_id' => $category->id
        ]);
        $id = $response['data']['id'];
        $response->assertStatus(201);
        $this->assertDatabaseHas('books', [
            'id' => $id,
            'name' => $name,
            'rating' => $rating,
            'category_id' => $category->id
        ]);
    }

    public function testNonAdminCannotCreateABook(): void {
        $category = Category::first();
        $user = User::factory()->create([
            'is_admin' => false
        ]);
        $name = fake()->text(20);
        $rating = Misc::rating();
        $response = $this->actingAs($user)->postJson('/api/books/', [
            'name' => $name,
            'rating' => $rating,
            'category_id' => $category->id
        ]);
        $response->assertStatus(403);
    }

    public function testUnauthenticatedUserCannotUpdateABook(): void {
        $category = Category::first();
        $book = Book::where('category_id', $category->id)->first();
        $name = fake()->text(20);
        $response = $this->putJson('/api/books/' . strval($book->id), [
            'name' => $name
        ]);
        $response->assertStatus(401);
    }

    public function testAdminCanUpdateABookThreeKeys(): void {
        $category = Category::first();
        $book = Book::where('category_id', $category->id)->first();
        $user = User::factory()->create([
            'is_admin' => true
        ]);
        $name = fake()->text(20);
        $rating = Misc::rating();
        $response = $this->actingAs($user)->putJson('/api/books/' . strval($book->id), [
            'name' => $name,
            'rating' => $rating,
            'category_id' => $category->id
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'name' => $name,
            'rating' => $rating,
            'category_id' => $category->id
        ]);
    }

    public function testAdminCanUpdateABookOneKey(): void {
        $category = Category::first();
        $book = Book::where('category_id', $category->id)->first();
        $user = User::factory()->create([
            'is_admin' => true
        ]);
        $rating = Misc::rating();
        $response = $this->actingAs($user)->putJson('/api/books/' . strval($book->id), [
            'rating' => $rating
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'rating' => $rating
        ]);
    }

    public function testAdminCannotUpdateABookZeroKeys(): void {
        $category = Category::first();
        $book = Book::where('category_id', $category->id)->first();
        $user = User::factory()->create([
            'is_admin' => true
        ]);
        $response = $this->actingAs($user)->putJson('/api/books/' . strval($book->id));
        $response->assertStatus(422);
    }

    public function testNonAdminCannotUpdateABookOneKey(): void {
        $category = Category::first();
        $book = Book::where('category_id', $category->id)->first();
        $user = User::factory()->create([
            'is_admin' => false
        ]);
        $rating = Misc::rating();
        $response = $this->actingAs($user)->putJson('/api/books/' . strval($book->id), [
            'rating' => $rating
        ]);
        $response->assertStatus(403);
    }

    public function testUnauthenticatedUserCannotDeleteABook(): void {
        $category = Category::first();
        $book = Book::where('category_id', $category->id)->first();
        $response = $this->deleteJson('/api/books/' . strval($book->id));
        $response->assertStatus(401);
        $this->assertDatabaseHas('books', [
            'id' => $book->id
        ]);
    }

    public function testAdminCanDeleteABookOnceButNotTwice(): void {
        $category = Category::first();
        $book = Book::where('category_id', $category->id)->first();
        $user = User::factory()->create([
            'is_admin' => true
        ]);
        $response = $this->actingAs($user)->deleteJson('/api/books/' . strval($book->id));
        $response->assertStatus(204);
        $this->assertDatabaseMissing('books', [
            'id' => $book->id
        ]);
        $response2 = $this->actingAs($user)->deleteJson('/api/books/' . strval($book->id));
        $response2->assertStatus(404);
    }

    public function testNonAdminCannotDeleteABook(): void {
        $category = Category::first();
        $book = Book::where('category_id', $category->id)->first();
        $user = User::factory()->create([
            'is_admin' => false
        ]);
        $response = $this->actingAs($user)->deleteJson('/api/books/' . strval($book->id));
        $response->assertStatus(403);
        $this->assertDatabaseHas('books', [
            'id' => $book->id
        ]);
    }

    public function testPublicUserCanListAllBooksFromACategory(): void {
        $category = Category::withCount('books')->latest()->first();
        $books = Book::where('category_id', $category->id)->get();
        $response = $this->getJson('/api/books-by-category/' . strval($category->id));
        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount($category->books_count, 'data');
        foreach ($books as $book) {
            $response->assertJsonFragment([
                'id' => $book->id,
                'name' => $book->name,
                'rating_value' => $book->rating->value,
                'rating_name' => $book->rating->name,
                'category_id' => $category->id,
                'category_name' => $category->name
            ]);
        }
    }

    public function testPublicUserListsEmptyCategoryAndGetsNoContent(): void {
        $category = Category::factory()->create();
        $response = $this->getJson('/api/books-by-category/' . strval($category->id));
        $response->assertStatus(204);
    }

    public function testPublicUserListsInexistantCategoryAndGetsNotFound(): void {
        $category = Category::factory()->create();
        $response = $this->getJson('/api/books-by-category/12345678');
        $response->assertStatus(404);
    }
}