<?php
namespace Database\Factories;
use App\Models\CustomItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomItemFactory extends Factory
{
    protected $model = CustomItem::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'quantity' => $this->faker->numberBetween(1, 10),
            'unit' => $this->faker->randomElement(['g', 'kg', 'ml', 'l', 'pièce']),
            'is_saved' => true,
            'usage_count' => $this->faker->numberBetween(0, 100),
        ];
    }
} 