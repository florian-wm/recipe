<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CustomItem;

class CustomItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => 'Lessive', 'quantity' => '1', 'unit' => 'bouteille', 'usage_count' => 5],
            ['name' => 'Papier toilette', 'quantity' => '2', 'unit' => 'rouleaux', 'usage_count' => 8],
            ['name' => 'Lait', 'quantity' => '2', 'unit' => 'litres', 'usage_count' => 12],
            ['name' => 'Pain', 'quantity' => '1', 'unit' => 'baguette', 'usage_count' => 15],
            ['name' => 'Beurre', 'quantity' => '1', 'unit' => 'plaquette', 'usage_count' => 6],
            ['name' => 'Yaourts', 'quantity' => '8', 'unit' => 'pots', 'usage_count' => 10],
            ['name' => 'Pommes', 'quantity' => '1', 'unit' => 'kg', 'usage_count' => 7],
            ['name' => 'Bananes', 'quantity' => '1', 'unit' => 'kg', 'usage_count' => 9],
            ['name' => 'Pâtes', 'quantity' => '2', 'unit' => 'paquets', 'usage_count' => 4],
            ['name' => 'Riz', 'quantity' => '1', 'unit' => 'kg', 'usage_count' => 3],
            ['name' => 'Huile d\'olive', 'quantity' => '1', 'unit' => 'bouteille', 'usage_count' => 2],
            ['name' => 'Sel', 'quantity' => '1', 'unit' => 'paquet', 'usage_count' => 1],
            ['name' => 'Poivre', 'quantity' => '1', 'unit' => 'moulin', 'usage_count' => 1],
            ['name' => 'Savon', 'quantity' => '1', 'unit' => 'pain', 'usage_count' => 3],
            ['name' => 'Shampooing', 'quantity' => '1', 'unit' => 'flacon', 'usage_count' => 2],
        ];

        foreach ($items as $item) {
            CustomItem::create([
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'unit' => $item['unit'],
                'is_saved' => true,
                'usage_count' => $item['usage_count']
            ]);
        }
    }
}
