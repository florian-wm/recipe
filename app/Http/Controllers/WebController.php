<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Menu;
use App\Services\ShoppingListService;
use Illuminate\Http\Request;
use App\Models\Meal;
use App\Models\MealRecipe;

class WebController extends Controller
{
    /**
     * Affiche la page d'accueil avec toutes les recettes
     */
    public function index()
    {
        $recipes = Recipe::with(['ingredients', 'steps', 'categories'])->get();
        $categories = Category::withCount('recipes')->get();
        return view('recipes.index', compact('recipes', 'categories'));
    }

    /**
     * Affiche les détails d'une recette spécifique
     */
    public function show($id)
    {
        $recipe = Recipe::with(['ingredients', 'steps', 'categories'])->findOrFail($id);
        return view('recipes.show', compact('recipe'));
    }

    /**
     * Affiche le formulaire de création d'une recette
     */
    public function create()
    {
        $ingredients = Ingredient::all();
        $categories = Category::all();
        return view('recipes.create', compact('ingredients', 'categories'));
    }

    /**
     * Affiche le formulaire d'édition d'une recette
     */
    public function edit($id)
    {
        $recipe = Recipe::with(['ingredients', 'steps', 'categories'])->findOrFail($id);
        $ingredients = Ingredient::all();
        $categories = Category::all();
        return view('recipes.edit', compact('recipe', 'ingredients', 'categories'));
    }

    /**
     * Affiche les recettes par catégorie
     */
    public function byCategory($categoryId)
    {
        $category = Category::with(['recipes.ingredients', 'recipes.steps', 'recipes.categories'])->findOrFail($categoryId);
        $categories = Category::withCount('recipes')->get();
        return view('recipes.by-category', compact('category', 'categories'));
    }

    /**
     * Affiche la page de création de menu
     */
    public function createMenu()
    {
        $recipes = Recipe::with(['ingredients', 'categories'])->get();
        $menus = Menu::with(['meals.mealRecipes.recipe'])->get();
        return view('menus.create', compact('recipes', 'menus'));
    }

    /**
     * Affiche la liste de courses d'un menu
     */
    public function shoppingList($menuId)
    {
        $menu = Menu::with(['recipes.ingredients'])->findOrFail($menuId);
        $shoppingListService = new ShoppingListService();
        $shoppingList = $shoppingListService->generateFromMenu($menu);
        
        return view('menus.shopping-list', compact('menu', 'shoppingList'));
    }

    /**
     * Affiche la liste de courses de recettes sélectionnées
     */
    public function shoppingListFromRecipes(Request $request)
    {
        $request->validate([
            'recipe_ids' => 'required|array',
            'recipe_ids.*' => 'exists:recipes,id',
            'portions' => 'nullable|array',
            'portions.*' => 'integer|min:1',
            'custom_items' => 'nullable|array',
            'custom_items.*.name' => 'required|string',
            'custom_items.*.quantity' => 'nullable|string',
            'custom_items.*.unit' => 'nullable|string'
        ]);

        $recipeIds = $request->recipe_ids;
        $portions = $request->portions ?? [];
        $customItems = $request->custom_items ?? [];
        
        $recipes = Recipe::with(['ingredients'])->whereIn('id', $recipeIds)->get();
        
        $shoppingListService = new ShoppingListService();
        $shoppingList = $shoppingListService->generateFromRecipes($recipes, $portions);
        
        // Add custom items to shopping list
        foreach ($customItems as $customItem) {
            $shoppingList[] = [
                'name' => $customItem['name'],
                'unit' => $customItem['unit'] ?? '',
                'total_quantity' => $customItem['quantity'] ?? '1',
                'recipes' => ['Élément personnalisé'],
                'is_custom' => true,
                'is_checked' => false
            ];
        }
        
        return view('menus.shopping-list', compact('recipes', 'shoppingList'));
    }

    /**
     * Affiche tous les menus
     */
    public function menus()
    {
        $finishedMenus = Menu::all();
        return view('menus.index', compact('finishedMenus'));
    }

    /**
     * Affiche le formulaire d'édition d'un menu
     */
    public function editMenu($menuId)
    {
        $menu = Menu::with(['meals.mealRecipes.recipe'])->findOrFail($menuId);
        $recipes = Recipe::with(['ingredients', 'categories'])->get();

        // Préparer la structure des meals pour le JS
        $mealsForJs = [];
        foreach ($menu->meals as $meal) {
            $recipesArr = [];
            foreach ($meal->mealRecipes as $mr) {
                $recipesArr[] = [
                    'id' => $mr->recipe_id,
                    'title' => $mr->recipe ? $mr->recipe->title : '',
                    'portion' => $mr->portion ?? 1,
                ];
            }
            $mealsForJs[] = [
                'day' => $meal->day,
                'label' => $meal->label,
                'recipes' => $recipesArr,
            ];
        }

        return view('menus.edit', compact('menu', 'recipes', 'mealsForJs'));
    }

