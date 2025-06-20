<?php
namespace Tests\Feature;
use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IngredientApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_ingredients()
    {
        Ingredient::factory()->count(2)->create();
        $response = $this->getJson('/api/ingredients');
        $response->assertStatus(200)->assertJsonCount(2);
    }

    public function test_can_create_ingredient()
    {
        $data = [
            'name' => 'Sel',
            'unit' => 'g',
        ];
        $response = $this->postJson('/api/ingredients', $data);
        $response->assertStatus(201)->assertJsonFragment(['name' => 'Sel']);
        $this->assertDatabaseHas('ingredients', ['name' => 'Sel']);
    }

    public function test_validation_error_on_create()
    {
        $response = $this->postJson('/api/ingredients', []);
        $response->assertStatus(422);
    }

    public function test_can_show_ingredient()
    {
        $ingredient = Ingredient::factory()->create();
        $response = $this->getJson('/api/ingredients/' . $ingredient->id);
        $response->assertStatus(200)->assertJsonFragment(['name' => $ingredient->name]);
    }

    public function test_can_update_ingredient()
    {
        $ingredient = Ingredient::factory()->create();
        $data = [
            'name' => 'Poivre',
            'unit' => 'g',
        ];
        $response = $this->putJson('/api/ingredients/' . $ingredient->id, $data);
        $response->assertStatus(200)->assertJsonFragment(['name' => 'Poivre']);
        $this->assertDatabaseHas('ingredients', ['name' => 'Poivre']);
    }

    public function test_can_delete_ingredient()
    {
        $ingredient = Ingredient::factory()->create();
        $response = $this->deleteJson('/api/ingredients/' . $ingredient->id);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('ingredients', ['id' => $ingredient->id]);
    }
} 