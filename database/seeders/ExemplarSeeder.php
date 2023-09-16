<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Exemplar;
use App\Models\Book;

class ExemplarSeeder extends Seeder {
    const HOW_MANY_TO_SEED = 3;   /*  average quantity of exemplars, per book  */

    public static $bookList = [];

    public static function prepare(): void {
        self::$bookList = Book::select('id')->get()->pluck('id')->toArray();
    }

    public function run(): void {
        self::prepare();
        Exemplar::factory(CategorySeeder::HOW_MANY_TO_SEED * BookSeeder::HOW_MANY_TO_SEED * self::HOW_MANY_TO_SEED)->create();
    }
}