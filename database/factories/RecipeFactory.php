<?php
namespace Database\Factories;
use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'image' => $this->faker->imageUrl(),
            'portion' => $this->faker->numberBetween(1, 8),
            'age' => $this->faker->numberBetween(0, 99),
            'is_favorite' => $this->faker->boolean(),
        ];
    }
} 