<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShoppingList extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'unit',
        'is_checked',
        'notes',
        'source'
    ];

    protected $casts = [
        'is_checked' => 'boolean'
    ];

    /**
     * Récupère tous les éléments non cochés
     */
    public static function getUncheckedItems()
    {
        return static::where('is_checked', false)->orderBy('created_at', 'asc');
    }

    /**
     * Récupère tous les éléments cochés
     */
    public static function getCheckedItems()
    {
        return static::where('is_checked', true)->orderBy('updated_at', 'desc');
    }

    /**
     * Ajoute des éléments depuis un menu
     */
    public static function addFromMenu($menu, $shoppingListData)
    {
        foreach ($shoppingListData as $item) {
            // Vérifier si l'élément existe déjà
            $existingItem = static::where('name', $item['name'])
                                 ->where('unit', $item['unit'] ?? null)
                                 ->where('is_checked', false)
                                 ->first();

            if ($existingItem) {
                // Mettre à jour la quantité
                $existingQuantity = (float) $existingItem->quantity;
                $newQuantity = (float) ($item['total_quantity'] ?? $item['quantity'] ?? 1);
                $existingItem->update([
                    'quantity' => (string) ($existingQuantity + $newQuantity)
                ]);
            } else {
                // Créer un nouvel élément
                static::create([
                    'name' => $item['name'],
                    'quantity' => $item['total_quantity'] ?? $item['quantity'] ?? '1',
                    'unit' => $item['unit'] ?? null,
                    'source' => "Menu: {$menu->name}",
                    'is_checked' => false
                ]);
            }
        }
    }

    /**
     * Ajoute un élément personnalisé
     */
    public static function addCustomItem($name, $quantity = '1', $unit = null, $notes = null)
    {
        // Vérifier si l'élément existe déjà
        $existingItem = static::where('name', $name)
                             ->where('unit', $unit)
                             ->where('is_checked', false)
                             ->first();

        if ($existingItem) {
            // Mettre à jour la quantité
            $existingQuantity = (float) $existingItem->quantity;
            $newQuantity = (float) $quantity;
            $existingItem->update([
                'quantity' => (string) ($existingQuantity + $newQuantity)
            ]);
            return $existingItem;
        } else {
            // Créer un nouvel élément
            return static::create([
                'name' => $name,
                'quantity' => $quantity,
                'unit' => $unit,
                'notes' => $notes,
                'source' => 'Manuel',
                'is_checked' => false
            ]);
        }
    }
}
