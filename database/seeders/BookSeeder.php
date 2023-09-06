<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder {
    const HOW_MANY_TO_SEED = 10;   /*  how many books to seed per category  */

    public static function exemplarQuantity():int {
        $r = rand(1,13);
        switch($r) {
            case 1: case 2: case 3: return 1; break;
            case 4: case 5: case 6: return 2; break;
            case 7: case 8: case 9: return 3; break;
            case 10: case 11: return 4; break;
            case 12: return 5; break;
            default: return 0; break;
        }
    }

    public function run(): void {
        /* Those books are seeded on CategorySeeder */
        //Book::factory(self::HOW_MANY_TO_SEED)->create();
    }
}