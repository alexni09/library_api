<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\CategorySeeder;
use App\Models\Category;
use App\Models\Book;
use App\Models\Exemplar;
use App\Models\User;
use App\Services\Misc;

class BookDonationTest extends TestCase {
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();
        (new CategorySeeder)->run();
    }

    public function testUnauthenticatedUserCannotDonateABook(): void {
        $category = Category::first();
        $name = fake()->text(20);
        $rating = Misc::rating();
        $condition = Misc::condition();
        $response = $this->postJson('/api/books/donate/', [
            'category_id' => $category->id,
            'name' => $name,
            'rating' => $rating,
            'condition' => $condition
        ]);
        $response->assertStatus(401);
    }

    public function testUserCanDonateABook(): void {
        $category = Category::first();
        $user = User::factory()->create([
            'is_admin' => false
        ]);
        $name = fake()->text(20);
        $rating = Misc::rating();
        $condition = Misc::condition();
        $response = $this->actingAs($user)->postJson('/api/books/donate/', [
            'category_id' => $category->id,
            'name' => $name,
            'rating' => $rating,
            'condition' => $condition
        ]);
        $exemplar_id = $response['data']['id'];
        $response->assertStatus(201);
        $book_id = Exemplar::where('id',$exemplar_id)->get()[0]->book_id;
        $book = Book::find($book_id);
        $this->assertDatabaseHas('books', [
            'id' => $book_id,
            'name' => $name,
            'rating' => $rating
        ]);
        $this->assertDatabaseHas('exemplars', [
            'id' => $exemplar_id,
            'book_id' => $book->id,
            'condition' => $condition,
            'borrowable' => 1,
            'user_id' => $user->id
        ]);
    }
}