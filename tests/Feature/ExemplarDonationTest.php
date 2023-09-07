<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\CategorySeeder;
use App\Models\Book;
use App\Models\User;
use App\Services\Misc;

class ExemplarDonationTest extends TestCase {
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();
        (new CategorySeeder)->run();
    }

    public function testUnauthenticatedUserCannotDonateAnExemplar(): void {
        $book = Book::first();
        $response = $this->postJson('/api/exemplars/donate/', [
            'book_id' => $book->id,
            'condition' => Misc::condition()
        ]);
        $response->assertStatus(401);
    }

    public function testUserCanDonateAnExemplar(): void {
        $book = Book::first();
        $user = User::factory()->create([
            'is_admin' => false
        ]);
        $condition = Misc::condition();
        $response = $this->actingAs($user)->postJson('/api/exemplars/donate/', [
            'book_id' => $book->id,
            'condition' => $condition
        ]);
        $id = $response['data']['id'];
        $response->assertStatus(201);
        $this->assertDatabaseHas('exemplars', [
            'id' => $id,
            'book_id' => $book->id,
            'condition' => $condition,
            'borrowable' => 1,
            'user_id' => $user->id
        ]);
    }
}