<?php
namespace Tests\Unit;
use App\Models\Ingredient;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IngredientTest extends TestCase
{
    use RefreshDatabase;

    public function test_ingredient_has_recipes_relation()
    {
        $ingredient = Ingredient::factory()->create();
        $recipe = Recipe::factory()->create();
        $ingredient->recipes()->attach($recipe->id, ['quantity' => 1]);
        $this->assertTrue($ingredient->recipes->contains($recipe));
    }

    public function test_fillable_attributes()
    {
        $data = [
            'name' => 'Sel',
            'unit' => 'g'
        ];
        $ingredient = Ingredient::create($data);
        foreach ($data as $key => $value) {
            $this->assertEquals($value, $ingredient->$key);
        }
    }
} 