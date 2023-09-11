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

class BorrowTest extends TestCase {
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();
        (new CategorySeeder)->run();
        (new ExemplarSeeder)->run();
    }

    public function testUnauthenticatedCannotBorrow() {
        $exemplar = Exemplar::where('borrowable',true)->first();
        $response = $this->postJson('/api/borrow/' . strval($exemplar->id));
        $response->assertStatus(401);
    }

    public function testUserCannotBorrowAnUnborrowableBook() {
        $exemplar = Exemplar::where('borrowable',false)->first();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/borrow/' . strval($exemplar->id));
        $response->assertStatus(403);
    }

    public function testUserCanBorrowThreeBooksButNotFour() {
        $exemplars = Exemplar::where('borrowable',true)->limit(4)->get();
        $user = User::factory()->create();
        for ($i=0; $i<3; $i++) {
            $response = $this->actingAs($user)->postJson('/api/borrow/' . strval($exemplars[$i]->id));
            $response->assertStatus(201);
            $this->assertDatabaseHas('exemplar_user', [
                'user_id' => $user->id,
                'exemplar_id' => $exemplars[$i]->id,
                'returned' => null
            ]);
        }
        $response = $this->actingAs($user)->postJson('/api/borrow/' . strval($exemplars[3]->id));
        $response->assertStatus(403);
        $this->assertDatabaseMissing('exemplar_user', [
            'user_id' => $user->id,
            'exemplar_id' => $exemplars[3]->id
        ]);
    }

    public function testUserCannotBorrowIfAlreadyBorrowed() {
        $exemplar = Exemplar::where('borrowable',true)->first();
        $user1 = User::factory()->create();
        $response = $this->actingAs($user1)->postJson('/api/borrow/' . strval($exemplar->id));
        $response->assertStatus(201);
        $response = $this->actingAs($user1)->postJson('/api/borrow/' . strval($exemplar->id));
        $response->assertStatus(403);
        $user2 = User::factory()->create();
        $response = $this->actingAs($user2)->postJson('/api/borrow/' . strval($exemplar->id));
        $response->assertStatus(403);
    }

    public function testUnauthenticatedCannotListBorrowedBooks() {
        $response = $this->getJson('/api/borrowed-list/');
        $response->assertStatus(401);
    }

    public function testUserCanListBorrowedBooks() {
        $exemplars = Exemplar::where('borrowable',true)->limit(6)->get();
        $user1 = User::factory()->create();
        $response = $this->actingAs($user1)->postJson('/api/borrow/' . strval($exemplars[0]->id));
        $response->assertStatus(201);
        $user2 = User::factory()->create();
        $response = $this->actingAs($user2)->postJson('/api/borrow/' . strval($exemplars[1]->id));
        $response->assertStatus(201);
        $response = $this->actingAs($user2)->postJson('/api/borrow/' . strval($exemplars[2]->id));
        $response->assertStatus(201);
        $user3 = User::factory()->create();
        $response = $this->actingAs($user3)->postJson('/api/borrow/' . strval($exemplars[3]->id));
        $response->assertStatus(201);
        $response = $this->actingAs($user3)->postJson('/api/borrow/' . strval($exemplars[4]->id));
        $response->assertStatus(201);
        $response = $this->actingAs($user3)->postJson('/api/borrow/' . strval($exemplars[5]->id));
        $response->assertStatus(201);
        $response = $this->actingAs($user3)->getJson('/api/borrowed-list/');
        $response->assertStatus(200)->assertJsonStructure(['data']);
        for ($i=0; $i<3; $i++) {
            $response->assertJsonCount(6, 'data.' . strval($i))
                ->assertJsonFragment([
                    'id' => $exemplars[3+$i]->id,
                    'borrowable' => intval($exemplars[3+$i]->borrowable),
                    'book_id' => $exemplars[3+$i]->book_id, 
                    'book_name' => $exemplars[3+$i]->book->name, 
                    'condition_value' => $exemplars[3+$i]->condition->value, 
                    'condition_name' => $exemplars[3+$i]->condition->name
                ]);
        }
    }

    public function testUnauthenticatedCannotReturnABook() {
        $exemplar = Exemplar::where('borrowable',true)->first();
        $response = $this->patchJson('/api/giveback/' . strval($exemplar->id));
        $response->assertStatus(401);
    }

    public function testUserCanReturnABookOnlyIfNotAlreadyReturned() {
        $exemplar = Exemplar::where('borrowable',true)->first();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/borrow/' . strval($exemplar->id));
        $response->assertStatus(201);
        $this->assertDatabaseHas('exemplar_user', [
            'user_id' => $user->id,
            'exemplar_id' => $exemplar->id,
            'returned' => null
        ]);
        $response = $this->actingAs($user)->patchJson('/api/giveback/' . strval($exemplar->id));
        $response->assertStatus(200);
        $this->assertDatabaseHas('exemplar_user', [
            'user_id' => $user->id,
            'exemplar_id' => $exemplar->id
        ]);
        $this->assertDatabaseMissing('exemplar_user', [
            'user_id' => $user->id,
            'exemplar_id' => $exemplar->id,
            'returned' => null
        ]);
        $this->assertDatabaseHas('payments', [
            'user_id' => $user->id,
            'exemplar_id' => $exemplar->id,
            'paid_at' => null
        ]);
        $response = $this->actingAs($user)->patchJson('/api/giveback/' . strval($exemplar->id));
        $response->assertStatus(404);
    }

    public function testAnotherUserCannotReturnAnothersBook() {
        $exemplar = Exemplar::where('borrowable',true)->first();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $response = $this->actingAs($user1)->postJson('/api/borrow/' . strval($exemplar->id));
        $response->assertStatus(201);
        $this->assertDatabaseHas('exemplar_user', [
            'user_id' => $user1->id,
            'exemplar_id' => $exemplar->id,
            'returned' => null
        ]);
        $response = $this->actingAs($user2)->patchJson('/api/giveback/' . strval($exemplar->id));
        $response->assertStatus(404);
        $this->assertDatabaseHas('exemplar_user', [
            'user_id' => $user1->id,
            'exemplar_id' => $exemplar->id,
            'returned' => null
        ]);
        $this->assertDatabaseMissing('exemplar_user', [
            'user_id' => $user2->id,
            'exemplar_id' => $exemplar->id,
            'returned' => null
        ]);
    }

    public function testUserCannotReturnABookInABetterShape() {
        $exemplar = Exemplar::where('borrowable',true)->where('condition','>',1)->first();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/borrow/' . strval($exemplar->id));
        $response->assertStatus(201);
        $this->assertDatabaseHas('exemplar_user', [
            'user_id' => $user->id,
            'exemplar_id' => $exemplar->id,
            'returned' => null
        ]);
        $condition = $exemplar->condition->value - 1;
        $response = $this->actingAs($user)->patchJson('/api/giveback/' . strval($exemplar->id), [
            'condition' => $condition
        ]);
        $response->assertStatus(403);
        $this->assertDatabaseHas('exemplar_user', [
            'user_id' => $user->id,
            'exemplar_id' => $exemplar->id,
            'returned' => null
        ]);
    }

    public function testUserCanReturnABookInAWorseShape() {
        $exemplar = Exemplar::where('borrowable',true)->where('condition','<',4)->first();
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/borrow/' . strval($exemplar->id));
        $response->assertStatus(201);
        $this->assertDatabaseHas('exemplar_user', [
            'user_id' => $user->id,
            'exemplar_id' => $exemplar->id,
            'returned' => null
        ]);
        $condition = $exemplar->condition->value + 1;
        $response = $this->actingAs($user)->patchJson('/api/giveback/' . strval($exemplar->id), [
            'condition' => $condition
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('exemplar_user', [
            'user_id' => $user->id,
            'exemplar_id' => $exemplar->id
        ]);
        $this->assertDatabaseMissing('exemplar_user', [
            'user_id' => $user->id,
            'exemplar_id' => $exemplar->id,
            'returned' => null
        ]);
        $this->assertDatabaseHas('exemplars', [
            'id' => $exemplar->id,
            'condition' => $condition
        ]);
        $this->assertDatabaseHas('payments', [
            'user_id' => $user->id,
            'exemplar_id' => $exemplar->id,
            'paid_at' => null
        ]);
    }
}