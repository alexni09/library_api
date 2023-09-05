<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder {
    public function run(): void {
        User::factory()->create([
            'is_admin' => true,
            'email' => 'admin@nowhere.xyz',
            'name' => 'admin'
        ]);
    }
}