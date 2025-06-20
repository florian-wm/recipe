<?php
namespace Tests\Feature;
use App\Models\Menu;
use App\Models\Recipe;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_menus()
    {
        Menu::factory()->count(2)->create();
        $response = $this->getJson('/api/menus');
        $response->assertStatus(200)->assertJsonCount(2);
    }

    public function test_can_create_menu()
    {
        $recipe = Recipe::factory()->create();
        $data = [
            'name' => 'Menu test',
            'meals' => [
                [
                    'day' => 'Lundi',
                    'label' => 'Déjeuner',
                    'recipes' => [
                        ['id' => $recipe->id, 'portion' => 2]
                    ]
                ]
            ]
        ];
        $response = $this->postJson('/api/menus', $data);
        $response->assertStatus(201)->assertJsonFragment(['name' => 'Menu test']);
        $this->assertDatabaseHas('menus', ['name' => 'Menu test']);
    }

    public function test_validation_error_on_create()
    {
        $response = $this->postJson('/api/menus', []);
        $response->assertStatus(422);
    }

    public function test_can_show_menu()
    {
        $menu = Menu::factory()->create();
        $response = $this->getJson('/api/menus/' . $menu->id);
        $response->assertStatus(200)->assertJsonFragment(['name' => $menu->name]);
    }

    public function test_can_update_menu()
    {
        $menu = Menu::factory()->create();
        $recipe = Recipe::factory()->create();
        $data = [
            'name' => 'Menu modifié',
            'meals' => [
                [
                    'day' => 'Mardi',
                    'label' => 'Dîner',
                    'recipes' => [
                        ['id' => $recipe->id, 'portion' => 3]
                    ]
                ]
            ]
        ];
        $response = $this->putJson('/api/menus/' . $menu->id, $data);
        $response->assertStatus(200)->assertJsonFragment(['name' => 'Menu modifié']);
        $this->assertDatabaseHas('menus', ['name' => 'Menu modifié']);
    }

    public function test_can_delete_menu()
    {
        $menu = Menu::factory()->create();
        $response = $this->deleteJson('/api/menus/' . $menu->id);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('menus', ['id' => $menu->id]);
    }
} 