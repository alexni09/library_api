<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\CategorySeeder;
use App\Models\Category;

class CategoryTest extends TestCase {
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();
        (new CategorySeeder)->run();
    }

    public function testPublicUserCanListCategories(): void {
        $response = $this->getJson('/api/categories');
        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(CategorySeeder::HOW_MANY_TO_SEED, 'data')
            ->assertJsonStructure(['data' => [
                ['*' => 'id', 'name']
            ]]);
    }

    public function testPublicUserCanShowACategory(): void {
        $first = Category::first();
        $response = $this->getJson('/api/categories/' . strval($first->id));
        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(2, 'data')
            ->assertJson([
                'data' => [
                    'id' => $first->id,
                    'name' => $first->name
                ]
            ]);
    }

    public function testUnauthenticatedUserCannotCreateACategory(): void {
        $response = $this->postJson('/api/categories/', [
            'name' => fake()->text(20)
        ]);
        $response->assertStatus(401);
    }
}