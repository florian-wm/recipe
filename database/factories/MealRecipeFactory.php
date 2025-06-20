<?php
namespace Database\Factories;
use App\Models\MealRecipe;
use Illuminate\Database\Eloquent\Factories\Factory;

class MealRecipeFactory extends Factory
{
    protected $model = MealRecipe::class;

    public function definition()
    {
        return [
            'meal_id' => null, // à renseigner dans le test
            'recipe_id' => null, // à renseigner dans le test
            'portion' => $this->faker->numberBetween(1, 8),
        ];
    }
} 