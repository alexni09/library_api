<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Exemplar;

class ExemplarSeeder extends Seeder {
    const HOW_MANY_TO_SEED = 3;   /*  average quantity of exemplars, per book  */

    /**
     * Run the database seeds.
     */
    public function run(): void {
        Exemplar::factory(CategorySeeder::HOW_MANY_TO_SEED * BookSeeder::HOW_MANY_TO_SEED * self::HOW_MANY_TO_SEED)->create();
    }
}