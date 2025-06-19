<?php
use Illuminate\Support\Facades\Route;

// Recettes : routes spécifiques d'abord
Route::get('recipes/export', [App\Http\Controllers\RecipeController::class, 'export']);
Route::post('recipes/import', [App\Http\Controllers\RecipeController::class, 'import']);
Route::put('recipes/{id}/favorite', [App\Http\Controllers\RecipeController::class, 'favorite']);
Route::apiResource('recipes', App\Http\Controllers\RecipeController::class);
// Menus
Route::apiResource('menus', App\Http\Controllers\MenuController::class);
// Ingrédients
Route::apiResource('ingredients', App\Http\Controllers\IngredientController::class);
// Création rapide d'un ingrédient
Route::post('ingredients/quick-create', [App\Http\Controllers\IngredientController::class, 'quickCreate']);
// Catégories
Route::apiResource('categories', App\Http\Controllers\CategoryController::class, ['names' => [
    'index' => "categories.api.index",
    'show' => null,
    'store' => null,
    'update' => null,
    'destroy' => null,
]]);
// Shopping List (global)
Route::get('shopping-list', [App\Http\Controllers\ShoppingListController::class, 'index']);
Route::post('shopping-list/recipes', [App\Http\Controllers\ShoppingListController::class, 'fromRecipes']);
Route::put('shopping-list/{itemId}/status', [App\Http\Controllers\ShoppingListController::class, 'updateStatus']);
Route::delete('shopping-list/clear-checked', [App\Http\Controllers\ShoppingListController::class, 'clearChecked']);
Route::put('shopping-list/uncheck-all', [App\Http\Controllers\ShoppingListController::class, 'uncheckAll']);
Route::get('shopping-list/items', [App\Http\Controllers\ShoppingListController::class, 'getItems']);
Route::delete('shopping-list/all', [App\Http\Controllers\ShoppingListController::class, 'destroyAll']);
Route::delete('shopping-list/{itemId}', [App\Http\Controllers\ShoppingListController::class, 'destroy']);

// Custom Items
Route::post('shopping-list/custom-item', [App\Http\Controllers\CustomItemController::class, 'store']); 