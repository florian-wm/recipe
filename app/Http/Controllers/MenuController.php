<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Recipe;
use App\Models\Meal;
use App\Models\MealRecipe;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $menus = Menu::with(['recipes'])->get();
        return response()->json($menus);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): JsonResponse
    {
        $recipes = Recipe::all();
        return response()->json(['recipes' => $recipes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'meals' => 'required|array',
            'meals.*.day' => 'required|string',
            'meals.*.label' => 'required|string',
            'meals.*.recipes' => 'required|array',
            'meals.*.recipes.*.id' => 'required|exists:recipes,id',
            'meals.*.recipes.*.portion' => 'nullable|integer|min:1',
        ]);
        $menu = Menu::create(['name' => $request->name]);

        foreach ($request->meals as $order => $mealData) {
            $meal = $menu->meals()->create([
                'day' => $mealData['day'],
                'label' => $mealData['label'],
                'order' => $order,
            ]);
            foreach ($mealData['recipes'] as $recipeData) {
                $meal->mealRecipes()->create([
                    'recipe_id' => $recipeData['id'],
                    'portion' => $recipeData['portion'] ?? 1,
                ]);
            }
        }

        return response()->json($menu->load('meals.mealRecipes.recipe'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $menu = Menu::with(['recipes.ingredients', 'recipes.steps'])->findOrFail($id);
        return response()->json($menu);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): JsonResponse
    {
        $menu = Menu::with(['recipes'])->findOrFail($id);
        $recipes = Recipe::all();
        return response()->json(['menu' => $menu, 'recipes' => $recipes]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'meals' => 'required|array',
            'meals.*.day' => 'required|string',
            'meals.*.label' => 'required|string',
            'meals.*.recipes' => 'required|array',
            'meals.*.recipes.*.id' => 'required|exists:recipes,id',
            'meals.*.recipes.*.portion' => 'nullable|integer|min:1',
        ]);

        $menu->update(['name' => $request->name]);

        // Supprimer les anciens repas et leurs plats
        foreach ($menu->meals as $oldMeal) {
            $oldMeal->mealRecipes()->delete();
            $oldMeal->delete();
        }
        foreach ($request->meals as $order => $mealData) {
            $meal = $menu->meals()->create([
                'day' => $mealData['day'],
                'label' => $mealData['label'],
                'order' => $order,
            ]);
            foreach ($mealData['recipes'] as $recipeData) {
                $meal->mealRecipes()->create([
                    'recipe_id' => $recipeData['id'],
                    'portion' => $recipeData['portion'] ?? 1,
                ]);
            }
        }

        return response()->json($menu->load('meals.mealRecipes.recipe'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
        return response()->json(null, 204);
    }
}
