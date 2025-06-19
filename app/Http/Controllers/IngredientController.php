<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $ingredients = Ingredient::all();
        return response()->json($ingredients);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): JsonResponse
    {
        return response()->json(['message' => 'Formulaire de création d\'ingrédient']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:ingredients',
            'unit' => 'nullable|string|max:50',
        ]);

        $ingredient = Ingredient::create($request->only(['name', 'unit']));
        return response()->json($ingredient, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $ingredient = Ingredient::with(['recipes'])->findOrFail($id);
        return response()->json($ingredient);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): JsonResponse
    {
        $ingredient = Ingredient::findOrFail($id);
        return response()->json($ingredient);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $ingredient = Ingredient::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:ingredients,name,' . $id,
            'unit' => 'nullable|string|max:50',
        ]);

        $ingredient->update($request->only(['name', 'unit']));
        return response()->json($ingredient);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->delete();
        return response()->json(['message' => 'Ingrédient supprimé avec succès']);
    }

    /**
     * Création rapide d'un ingrédient (AJAX)
     */
    public function quickCreate(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'nullable|string|max:50',
        ]);

        // Vérifier si l'ingrédient existe déjà (insensible à la casse)
        $ingredient = Ingredient::whereRaw('LOWER(name) = ?', [strtolower($request->name)])
            ->where('unit', $request->unit)
            ->first();

        if ($ingredient) {
            return response()->json($ingredient);
        }

        $ingredient = Ingredient::create([
            'name' => $request->name,
            'unit' => $request->unit,
        ]);

        return response()->json($ingredient, 201);
    }
}
