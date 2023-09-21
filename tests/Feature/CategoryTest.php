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

    protected $category_min = null;
    protected $category_max = null;
    protected $category_chunk = null;

    protected function setUp(): void {
        parent::setUp();
        (new CategorySeeder)->run();
        $this->category_min = Category::first()->id;
        $this->category_max = Category::latest('id')->first()->id;
        $this->category_chunk = intval(env('CATEGORY_CHUNK', 10));
    }

    public function testPublicUserCanListCategories(): void {
        $response = $this->getJson('/api/categories');
        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount($this->category_chunk, 'data')
            ->assertJsonStructure(['data' => [
                ['*' => 'id', 'name']
            ]]);
        for ($i = 0; $i < $this->category_chunk; $i++) {
            $response->assertJsonFragment([ 'id' => ($i + $this->category_min) ]);
        }
    }

    public function testPublicUserCannotListCategoryZero(): void {
        $response = $this->getJson('/api/categories?start=0');
        $response->assertStatus(422);
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
        $response->assertStatus(403);
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
        $response->assertStatus(403);
    }

    public function testUnauthenticatedUserCannotDeleteACategory(): void {
        $name = fake()->text(20);
        $category = Category::factory()->create([
            'name' => $name
        ]);
        $response = $this->deleteJson('/api/categories/' . strval($category->id));
        $response->assertStatus(401);
    }

    public function testAdminCanDeleteACategory(): void {
        $user = User::factory()->create([
            'is_admin' => true
        ]);
        $name1 = fake()->text(20);
        $category1 = Category::factory()->create([
            'name' => $name1
        ]);
        $name2 = fake()->text(20);
        $category2 = Category::factory()->create([
            'name' => $name2
        ]);
        $name3 = fake()->text(20);
        $category3 = Category::factory()->create([
            'name' => $name3
        ]);
        $response = $this->actingAs($user)->deleteJson('/api/categories/' . strval($category2->id));
        $response->assertStatus(204);
        $this->assertDatabaseHas('categories', [
            'id' => $category1->id,
            'name' => $name1
        ]);
        $this->assertDatabaseMissing('categories', [
            'id' => $category2->id
        ]);
        $this->assertDatabaseHas('categories', [
            'id' => $category3->id,
            'name' => $name3
        ]);
    }

    public function testNonAdminCannotDeleteACategory(): void {
        $user = User::factory()->create([
            'is_admin' => false
        ]);
        $name1 = fake()->text(20);
        $category1 = Category::factory()->create([
            'name' => $name1
        ]);
        $name2 = fake()->text(20);
        $category2 = Category::factory()->create([
            'name' => $name2
        ]);
        $name3 = fake()->text(20);
        $category3 = Category::factory()->create([
            'name' => $name3
        ]);
        $response = $this->actingAs($user)->deleteJson('/api/categories/' . strval($category2->id));
        $response->assertStatus(403);
        $this->assertDatabaseHas('categories', [
            'id' => $category1->id,
            'name' => $name1
        ]);
        $this->assertDatabaseHas('categories', [
            'id' => $category2->id,
            'name' => $name2
        ]);
        $this->assertDatabaseHas('categories', [
            'id' => $category3->id,
            'name' => $name3
        ]);
    }
}