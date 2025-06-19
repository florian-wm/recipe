<?php

namespace App\Services;

use App\Models\Menu;
use Illuminate\Support\Collection;

class ShoppingListService
{
    public function generateShoppingList(Menu $menu): Collection
    {
        $shoppingList = collect();

        foreach ($menu->recipes as $recipe) {
            foreach ($recipe->ingredients as $ingredient) {
                $quantity = $ingredient->pivot->quantity;
                
                // Chercher si l'ingrédient existe déjà dans la liste
                $existingItem = $shoppingList->first(function ($item) use ($ingredient) {
                    return $item['ingredient_id'] === $ingredient->id;
                });

                if ($existingItem) {
                    // Ajouter la quantité à l'ingrédient existant
                    $existingItem['quantity'] += $quantity;
                } else {
                    // Ajouter un nouvel ingrédient
                    $shoppingList->push([
                        'ingredient_id' => $ingredient->id,
                        'name' => $ingredient->name,
                        'unit' => $ingredient->unit,
                        'quantity' => $quantity
                    ]);
                }
            }
        }

        return $shoppingList->sortBy('name');
    }

    public function generateShoppingListForRecipes(Collection $recipes): Collection
    {
        $shoppingList = collect();

        foreach ($recipes as $recipe) {
            foreach ($recipe->ingredients as $ingredient) {
                $quantity = $ingredient->pivot->quantity;
                
                $existingItem = $shoppingList->first(function ($item) use ($ingredient) {
                    return $item['ingredient_id'] === $ingredient->id;
                });

                if ($existingItem) {
                    $existingItem['quantity'] += $quantity;
                } else {
                    $shoppingList->push([
                        'ingredient_id' => $ingredient->id,
                        'name' => $ingredient->name,
                        'unit' => $ingredient->unit,
                        'quantity' => $quantity
                    ]);
                }
            }
        }

        return $shoppingList->sortBy('name');
    }

    /**
     * Génère une liste de courses à partir d'un menu (nouvelle structure)
     */
    public function generateFromMenu(Menu $menu): array
    {
        $shoppingList = collect();

        foreach ($menu->meals as $meal) {
            foreach ($meal->mealRecipes as $mealRecipe) {
                $recipe = $mealRecipe->recipe;
                $portionMultiplier = ($mealRecipe->portion ?? 1) / ($recipe->portion ?? 1);
                foreach ($recipe->ingredients as $ingredient) {
                    $quantity = $ingredient->pivot->quantity * $portionMultiplier;

                    // Chercher si l'ingrédient existe déjà dans la liste
                    $existingItem = $shoppingList->first(function ($item) use ($ingredient) {
                        return $item['name'] === $ingredient->name && $item['unit'] === $ingredient->unit;
                    });

                    if ($existingItem) {
                        $existingItem['total_quantity'] += $quantity;
                        if (!in_array($recipe->title, $existingItem['recipes'])) {
                            $existingItem['recipes'][] = $recipe->title;
                        }
                    } else {
                        $shoppingList->push([
                            'name' => $ingredient->name,
                            'unit' => $ingredient->unit,
                            'total_quantity' => $quantity,
                            'recipes' => [$recipe->title]
                        ]);
                    }
                }
            }
        }

        return $shoppingList->sortBy('name')->values()->toArray();
    }

    /**
     * Génère une liste de courses à partir d'une collection de recettes
     */
    public function generateFromRecipes(Collection $recipes, array $portions = []): array
    {
        $shoppingList = collect();

        foreach ($recipes as $recipe) {
            $recipePortions = $portions[$recipe->id] ?? $recipe->portion;
            $multiplier = $recipePortions / $recipe->portion;

            foreach ($recipe->ingredients as $ingredient) {
                $quantity = $ingredient->pivot->quantity * $multiplier;
                
                // Chercher si l'ingrédient existe déjà dans la liste
                $existingItem = $shoppingList->first(function ($item) use ($ingredient) {
                    return $item['name'] === $ingredient->name;
                });

                if ($existingItem) {
                    // Ajouter la quantité à l'ingrédient existant
                    $existingItem['total_quantity'] += $quantity;
                    if (!in_array($recipe->title, $existingItem['recipes'])) {
                        $existingItem['recipes'][] = $recipe->title;
                    }
                } else {
                    // Ajouter un nouvel ingrédient
                    $shoppingList->push([
                        'name' => $ingredient->name,
                        'unit' => $ingredient->unit,
                        'total_quantity' => $quantity,
                        'recipes' => [$recipe->title]
                    ]);
                }
            }
        }

        return $shoppingList->sortBy('name')->values()->toArray();
    }
} 