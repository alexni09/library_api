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
        $exemplar = Exemplar::where('borrowable',false)->first();
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
}