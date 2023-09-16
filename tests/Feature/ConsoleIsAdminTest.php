<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\UserSeeder;

class ConsoleIsAdminTest extends TestCase {
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();
        (new UserSeeder)->run();
    }

    public function testAttribAdminCorrectly() {
        $email = 'another@nowhere.xyz';
        $this->artisan('app:attrib-admin ' . $email)
            ->assertSuccessful()
            ->expectsOutput('Success!');
        $this->assertDatabaseHas('users', [
            'email' => $email,
            'is_admin' => true
        ]);
    }

    public function testAttribAdminWithInexistantEmailAddressReturnsAnError() {
        $email = 'another@nowhere.inexistant.xyz';
        $this->artisan('app:attrib-admin ' . $email)
            ->assertFailed()
            ->expectsOutput('The specified email was not found in the users table.');
        $this->assertDatabaseMissing('users', [
            'email' => $email
        ]);
    }

    public function testRemoveAdminCorrectly() {
        $email = 'admin@nowhere.xyz';
        $this->artisan('app:remove-admin ' . $email)
            ->assertSuccessful()
            ->expectsOutput('Success!');
        $this->assertDatabaseHas('users', [
            'email' => $email,
            'is_admin' => false
        ]);
    }

    public function testRemoveAdminWithInexistantEmailAddressReturnsAnError() {
        $email = 'admin@nowhere.inexistant.xyz';
        $this->artisan('app:remove-admin ' . $email)
            ->assertFailed()
            ->expectsOutput('The specified email was not found in the users table.');
        $this->assertDatabaseMissing('users', [
            'email' => $email
        ]);
    }
}