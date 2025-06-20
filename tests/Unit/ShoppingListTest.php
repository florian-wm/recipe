<?php

namespace Tests\Unit;

use App\Models\ShoppingList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShoppingListTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_custom_item_creates_new_item()
    {
        $item = ShoppingList::addCustomItem('Tomates', 2, 'kg', 'Bio');
        $this->assertDatabaseHas('shopping_lists', [
            'name' => 'Tomates',
            'quantity' => '2',
            'unit' => 'kg',
            'notes' => 'Bio',
            'is_checked' => false,
            'source' => 'Manuel',
        ]);
        $this->assertEquals('Tomates', $item->name);
    }

    public function test_add_custom_item_merges_quantity_if_exists()
    {
        ShoppingList::addCustomItem('Pommes', 1, 'kg');
        $item = ShoppingList::addCustomItem('Pommes', 2, 'kg');
        $this->assertDatabaseHas('shopping_lists', [
            'name' => 'Pommes',
            'quantity' => '3',
            'unit' => 'kg',
        ]);
        $this->assertEquals('3', $item->quantity);
    }

    public function test_get_unchecked_items_returns_only_unchecked()
    {
        ShoppingList::addCustomItem('Carottes', 1);
        $item = ShoppingList::addCustomItem('Salade', 1);
        $item->update(['is_checked' => true]);
        $unchecked = ShoppingList::getUncheckedItems()->get();
        $this->assertCount(1, $unchecked);
        $this->assertEquals('Carottes', $unchecked->first()->name);
    }

    public function test_get_checked_items_returns_only_checked()
    {
        $item = ShoppingList::addCustomItem('Oignons', 1);
        $item->update(['is_checked' => true]);
        ShoppingList::addCustomItem('Poivrons', 1);
        $checked = ShoppingList::getCheckedItems()->get();
        $this->assertCount(1, $checked);
        $this->assertEquals('Oignons', $checked->first()->name);
    }

    public function test_add_from_menu_creates_items()
    {
        $menu = (object)['name' => 'Menu Test'];
        $data = [
            ['name' => 'Pâtes', 'quantity' => 2, 'unit' => 'paquet'],
            ['name' => 'Sauce', 'quantity' => 1, 'unit' => 'bocal'],
        ];
        ShoppingList::addFromMenu($menu, $data);
        $this->assertDatabaseHas('shopping_lists', [
            'name' => 'Pâtes', 'quantity' => '2', 'unit' => 'paquet', 'source' => 'Menu: Menu Test',
        ]);
        $this->assertDatabaseHas('shopping_lists', [
            'name' => 'Sauce', 'quantity' => '1', 'unit' => 'bocal', 'source' => 'Menu: Menu Test',
        ]);
    }
} 