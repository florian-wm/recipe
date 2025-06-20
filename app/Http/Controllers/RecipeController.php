<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $recipes = Recipe::with(['ingredients', 'steps', 'categories'])->get();
        return response()->json($recipes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): JsonResponse
    {
        $ingredients = Ingredient::all();
        $categories = Category::all();
        return response()->json(['ingredients' => $ingredients, 'categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable',
            'portion' => 'nullable|integer|min:1',
            'age' => 'nullable|integer|min:0',
            'ingredients' => 'required|array',
            'ingredients.*.id' => 'nullable|exists:ingredients,id',
            'ingredients.*.name' => 'nullable|string|max:255',
            'ingredients.*.quantity' => 'nullable|numeric|min:0',
            'ingredients.*.unit' => 'nullable|string|max:50',
            'steps' => 'required|array',
            'steps.*.description' => 'required|string',
            'steps.*.order' => 'required|integer|min:1',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        // Vérification manuelle : chaque ingrédient doit avoir un id ou un nom
        foreach ($request->ingredients as $i => $ingredient) {
            if (empty($ingredient['id']) && empty($ingredient['name'])) {
                return response()->json([
                    'message' => 'Indiquez un ingrédient existant ou un nom.',
                    'errors' => ["ingredients.$i.name" => 'Indiquez un ingrédient existant ou un nom.']
                ], 422);
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

        $recipe->load(['ingredients', 'steps', 'categories']);
        return response()->json($recipe, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $recipe = Recipe::with(['ingredients', 'steps', 'categories'])->findOrFail($id);
        return response()->json($recipe);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): JsonResponse
    {
        $recipe = Recipe::with(['ingredients', 'steps', 'categories'])->findOrFail($id);
        $ingredients = Ingredient::all();
        $categories = Category::all();
        return response()->json(['recipe' => $recipe, 'ingredients' => $ingredients, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $recipe = Recipe::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable',
            'portion' => 'nullable|integer|min:1',
            'age' => 'nullable|integer|min:0',
            'ingredients' => 'required|array',
            'ingredients.*.id' => 'nullable|exists:ingredients,id',
            'ingredients.*.name' => 'nullable|string|max:255',
            'ingredients.*.quantity' => 'nullable|numeric|min:0',
            'ingredients.*.unit' => 'nullable|string|max:50',
            'steps' => 'required|array',
            'steps.*.description' => 'required|string',
            'steps.*.order' => 'required|integer|min:1',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        if ($request->hasFile('image')) {
            if ($recipe->image && !filter_var($recipe->image, FILTER_VALIDATE_URL) && \Storage::disk('public')->exists($recipe->image)) {
                \Storage::disk('public')->delete($recipe->image);
            }
            $imagePath = $request->file('image')->store('recipes', 'public');
            $recipe->image = $imagePath;
        } elseif ($request->filled('image') && filter_var($request->image, FILTER_VALIDATE_URL)) {
            $recipe->image = $request->image;
        }

        $recipe->title = $request->title;
        $recipe->description = $request->description;
        $recipe->portion = $request->portion;
        $recipe->age = $request->age;
        $recipe->save();

        $recipe->ingredients()->detach();
        foreach ($request->ingredients as $ingredient) {
            $recipe->ingredients()->attach($ingredient['id'], ['quantity' => $ingredient['quantity']]);
        }

        $recipe->categories()->sync($request->category_ids ?? []);

        $recipe->steps()->delete();
        foreach ($request->steps as $step) {
            $recipe->steps()->create([
                'description' => $step['description'],
                'order' => $step['order']
            ]);
        }

        $recipe->load(['ingredients', 'steps', 'categories']);
        return response()->json($recipe);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->delete();
        return response()->json(null, 204);
    }

    /**
     * Marquer ou démarquer une recette comme favorite.
     */
    public function favorite(Request $request, string $id): JsonResponse
    {
        $recipe = Recipe::findOrFail($id);
        $request->validate([
            'is_favorite' => 'required|boolean',
        ]);
        $recipe->is_favorite = $request->is_favorite;
        $recipe->save();
        return response()->json($recipe);
    }

    /**
     * Exporter une ou plusieurs recettes au format JSON.
     * ids[] (query) optionnel : tableau d'identifiants à exporter, sinon toutes.
     */
    public function export(Request $request)
    {
        $ids = $request->query('ids');
        $query = Recipe::with(['ingredients', 'steps', 'categories']);
        if ($ids && is_array($ids)) {
            $query->whereIn('id', $ids);
        }
        $recipes = $query->get();
        $json = $recipes->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $filename = 'recettes_' . now()->format('Ymd_His') . '.json';
        return response($json, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Importer une ou plusieurs recettes depuis un fichier JSON.
     * Gère les doublons par titre (ignore si déjà existant).
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json,txt,application/json',
        ]);
        $json = file_get_contents($request->file('file')->getRealPath());
        $data = json_decode($json, true);
        if (!is_array($data)) {
            return response()->json(['error' => 'Format JSON invalide'], 422);
        }
        $imported = 0;
        foreach ($data as $recipeData) {
            if (isset($recipeData['title']) && Recipe::where('title', $recipeData['title'])->exists()) {
                continue; // Doublon
            }
            $recipe = Recipe::create([
                'title' => $recipeData['title'] ?? '',
                'description' => $recipeData['description'] ?? '',
                'image' => $recipeData['image'] ?? null,
                'portion' => $recipeData['portion'] ?? 1,
                'age' => $recipeData['age'] ?? 0,
                'is_favorite' => $recipeData['is_favorite'] ?? false,
            ]);
            // Ingrédients
            if (!empty($recipeData['ingredients'])) {
                foreach ($recipeData['ingredients'] as $ingredient) {
                    if (isset($ingredient['id']) && isset($ingredient['quantity'])) {
                        $recipe->ingredients()->attach($ingredient['id'], ['quantity' => $ingredient['quantity']]);
                    }
                }
            }
            // Catégories
            if (!empty($recipeData['categories'])) {
                $catIds = collect($recipeData['categories'])->pluck('id')->all();
                $recipe->categories()->attach($catIds);
            }
            // Étapes
            if (!empty($recipeData['steps'])) {
                foreach ($recipeData['steps'] as $step) {
                    $recipe->steps()->create([
                        'description' => $step['description'] ?? '',
                        'order' => $step['order'] ?? 1
                    ]);
                }
            }
            $imported++;
        }
        return response()->json(['imported' => $imported]);
    }
}
