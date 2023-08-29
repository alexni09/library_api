<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\CategorySeeder;

class CategoryTest extends TestCase {
    use RefreshDatabase;

    public function test_example(): void {
        (new CategorySeeder)->run();
        $response = $this->getJson('/api/categories');
        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }
}