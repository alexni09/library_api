<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoryExtraSeeder extends Seeder {
    const HOW_MANY_TO_SEED = 1000;

    public function run(): void {
        Category::factory(self::HOW_MANY_TO_SEED)->hasBooks(BookExtraSeeder::HOW_MANY_TO_SEED)->create();
    }
}