<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Step;
use App\Models\Category;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Recette 1: Crêpes
        $crepes = Recipe::create([
            'title' => 'Crêpes classiques',
            'description' => 'Des crêpes légères et délicieuses pour le petit-déjeuner ou le dessert.',
            'image' => 'crepes.jpg',
            'portion' => 4,
            'age' => 3
        ]);

        // Ingrédients pour les crêpes
        $crepes->ingredients()->attach([
            Ingredient::where('name', 'Farine')->first()->id => ['quantity' => 250],
            Ingredient::where('name', 'Œufs')->first()->id => ['quantity' => 4],
            Ingredient::where('name', 'Lait')->first()->id => ['quantity' => 500],
            Ingredient::where('name', 'Beurre')->first()->id => ['quantity' => 50],
            Ingredient::where('name', 'Sel')->first()->id => ['quantity' => 1],
        ]);

        // Catégories pour les crêpes
        $crepes->categories()->attach([
            Category::where('name', 'Petit-déjeuner')->first()->id,
            Category::where('name', 'Desserts')->first()->id,
            Category::where('name', 'Traditionnel')->first()->id,
        ]);

        // Étapes pour les crêpes
        $crepesSteps = [
            'Mélanger la farine et le sel dans un grand bol.',
            'Faire un puits et y casser les œufs.',
            'Ajouter progressivement le lait en remuant.',
            'Faire fondre le beurre et l\'ajouter à la pâte.',
            'Laisser reposer 1 heure au réfrigérateur.',
            'Faire cuire les crêpes dans une poêle chaude.'
        ];

        foreach ($crepesSteps as $index => $step) {
            Step::create([
                'recipe_id' => $crepes->id,
                'description' => $step,
                'order' => $index + 1
            ]);
        }

        // Recette 2: Pâtes à la carbonara
        $carbonara = Recipe::create([
            'title' => 'Pâtes à la carbonara',
            'description' => 'Un plat italien traditionnel avec des œufs, du fromage et du poivre.',
            'image' => 'carbonara.jpg',
            'portion' => 2,
            'age' => 5
        ]);

        // Ingrédients pour la carbonara
        $carbonara->ingredients()->attach([
            Ingredient::where('name', 'Pâtes')->first()->id => ['quantity' => 200],
            Ingredient::where('name', 'Œufs')->first()->id => ['quantity' => 2],
            Ingredient::where('name', 'Fromage râpé')->first()->id => ['quantity' => 100],
            Ingredient::where('name', 'Poivre')->first()->id => ['quantity' => 2],
            Ingredient::where('name', 'Sel')->first()->id => ['quantity' => 1],
        ]);

        // Catégories pour la carbonara
        $carbonara->categories()->attach([
            Category::where('name', 'Plats principaux')->first()->id,
            Category::where('name', 'International')->first()->id,
            Category::where('name', 'Rapide')->first()->id,
        ]);

        // Étapes pour la carbonara
        $carbonaraSteps = [
            'Faire cuire les pâtes dans l\'eau salée.',
            'Battre les œufs avec le fromage râpé.',
            'Égoutter les pâtes en gardant un peu d\'eau de cuisson.',
            'Mélanger les pâtes avec la préparation aux œufs.',
            'Ajouter du poivre fraîchement moulu et servir.'
        ];

        foreach ($carbonaraSteps as $index => $step) {
            Step::create([
                'recipe_id' => $carbonara->id,
                'description' => $step,
                'order' => $index + 1
            ]);
        }
    }
}
