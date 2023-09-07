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
            'book_id' => ExemplarSeeder::$bookList[rand(0, count(ExemplarSeeder::$bookList) - 1)]
        ];
    }
}