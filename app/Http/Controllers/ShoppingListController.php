<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Recipe;
use App\Services\ShoppingListService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class ShoppingListController extends Controller
{
    protected ShoppingListService $shoppingListService;

    public function __construct(ShoppingListService $shoppingListService)
    {
        $this->shoppingListService = $shoppingListService;
    }

    /**
     * Générer une liste de courses à partir d'un menu
     */
    public function fromMenu(string $menuId): JsonResponse
    {
        $menu = Menu::with(['recipes.ingredients'])->findOrFail($menuId);
        $shoppingList = $this->shoppingListService->generateShoppingList($menu);
        
        return response()->json([
            'menu' => $menu,
            'shopping_list' => $shoppingList
        ]);
    }

    /**
     * Générer une liste de courses à partir de recettes sélectionnées
     */
    public function fromRecipes(Request $request): JsonResponse
    {
        $request->validate([
            'recipe_ids' => 'required|array',
            'recipe_ids.*' => 'exists:recipes,id',
        ]);

        $recipes = Recipe::with(['ingredients'])->whereIn('id', $request->recipe_ids)->get();
        $shoppingList = $this->shoppingListService->generateShoppingListForRecipes($recipes);

        return response()->json([
            'recipes' => $recipes,
            'shopping_list' => $shoppingList
        ]);
    }

    /**
     * Supprime tous les éléments de la liste de courses (API)
     */
    public function destroyAll()
    {
        try {
            $deletedCount = \App\Models\ShoppingList::count();
            \App\Models\ShoppingList::truncate();
            return response()->json([
                'success' => true,
                'message' => "{$deletedCount} éléments supprimés",
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression totale : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Met à jour le statut d'un élément (coché/décoché) via l'API
     */
    public function updateStatus(Request $request, $itemId)
    {
        $request->validate([
            'is_checked' => 'required|boolean'
        ]);

        $item = \App\Models\ShoppingList::findOrFail($itemId);
        $item->update(['is_checked' => $request->is_checked]);

        return response()->json([
            'message' => $request->is_checked ? 'Élément marqué comme trouvé' : 'Élément marqué comme non trouvé',
            'item' => $item
        ]);
    }
}
