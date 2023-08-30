<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Book;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory {
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class;

    protected function rating(): int {
        $r = rand(1,15);
        switch($r) {
            case 1: return 1; break;
            case 2: case 3: return 2; break;
            case 4: case 5: case 6: return 3; break;
            case 7: case 8: case 9: case 10: return 4; break;
            default: return 5; break;
        }
    }
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'name' => $this->faker->text(30),
            'rating' => $this->rating()
        ];
    }
}