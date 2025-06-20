<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $categories = Category::withCount('recipes')->get();
        return response()->json($categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): JsonResponse
    {
        return response()->json(['message' => 'Formulaire de création de catégorie']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
        ]);

        $category = Category::create($request->only(['name', 'description', 'color']));
        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $category = Category::with(['recipes.ingredients', 'recipes.steps'])->findOrFail($id);
        return response()->json($category);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
            'color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
        ]);

        $category->update($request->only(['name', 'description', 'color']));
        return response()->json($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Catégorie supprimée avec succès']);
    }
}
