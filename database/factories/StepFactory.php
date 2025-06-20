<?php
namespace Database\Factories;
use App\Models\Step;
use Illuminate\Database\Eloquent\Factories\Factory;

class StepFactory extends Factory
{
    protected $model = Step::class;

    public function definition()
    {
        return [
            'recipe_id' => null, // à renseigner dans le test
            'description' => $this->faker->sentence(),
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }
} 