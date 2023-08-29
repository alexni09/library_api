<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class ProfileTest extends TestCase {
    use RefreshDatabase;
 
    public function testUserCanWhoAmIItself() {
        $user = User::factory()->create();
        $response = $this->postJson('/api/v1/auth/login', [
            'email'    => $user->email,
            'password' => 'password'
        ]);
        //$token = $response['access_token'];
        $response = $this->actingAs($user)->getJson('/api/v1/whoami');
        $response->assertStatus(200)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(3, 'data')
            ->assertJsonFragment(['id' => $user->id, 'name' => $user->name, 'email' => $user->email]);
    }
 
    public function testUserCanGetTheirProfile() {
        $user = User::factory()->create();
 
        $response = $this->actingAs($user)->getJson('/api/v1/profile');
 
        $response->assertStatus(200)
            ->assertJsonStructure(['name', 'email'])
            ->assertJsonCount(2)
            ->assertJsonFragment(['name' => $user->name]);
    }
 
    public function testUserCanUpdateNameAndEmail() {
        $user = User::factory()->create();
 
        $response = $this->actingAs($user)->putJson('/api/v1/profile', [
            'name'  => 'John Updated',
            'email' => 'john_updated@example.com',
        ]);
 
        $response->assertStatus(202)
            ->assertJsonStructure(['name', 'email'])
            ->assertJsonCount(2)
            ->assertJsonFragment(['name' => 'John Updated']);
 
        $this->assertDatabaseHas('users', [
            'name'  => 'John Updated',
            'email' => 'john_updated@example.com',
        ]);
    }
 
    public function testUserCanChangePassword() {
        $user = User::factory()->create();
 
        $response = $this->actingAs($user)->putJson('/api/v1/password', [
            'current_password'      => 'password',
            'password'              => 'testing123',
            'password_confirmation' => 'testing123',
        ]);
 
        $response->assertStatus(202);
    }
}