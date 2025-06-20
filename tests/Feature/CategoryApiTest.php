<?php
namespace Tests\Feature;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_categories()
    {
        Category::factory()->count(2)->create();
        $response = $this->getJson('/api/categories');
        $response->assertStatus(200)->assertJsonCount(2);
    }

    public function test_can_create_category()
    {
        $data = [
            'name' => 'Entrée',
            'description' => 'desc',
            'color' => '#FF0000',
        ];
        $response = $this->postJson('/api/categories', $data);
        $response->assertStatus(201)->assertJsonFragment(['name' => 'Entrée']);
        $this->assertDatabaseHas('categories', ['name' => 'Entrée']);
    }

    public function test_validation_error_on_create()
    {
        $response = $this->postJson('/api/categories', []);
        $response->assertStatus(422);
    }

    public function test_can_show_category()
    {
        $category = Category::factory()->create();
        $response = $this->getJson('/api/categories/' . $category->id);
        $response->assertStatus(200)->assertJsonFragment(['name' => $category->name]);
    }

    public function test_can_update_category()
    {
        $category = Category::factory()->create();
        $data = [
            'name' => 'Plat',
            'description' => 'modif',
            'color' => '#00FF00',
        ];
        $response = $this->putJson('/api/categories/' . $category->id, $data);
        $response->assertStatus(200)->assertJsonFragment(['name' => 'Plat']);
        $this->assertDatabaseHas('categories', ['name' => 'Plat']);
    }

    public function test_can_delete_category()
    {
        $category = Category::factory()->create();
        $response = $this->deleteJson('/api/categories/' . $category->id);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
} 