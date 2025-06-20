<?php
namespace Database\Factories;
use App\Models\ShoppingList;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShoppingListFactory extends Factory
{
    protected $model = ShoppingList::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'quantity' => $this->faker->numberBetween(1, 10),
            'unit' => $this->faker->randomElement(['g', 'kg', 'ml', 'l', 'pièce']),
            'is_checked' => false,
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
} 