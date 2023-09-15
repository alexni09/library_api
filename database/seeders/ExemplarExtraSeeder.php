<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Exemplar;
use Illuminate\Support\Facades\DB;
use App\Services\Misc;

class ExemplarExtraSeeder extends Seeder {
    const HOW_MANY_TO_SEED = 100000;        /*  an absolute minimum value  */
    const HOW_MANY_RANDOM_TO_SEED = 3999;   /*  an absolute maximum value  */

    public static $maxBookId = null;

    public static function prepare(): void {
        self::$maxBookId = DB::table('books')->selectRaw('max(distinct id) as biggest')->get()[0]->biggest;
    }

    public function run(): void {
        self::prepare();
        $howMany = self::HOW_MANY_TO_SEED + rand(0, self::HOW_MANY_RANDOM_TO_SEED);
        for ($i = 0; $i < $howMany; $i++) {
            Exemplar::create([
                'rental_maximum_minutes' => rand(3,20),
                'payment_maximum_minutes' => rand(5,30),
                'borrowable' => rand(1,5) < 5,
                'condition' => Misc::condition(),
                'book_id' => rand(1,self::$maxBookId),
                'fee' => 300 + 100 * rand(1,6),
                'fine_per_delay' => 1100 + 100 * rand(1,7),
                'fine_per_minute' => 40 + 5 * rand(1,20),
                'fine_per_loss' => 10000 + 20000 * rand(1,17),
                'fine_per_damage' => 3000 + 4000 * rand(2,5)
            ]);
        }
    }
}