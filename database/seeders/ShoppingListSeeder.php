<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShoppingList;

class ShoppingListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'Lessive',
                'quantity' => '1',
                'unit' => 'bouteille',
                'notes' => 'Marque préférée : Ariel',
                'source' => 'Manuel',
                'is_checked' => false
            ],
            [
                'name' => 'Pain',
                'quantity' => '2',
                'unit' => 'baguettes',
                'notes' => 'Pain complet si possible',
                'source' => 'Manuel',
                'is_checked' => false
            ],
            [
                'name' => 'Lait',
                'quantity' => '2',
                'unit' => 'l',
                'notes' => 'Lait demi-écrémé',
                'source' => 'Manuel',
                'is_checked' => false
            ],
            [
                'name' => 'Beurre',
                'quantity' => '250',
                'unit' => 'g',
                'notes' => 'Beurre doux',
                'source' => 'Manuel',
                'is_checked' => false
            ],
            [
                'name' => 'Pommes',
                'quantity' => '6',
                'unit' => 'pièces',
                'notes' => 'Golden ou Gala',
                'source' => 'Manuel',
                'is_checked' => false
            ]
        ];

        foreach ($items as $item) {
            ShoppingList::create($item);
        }
    }
}
