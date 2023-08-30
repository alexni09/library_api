<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder {
    const HOW_MANY_TO_SEED = 10;

    public function run(): void {
        /* Those books are seeded on CategorySeeder */
        //Book::factory(self::HOW_MANY_TO_SEED)->create();
    }
}