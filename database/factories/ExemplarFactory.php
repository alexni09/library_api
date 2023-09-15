<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Exemplar;
use Database\Seeders\ExemplarSeeder;
use Database\Seeders\BookSeeder;
use Database\Seeders\CategorySeeder;
use App\Services\Misc;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exemplar>
 */
class ExemplarFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Exemplar::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'borrowable' => rand(1,5) < 5,
            'condition' => Misc::condition(),
            'book_id' => ExemplarSeeder::$bookList[rand(0, count(ExemplarSeeder::$bookList) - 1)],
            'fee' => 300 + 100 * rand(1,6),
            'fine_per_delay' => 1100 + 100 * rand(1,7),
            'fine_per_minute' => 40 + 5 * rand(1,20),
            'fine_per_loss' => 10000 + 20000 * rand(1,17),
            'fine_per_damage' => 3000 + 4000 * rand(2,5)
        ];
    }
}