    /**
     * Met à jour un menu existant
     */
    public function updateMenu(Request $request, $menuId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'recipe_ids' => 'nullable|array',
            'recipe_ids.*' => 'exists:recipes,id',
            'portions' => 'nullable|array',
            'portions.*' => 'integer|min:1',
            'meals_json' => 'nullable|string',
        ]);

        $menu = Menu::findOrFail($menuId);
        
        $menu->update([
            'name' => $request->name,
        ]);

        // Mettre à jour les recettes du menu
        if ($request->has('recipe_ids')) {
            $recipeIds = $request->recipe_ids;
            $portions = $request->portions ?? [];
            
            // Supprimer toutes les relations existantes
            $menu->recipes()->detach();
            
            // Ajouter les nouvelles relations avec les portions
            foreach ($recipeIds as $recipeId) {
                $portion = $portions[$recipeId] ?? 1;
                $menu->recipes()->attach($recipeId, ['portion' => $portion]);
            }
        }

        // --- NOUVEAU : persistance des repas et plats du menu ---
        if ($request->filled('meals_json')) {
            $meals = json_decode($request->meals_json, true);
            // Supprimer les anciens repas et leurs plats
            foreach ($menu->meals as $oldMeal) {
                $oldMeal->mealRecipes()->delete();
                $oldMeal->delete();
            }
            // Créer les nouveaux repas et leurs plats
            if (is_array($meals)) {
                foreach ($meals as $mealData) {
                    $meal = new Meal([
                        'day' => $mealData['day'] ?? null,
                        'label' => $mealData['label'] ?? null,
                        'menu_id' => $menu->id,
                    ]);
                    $meal->save();
                    if (!empty($mealData['recipes']) && is_array($mealData['recipes'])) {
                        foreach ($mealData['recipes'] as $r) {
                            $meal->mealRecipes()->create([
                                'recipe_id' => $r['id'],
                                // 'portion' => ... // à ajouter si géré côté JS
                            ]);
                        }
                    }
                }
            }
        }
        // --- FIN NOUVEAU ---

        // Rediriger vers la liste des menus avec un message de succès
        return redirect()->route('web.menus.index')
            ->with('success', 'Menu mis à jour avec succès !');
    }

    public function categoriesView() {
        return view('categories.index');
    }

    /**
     * Enregistre une nouvelle recette (création d'ingrédients à la volée)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable',
            'portion' => 'required|integer|min:1',
            'age' => 'required|integer|min:0',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.quantity' => 'required|numeric|min:0',
            'ingredients.*.id' => 'nullable|exists:ingredients,id',
            'ingredients.*.name' => 'nullable|string|max:255',
            'ingredients.*.unit' => 'nullable|string|max:50',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'steps' => 'required|array|min:1',
            'steps.*.description' => 'required|string',
            'steps.*.order' => 'required|integer|min:1',
        ]);

        // Vérification manuelle : chaque ingrédient doit avoir un id ou un nom
        foreach ($request->ingredients as $i => $ingredient) {
            if (empty($ingredient['id']) && empty($ingredient['name'])) {
                return back()->withErrors(['ingredients.'.$i.'.name' => 'Indiquez un ingrédient existant ou un nom.'])->withInput();
            }
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('recipes', 'public');
        } elseif ($request->filled('image') && filter_var($request->image, FILTER_VALIDATE_URL)) {
            $imagePath = $request->image;
        }

        $recipe = Recipe::create([
            'title' => $request->title,
            'description' => $request->description,
            'portion' => $request->portion,
            'age' => $request->age,
            'image' => $imagePath,
        ]);

        // Attacher les ingrédients (création à la volée si besoin)
        foreach ($request->ingredients as $ingredient) {
            $ingredientId = $ingredient['id'] ?? null;
            if (!$ingredientId) {
                // Chercher un ingrédient existant (nom+unité, insensible à la casse)
                $existing = Ingredient::whereRaw('LOWER(name) = ?', [strtolower($ingredient['name'])])
                    ->where('unit', $ingredient['unit'] ?? null)
                    ->first();
                if ($existing) {
                    $ingredientId = $existing->id;
                } else {
                    $new = Ingredient::create([
                        'name' => $ingredient['name'],
                        'unit' => $ingredient['unit'] ?? null,
                    ]);
                    $ingredientId = $new->id;
                }
            }
            $recipe->ingredients()->attach($ingredientId, ['quantity' => $ingredient['quantity']]);
        }

        if ($request->has('category_ids')) {
            $recipe->categories()->attach($request->category_ids);
        }

        foreach ($request->steps as $step) {
            $recipe->steps()->create([
                'description' => $step['description'],
                'order' => $step['order']
            ]);
        }

        return redirect()->route('recettes.index')->with('success', 'Recette créée avec succès !');
    }
}
