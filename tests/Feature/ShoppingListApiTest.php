<?php
namespace Tests\Feature;
use App\Models\ShoppingList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppingListApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_shopping_items()
    {
        ShoppingList::factory()->count(2)->create();
        $response = $this->getJson('/api/shopping-list');
        $response->assertStatus(200)->assertJsonStructure([
            'unchecked_items', 'checked_items'
        ]);
    }

    public function test_can_add_custom_item()
    {
        $data = [
            'name' => 'Pain',
            'quantity' => '1',
            'unit' => 'baguette',
        ];
        $response = $this->postJson('/api/custom-items', $data);
        $response->assertStatus(201)->assertJsonFragment(['name' => 'Pain']);
        $this->assertDatabaseHas('custom_items', ['name' => 'Pain']);
    }

    public function test_validation_error_on_add_custom_item()
    {
        $response = $this->postJson('/api/custom-items', []);
        $response->assertStatus(422);
    }

    public function test_can_update_status()
    {
        $item = ShoppingList::factory()->create(['is_checked' => false]);
        $response = $this->putJson('/api/shopping-list/' . $item->id . '/status', ['is_checked' => true]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('shopping_lists', ['id' => $item->id, 'is_checked' => true]);
    }

    public function test_can_delete_item()
    {
        $item = ShoppingList::factory()->create();
        $response = $this->deleteJson('/api/shopping-list/' . $item->id);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('shopping_lists', ['id' => $item->id]);
    }
} 