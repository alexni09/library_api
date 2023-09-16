<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;

class BookExtraSeeder extends Seeder {
    const HOW_MANY_TO_SEED = 20;   /*  how many books to seed per category  */

    public function run(): void {
        /*  
            Production only.
            Those books are seeded on CategoryExtraSeeder  
        */
    }
}