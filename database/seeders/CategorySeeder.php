<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Petit-déjeuner',
                'description' => 'Recettes pour commencer la journée en beauté',
                'color' => '#F59E0B'
            ],
            [
                'name' => 'Entrées',
                'description' => 'Plats d\'ouverture et apéritifs',
                'color' => '#10B981'
            ],
            [
                'name' => 'Plats principaux',
                'description' => 'Plats de résistance et repas complets',
                'color' => '#EF4444'
            ],
            [
                'name' => 'Desserts',
                'description' => 'Douceurs et pâtisseries',
                'color' => '#8B5CF6'
            ],
            [
                'name' => 'Végétarien',
                'description' => 'Recettes sans viande ni poisson',
                'color' => '#84CC16'
            ],
            [
                'name' => 'Sans gluten',
                'description' => 'Recettes adaptées aux intolérants au gluten',
                'color' => '#06B6D4'
            ],
            [
                'name' => 'Rapide',
                'description' => 'Recettes en moins de 30 minutes',
                'color' => '#F97316'
            ],
            [
                'name' => 'Traditionnel',
                'description' => 'Recettes de grand-mère et classiques',
                'color' => '#6B7280'
            ],
            [
                'name' => 'International',
                'description' => 'Cuisines du monde entier',
                'color' => '#EC4899'
            ],
            [
                'name' => 'Healthy',
                'description' => 'Recettes équilibrées et nutritives',
                'color' => '#059669'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
