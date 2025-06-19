<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = [
            ['name' => 'Farine', 'unit' => 'g'],
            ['name' => 'Sucre', 'unit' => 'g'],
            ['name' => 'Œufs', 'unit' => 'pièce'],
            ['name' => 'Lait', 'unit' => 'ml'],
            ['name' => 'Beurre', 'unit' => 'g'],
            ['name' => 'Sel', 'unit' => 'pincée'],
            ['name' => 'Poivre', 'unit' => 'pincée'],
            ['name' => 'Huile d\'olive', 'unit' => 'ml'],
            ['name' => 'Oignons', 'unit' => 'pièce'],
            ['name' => 'Ail', 'unit' => 'gousse'],
            ['name' => 'Tomates', 'unit' => 'pièce'],
            ['name' => 'Poulet', 'unit' => 'g'],
            ['name' => 'Pâtes', 'unit' => 'g'],
            ['name' => 'Fromage râpé', 'unit' => 'g'],
            ['name' => 'Crème fraîche', 'unit' => 'ml'],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}
