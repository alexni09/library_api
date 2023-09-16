<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\ExemplarSeeder;
use Database\Seeders\CategorySeeder;
use App\Models\Exemplar;
use App\Models\User;

class ExemplarLossTest extends TestCase {
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();
        (new CategorySeeder)->run();
        (new ExemplarSeeder)->run();
    }

    public function testUnauthenticatedCannotLoseAnExemplar() {
        $exemplar = Exemplar::where('borrowable',true)->first();
        $response = $this->deleteJson('/api/exemplar-loss/' . strval($exemplar->id));
        $response->assertStatus(401);
    }

    public function testUserCannotLoseAnInexistantExemplar() {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->deleteJson('/api/exemplar-loss/12345678/');
        $response->assertStatus(404);
    }

    public function testAnotherUserCannotLoseAnothersExemplar() {
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
        $response = $this->actingAs($user2)->deleteJson('/api/exemplar-loss/' . strval($exemplar->id));
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

    public function testUserCanLoseAnExemplar() {
        $exemplar = Exemplar::where('borrowable',true)->first();
        $exemplar_id = $exemplar->id;
        $fine_per_loss = $exemplar->fine_per_loss;
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/borrow/' . strval($exemplar->id));
        $response->assertStatus(201);
        $this->assertDatabaseHas('exemplar_user', [
            'user_id' => $user->id,
            'exemplar_id' => $exemplar->id,
            'returned' => null
        ]);
        $response = $this->actingAs($user)->deleteJson('/api/exemplar-loss/' . strval($exemplar->id));
        $response->assertStatus(200);
        $this->assertDatabaseHas('exemplar_user', [
            'user_id' => $user->id,
            'exemplar_id' => null
        ]);
        $this->assertDatabaseMissing('exemplar_user', [
            'user_id' => $user->id,
            'exemplar_id' => null,
            'returned' => null
        ]);
        $this->assertDatabaseMissing('exemplars', [
            'id' => $exemplar_id
        ]);
        $this->assertDatabaseHas('payments', [
            'user_id' => $user->id,
            'exemplar_id' => null,
            'due_value' => $fine_per_loss,
            'paid_at' => null
        ]);
    }
}