<?php
namespace Tests\Unit;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeTest extends TestCase
{
    use RefreshDatabase;

    public function test_recipe_has_ingredients_relation()
    {
        $recipe = Recipe::factory()->create();
        $ingredient = Ingredient::factory()->create();
        $recipe->ingredients()->attach($ingredient->id, ['quantity' => 2]);
        $this->assertTrue($recipe->ingredients->contains($ingredient));
    }

    public function test_recipe_has_categories_relation()
    {
        $recipe = Recipe::factory()->create();
        $category = Category::factory()->create();
        $recipe->categories()->attach($category->id);
        $this->assertTrue($recipe->categories->contains($category));
    }

    public function test_fillable_attributes()
    {
        $data = [
            'title' => 'Test',
            'description' => 'Desc',
            'image' => 'img.jpg',
            'portion' => 2,
            'age' => 1,
            'is_favorite' => true
        ];
        $recipe = Recipe::create($data);
        foreach ($data as $key => $value) {
            $this->assertEquals($value, $recipe->$key);
        }
    }
} 