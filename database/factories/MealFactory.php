<?php
namespace Database\Factories;
use App\Models\Meal;
use Illuminate\Database\Eloquent\Factories\Factory;

class MealFactory extends Factory
{
    protected $model = Meal::class;

    public function definition()
    {
        return [
            'menu_id' => null, // à renseigner dans le test
            'day' => $this->faker->dayOfWeek(),
            'label' => $this->faker->word(),
            'order' => $this->faker->numberBetween(1, 7),
        ];
    }
} 