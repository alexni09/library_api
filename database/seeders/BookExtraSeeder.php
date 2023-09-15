<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;

class BookExtraSeeder extends Seeder {
    const HOW_MANY_TO_SEED = 20;   /*  how many books to seed per category  */

    public function run(): void {
        /* Those books are seeded on CategorySeeder */
        //Book::factory(self::HOW_MANY_TO_SEED)->create();
    }
}