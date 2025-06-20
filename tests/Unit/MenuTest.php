<?php
namespace Tests\Unit;
use App\Models\Menu;
use App\Models\Meal;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_menu_has_meals_relation()
    {
        $menu = Menu::factory()->create();
        $meal = Meal::factory()->create(['menu_id' => $menu->id]);
        $this->assertTrue($menu->meals->contains($meal));
    }

    public function test_fillable_attributes()
    {
        $data = [
            'name' => 'Menu de test',
        ];
        $menu = Menu::create($data);
        foreach ($data as $key => $value) {
            $this->assertEquals($value, $menu->$key);
        }
    }
} 