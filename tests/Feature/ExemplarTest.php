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
use Illuminate\Support\Facades\DB;

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
        /* Let's test for 5 exemplars of a single book: */
        $book_id = DB::table('quantity_of_exemplars_per_book')->select('book_id')->where('quantity',5)->first()->book_id;
        $exemplar_ids = DB::table('exemplars')->select('id')->where('book_id',$book_id)->get()->pluck('id')->toArray();
        $response = $this->getJson('/api/exemplars/list/' . strval($book_id) . '?borrowable=0');
        for ($i = 0; $i < count($exemplar_ids); $i++) {
            $exemplar = Exemplar::find($exemplar_ids[$i]);
            $response->assertStatus(200)
                ->assertJsonStructure(['data'])
                ->assertJsonCount(6, 'data.' . strval($i))
                ->assertJsonFragment([
                    'id' => $exemplar->id,
                    'borrowable' => intval($exemplar->borrowable),
                    'book_id' => $exemplar->book_id, 
                    'book_name' => $exemplar->book->name, 
                    'condition_value' => $exemplar->condition->value, 
                    'condition_name' => $exemplar->condition->name
                ]);
        }
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

    public function testUnauthenticatedUserCannotUpdateAnExemplar(): void {
        $exemplar = Exemplar::first();
        $response = $this->putJson('/api/exemplars/' . strval($exemplar->id), [
            'condition' => Misc::condition()
        ]);
        $response->assertStatus(401);
    }

    public function testAdminCanUpdateAnExemplarOneKey(): void {
        $exemplar = Exemplar::where('condition',4)->first();
        $user = User::factory()->create([
            'is_admin' => true
        ]);
        $response = $this->actingAs($user)->putJson('/api/exemplars/' . strval($exemplar->id), [
            'condition' => 2
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('exemplars', [
            'id' => $exemplar->id,
            'condition' => 2
        ]);
    }

    public function testAdminCanUpdateAnExemplarCirculatingUserIds(): void {
        $exemplar = Exemplar::whereNull('user_id')->first();
        $admin = User::factory()->create([
            'is_admin' => true
        ]);
        $user1 = User::factory()->create([
            'is_admin' => false
        ]);
        $user2 = User::factory()->create([
            'is_admin' => false
        ]);
        $response = $this->actingAs($admin)->putJson('/api/exemplars/' . strval($exemplar->id), [
            'change_donor' => 1,
            'user_id' => $user1->id
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('exemplars', [
            'id' => $exemplar->id,
            'user_id' => $user1->id
        ]);
        $response = $this->actingAs($admin)->putJson('/api/exemplars/' . strval($exemplar->id), [
            'change_donor' => 1,
            'user_id' => $user2->id
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('exemplars', [
            'id' => $exemplar->id,
            'user_id' => $user2->id
        ]);
        $response = $this->actingAs($admin)->putJson('/api/exemplars/' . strval($exemplar->id), [
            'change_donor' => 0
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('exemplars', [
            'id' => $exemplar->id,
            'user_id' => null
        ]);
    }

    public function testNonAdminCannotUpdateAnExemplar(): void {
        $exemplar = Exemplar::where('condition',3)->first();
        $user = User::factory()->create([
            'is_admin' => false
        ]);
        $response = $this->actingAs($user)->putJson('/api/exemplars/' . strval($exemplar->id), [
            'condition' => 1
        ]);
        $response->assertStatus(422);
    }

}