<?php

namespace App\Http\Controllers;

use App\Models\ShoppingList;
use App\Models\Menu;
use App\Services\ShoppingListService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GlobalShoppingListController extends Controller
{
    protected ShoppingListService $shoppingListService;

    public function __construct(ShoppingListService $shoppingListService)
    {
        $this->shoppingListService = $shoppingListService;
    }

    /**
     * Affiche la page de la liste de courses globale
     */
    public function index()
    {
        $uncheckedItems = ShoppingList::getUncheckedItems()->get();
        $checkedItems = ShoppingList::getCheckedItems()->limit(10)->get(); // Derniers 10 éléments cochés
        $recipes = \App\Models\Recipe::orderBy('title')->get(['id', 'title', 'portion']);
        return view('shopping-list.global', compact('uncheckedItems', 'checkedItems', 'recipes'));
    }

    /**
     * Ajoute des éléments depuis un menu à la liste globale
     */
    public function addFromMenu(Request $request, $menuId)
    {
        $menu = Menu::with(['meals.mealRecipes.recipe.ingredients'])->findOrFail($menuId);
        
        // Générer la liste de courses du menu (nouvelle structure)
        $shoppingListData = $this->shoppingListService->generateFromMenu($menu);
        
        // Ajouter à la liste globale
        ShoppingList::addFromMenu($menu, $shoppingListData);
        
        return response()->json([
            'message' => 'Éléments ajoutés à la liste de courses globale',
            'added_count' => count($shoppingListData)
        ]);
    }

    /**
     * Ajoute un élément personnalisé à la liste
     */
    public function addCustomItem(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:500'
        ]);

        $item = ShoppingList::addCustomItem(
            $request->name,
            $request->quantity ?? '1',
            $request->unit,
            $request->notes
        );

        return response()->json([
            'message' => 'Élément ajouté à la liste',
            'item' => $item
        ]);
    }

    /**
     * Met à jour le statut d'un élément (coché/décoché)
     */
    public function updateStatus(Request $request, $itemId)
    {
        $request->validate([
            'is_checked' => 'required|boolean'
        ]);

        $item = ShoppingList::findOrFail($itemId);
        $item->update(['is_checked' => $request->is_checked]);

        return response()->json([
            'message' => $request->is_checked ? 'Élément marqué comme trouvé' : 'Élément marqué comme non trouvé',
            'item' => $item
        ]);
    }

    /**
     * Supprime un élément de la liste
     */
    public function destroy($itemId)
    {
        $item = ShoppingList::findOrFail($itemId);
        $item->delete();

        return response()->json([
            'message' => 'Élément supprimé de la liste'
        ]);
    }

    /**
     * Supprime tous les éléments cochés
     */
    public function clearChecked()
    {
        try {
            // Récupérer d'abord le nombre d'éléments à supprimer
            $itemsToDelete = ShoppingList::where('is_checked', true)->get();
            $deletedCount = $itemsToDelete->count();
            
            // Supprimer les éléments un par un pour éviter les problèmes
            foreach ($itemsToDelete as $item) {
                $item->delete();
            }

            return response()->json([
                'success' => true,
                'message' => "{$deletedCount} éléments supprimés",
                'deleted_count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du nettoyage : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Décochage de tous les éléments (remet tout dans la liste)
     */
    public function uncheckAll()
    {
        try {
            $updatedCount = ShoppingList::where('is_checked', true)->update(['is_checked' => false]);

            return response()->json([
                'success' => true,
                'message' => "{$updatedCount} éléments remis dans la liste",
                'updated_count' => $updatedCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du décochage : ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API pour récupérer les éléments de la liste
     */
    public function getItems()
    {
        $uncheckedItems = ShoppingList::getUncheckedItems()->get();
        $checkedItems = ShoppingList::getCheckedItems()->limit(10)->get();

        return response()->json([
            'unchecked_items' => $uncheckedItems,
            'checked_items' => $checkedItems
        ]);
    }

    /**
     * Supprime tous les éléments de la liste (cochés ou non)
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
     * Ajoute les ingrédients d'une recette à la liste de courses globale
     */
    public function addFromRecipe(Request $request)
    {
        $request->validate([
            'recipe_id' => 'required|integer|exists:recipes,id',
            'portion' => 'nullable|numeric|min:0.1'
        ]);
        $recipe = \App\Models\Recipe::with('ingredients')->findOrFail($request->recipe_id);
        $portion = $request->input('portion', $recipe->portion ?? 1);
        foreach ($recipe->ingredients as $ingredient) {
            $quantity = $ingredient->pivot->quantity * ($portion / max($recipe->portion ?? 1, 1));
            \App\Models\ShoppingList::addCustomItem(
                $ingredient->name,
                $quantity,
                $ingredient->unit
            );
        }
        return response()->json(['success' => true, 'message' => 'Ingrédients ajoutés à la liste de courses']);
    }
}
