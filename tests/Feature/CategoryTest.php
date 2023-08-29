<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\CategorySeeder;
use App\Models\Category;
use App\Models\User;

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

    public function testAdminCanCreateACategory(): void {
        $user = User::factory()->create([
            'is_admin' => true
        ]);
        $name = fake()->text(20);
        $response = $this->actingAs($user)->postJson('/api/categories/', [
            'name' => $name
        ]);
        $id = $response['data']['id'];
        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', [
            'id' => $id,
            'name' => $name
        ]);
    }

    public function testNonAdminCannotCreateACategory(): void {
        $user = User::factory()->create([
            'is_admin' => false
        ]);
        $name = fake()->text(20);
        $response = $this->actingAs($user)->postJson('/api/categories/', [
            'name' => $name
        ]);
        $response->assertStatus(422);
    }

    public function testUnauthenticatedUserCannotUpdateACategory(): void {
        $name1 = fake()->text(20);
        $category = Category::factory()->create([
            'name' => $name1
        ]);
        do {
            $name2 = fake()->text(20);
        } while ($name1 === $name2);
        $response = $this->putJson('/api/categories/' . strval($category->id), [
            'name' => $name2
        ]);
        $response->assertStatus(401);
    }

    public function testAdminCanUpdateACategory(): void {
        $user = User::factory()->create([
            'is_admin' => true
        ]);
        $name1 = fake()->text(20);
        $category = Category::factory()->create([
            'name' => $name1
        ]);
        do {
            $name2 = fake()->text(20);
        } while ($name1 === $name2);
        $response = $this->actingAs($user)->putJson('/api/categories/' . strval($category->id), [
            'name' => $name2
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => $name2
        ]);
    }

    public function testNonAdminCannotUpdateACategory(): void {
        $user = User::factory()->create([
            'is_admin' => false
        ]);
        $name1 = fake()->text(20);
        $category = Category::factory()->create([
            'name' => $name1
        ]);
        do {
            $name2 = fake()->text(20);
        } while ($name1 === $name2);
        $response = $this->actingAs($user)->putJson('/api/categories/' . strval($category->id), [
            'name' => $name2
        ]);
        $response->assertStatus(422);
    }}