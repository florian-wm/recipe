<?php
namespace Tests\Feature;
use App\Models\CustomItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomItemApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_custom_items()
    {
        \App\Models\CustomItem::factory()->count(2)->create(['is_saved' => true]);
        $response = $this->getJson('/api/custom-items');
        $response->assertStatus(200)->assertJsonCount(2);
    }

    public function test_can_create_custom_item()
    {
        $data = [
            'name' => 'Bière',
            'quantity' => '6',
            'unit' => 'bouteilles',
        ];
        $response = $this->postJson('/api/custom-items', $data);
        $response->assertStatus(201)->assertJsonFragment(['name' => 'Bière']);
        $this->assertDatabaseHas('custom_items', ['name' => 'Bière']);
    }

    public function test_validation_error_on_create()
    {
        $response = $this->postJson('/api/custom-items', []);
        $response->assertStatus(422);
    }

    public function test_can_update_custom_item()
    {
        $item = \App\Models\CustomItem::factory()->create(['is_saved' => true]);
        $data = [
            'name' => 'Jus',
            'quantity' => '2',
            'unit' => 'bouteilles',
        ];
        $response = $this->putJson('/api/custom-items/' . $item->id, $data);
        $response->assertStatus(200)->assertJsonFragment(['name' => 'Jus']);
        $this->assertDatabaseHas('custom_items', ['name' => 'Jus']);
    }

    public function test_can_delete_custom_item()
    {
        $item = \App\Models\CustomItem::factory()->create(['is_saved' => true]);
        $response = $this->deleteJson('/api/custom-items/' . $item->id);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('custom_items', ['id' => $item->id]);
    }
} 