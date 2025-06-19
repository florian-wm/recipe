<?php

namespace App\Http\Controllers;

use App\Models\CustomItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CustomItemController extends Controller
{
    /**
     * Récupère tous les éléments personnalisés sauvegardés
     */
    public function index(): JsonResponse
    {
        $items = CustomItem::where('is_saved', true)
                          ->orderBy('usage_count', 'desc')
                          ->orderBy('name')
                          ->get();
        
        return response()->json($items);
    }

    /**
     * Récupère les éléments suggérés
     */
    public function suggestions(): JsonResponse
    {
        $suggestions = CustomItem::getSuggestedItems(10);
        return response()->json($suggestions);
    }

    /**
     * Sauvegarde un nouvel élément personnalisé
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:50',
        ]);

        // Vérifier si l'élément existe déjà
        $existingItem = CustomItem::where('name', $request->name)
                                 ->where('is_saved', true)
                                 ->first();

        if ($existingItem) {
            $existingItem->incrementUsage();
            return response()->json($existingItem);
        }

        $customItem = CustomItem::create([
            'name' => $request->name,
            'quantity' => $request->quantity ?? '1',
            'unit' => $request->unit,
            'is_saved' => true,
            'usage_count' => 1
        ]);

        return response()->json($customItem, 201);
    }

    /**
     * Met à jour un élément personnalisé
     */
    public function update(Request $request, CustomItem $customItem): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:50',
        ]);

        $customItem->update([
            'name' => $request->name,
            'quantity' => $request->quantity ?? '1',
            'unit' => $request->unit,
        ]);

        return response()->json($customItem);
    }

    /**
     * Supprime un élément personnalisé
     */
    public function destroy(CustomItem $customItem): JsonResponse
    {
        $customItem->delete();
        return response()->json(['message' => 'Élément supprimé avec succès']);
    }

    /**
     * Incrémente le compteur d'utilisation d'un élément
     */
    public function incrementUsage(CustomItem $customItem): JsonResponse
    {
        $customItem->incrementUsage();
        return response()->json(['message' => 'Compteur d\'utilisation incrémenté']);
    }
}
