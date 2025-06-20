<?php
namespace Tests\Feature;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_recipes()
    {
        Recipe::factory()->count(2)->create();
        $response = $this->getJson('/api/recipes');
        $response->assertStatus(200)->assertJsonCount(2);
    }

    public function test_can_create_recipe()
    {
        $ingredient = Ingredient::factory()->create();
        $category = Category::factory()->create();
        $data = [
            'title' => 'Tarte',
            'description' => 'Délicieuse',
            'portion' => 4,
            'age' => 1,
            'ingredients' => [
                ['id' => $ingredient->id, 'quantity' => 2, 'unit' => 'g']
            ],
            'steps' => [
                ['description' => 'Mélanger', 'order' => 1]
            ],
            'category_ids' => [$category->id],
        ];
        $response = $this->postJson('/api/recipes', $data);
        $response->assertStatus(201)->assertJsonFragment(['title' => 'Tarte']);
        $this->assertDatabaseHas('recipes', ['title' => 'Tarte']);
    }

    public function test_validation_error_on_create()
    {
        $response = $this->postJson('/api/recipes', []);
        $response->assertStatus(422);
    }

    public function test_can_show_recipe()
    {
        $recipe = Recipe::factory()->create();
        $response = $this->getJson('/api/recipes/' . $recipe->id);
        $response->assertStatus(200)->assertJsonFragment(['title' => $recipe->title]);
    }

    public function test_can_update_recipe()
    {
        $recipe = Recipe::factory()->create();
        $ingredient = \App\Models\Ingredient::factory()->create();
        $data = [
            'title' => 'Nouvelle',
            'description' => 'desc',
            'portion' => 2,
            'age' => 1,
            'ingredients' => [
                ['id' => $ingredient->id, 'quantity' => 1, 'unit' => 'g']
            ],
            'steps' => [['description' => 'step', 'order' => 1]],
            'category_ids' => [],
        ];
        $response = $this->putJson('/api/recipes/' . $recipe->id, $data);
        $response->assertStatus(200)->assertJsonFragment(['title' => 'Nouvelle']);
        $this->assertDatabaseHas('recipes', ['title' => 'Nouvelle']);
    }

    public function test_can_delete_recipe()
    {
        $recipe = Recipe::factory()->create();
        $response = $this->deleteJson('/api/recipes/' . $recipe->id);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('recipes', ['id' => $recipe->id]);
    }
} 