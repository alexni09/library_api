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

class BookTest extends TestCase {
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();
        (new CategorySeeder)->run();
    }

    public function testPublicUserCanListBooks(): void {
        $response = $this->getJson('/api/books');
        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(CategorySeeder::HOW_MANY_TO_SEED * BookSeeder::HOW_MANY_TO_SEED, 'data')
            ->assertJsonStructure(['data' => [
                ['*' => 'id', 'name', 'rating_value', 'rating_name', 'category_id', 'category_name']
            ]]);
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
}