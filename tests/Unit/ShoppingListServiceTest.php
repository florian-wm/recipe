<?php
namespace Tests\Unit;

use App\Models\Menu;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Meal;
use App\Models\MealRecipe;
use App\Services\ShoppingListService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppingListServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ShoppingListService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ShoppingListService();
    }

    public function test_generate_shopping_list_from_menu_old_structure()
    {
        $menu = Menu::factory()->create();
        $ingredient1 = Ingredient::factory()->create(['name' => 'Tomate', 'unit' => 'kg']);
        $ingredient2 = Ingredient::factory()->create(['name' => 'Pâtes', 'unit' => 'g']);
        $recipe1 = Recipe::factory()->create();
        $recipe2 = Recipe::factory()->create();
        $recipe1->ingredients()->attach($ingredient1->id, ['quantity' => 2]);
        $recipe2->ingredients()->attach($ingredient2->id, ['quantity' => 500]);
        $menu->recipes()->attach($recipe1->id);
        $menu->recipes()->attach($recipe2->id);

        $result = $this->service->generateShoppingList($menu);
        $this->assertCount(2, $result);
        $this->assertEquals('Pâtes', $result->first()['name']);
        $this->assertEquals('Tomate', $result->last()['name']);
    }

    public function test_generate_shopping_list_for_recipes_aggregates_quantities()
    {
        $ingredient = Ingredient::factory()->create(['name' => 'Carotte', 'unit' => 'kg']);
        $recipe1 = Recipe::factory()->create();
        $recipe2 = Recipe::factory()->create();
        $recipe1->ingredients()->attach($ingredient->id, ['quantity' => 1]);
        $recipe2->ingredients()->attach($ingredient->id, ['quantity' => 2]);
        $recipes = collect([$recipe1, $recipe2]);

        $result = $this->service->generateShoppingListForRecipes($recipes);
        $this->assertCount(1, $result);
        $this->assertEquals(3, $result->first()['quantity']);
    }

    public function test_generate_from_menu_new_structure_with_meals_and_portions()
    {
        $menu = Menu::factory()->create();
        $meal = Meal::factory()->create(['menu_id' => $menu->id, 'day' => 'Lundi', 'label' => 'Déjeuner']);
        $ingredient = Ingredient::factory()->create(['name' => 'Riz', 'unit' => 'g']);
        $recipe = Recipe::factory()->create(['portion' => 2]);
        $recipe->ingredients()->attach($ingredient->id, ['quantity' => 100]);
        $mealRecipe = MealRecipe::factory()->create([
            'meal_id' => $meal->id,
            'recipe_id' => $recipe->id,
            'portion' => 4 // 2x la portion de base
        ]);
        $menu->refresh();

        $result = $this->service->generateFromMenu($menu);
        $this->assertCount(1, $result);
        $this->assertEquals('Riz', $result[0]['name']);
        $this->assertEquals(200, $result[0]['total_quantity']); // 100*2
    }

    public function test_generate_from_recipes_with_portions_and_duplicates()
    {
        $ingredient = Ingredient::factory()->create(['name' => 'Oeuf', 'unit' => 'pièce']);
        $recipe1 = Recipe::factory()->create(['portion' => 2]);
        $recipe2 = Recipe::factory()->create(['portion' => 4]);
        $recipe1->ingredients()->attach($ingredient->id, ['quantity' => 2]);
        $recipe2->ingredients()->attach($ingredient->id, ['quantity' => 4]);
        $recipes = collect([$recipe1, $recipe2]);
        $portions = [
            $recipe1->id => 4, // double la portion
            $recipe2->id => 2  // moitié de la portion
        ];
        $result = $this->service->generateFromRecipes($recipes, $portions);
        $this->assertCount(1, $result);
        // recipe1: 2*2=4, recipe2: 4*0.5=2, total=6
        $this->assertEquals(6, $result[0]['total_quantity']);
    }
} 