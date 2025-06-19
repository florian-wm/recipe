<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('recettes.index');
    }
    return view('welcome');
});

// Routes publiques (consultation)
Route::get('/recettes', [App\Http\Controllers\WebController::class, 'index'])->name('recettes.index');
Route::middleware('auth')->group(function () {
    Route::get('/recettes/create', [App\Http\Controllers\WebController::class, 'create'])->name('recettes.create');
});
Route::get('/recettes/{id}', [App\Http\Controllers\WebController::class, 'show'])->name('recettes.show');
Route::get('/categories/{categoryId}/recettes', [App\Http\Controllers\WebController::class, 'byCategory'])->name('recettes.by-category');
Route::get('/menus', [App\Http\Controllers\WebController::class, 'menus'])->name('web.menus.index');
Route::get('/shopping-list', [App\Http\Controllers\GlobalShoppingListController::class, 'index'])->name('web.shopping-list.global');

// Routes protégées (création, édition, suppression)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Recettes
    Route::get('/recettes/{id}/edit', [App\Http\Controllers\WebController::class, 'edit'])->name('recettes.edit');
    Route::put('/recettes/{id}', [App\Http\Controllers\WebController::class, 'update'])->name('recettes.update');
    Route::post('/recettes', [App\Http\Controllers\WebController::class, 'store'])->name('recettes.store');
    // Menus
    Route::get('/menus/create', [App\Http\Controllers\WebController::class, 'createMenu'])->name('web.menus.create');
    Route::get('/menus/{menuId}/edit', [App\Http\Controllers\WebController::class, 'editMenu'])->name('web.menus.edit');
    Route::put('/menus/{menuId}', [App\Http\Controllers\WebController::class, 'updateMenu'])->name('web.menus.update');
    // Shopping list depuis recettes sélectionnées
    Route::post('/shopping-list/recipes', [App\Http\Controllers\WebController::class, 'shoppingListFromRecipes'])->name('web.shopping-list.recipes');
    // Ajout d'éléments personnalisés à la liste globale
    Route::post('/shopping-list/custom-item', [App\Http\Controllers\GlobalShoppingListController::class, 'addCustomItem'])->name('web.shopping-list.add-custom');
    // Mise à jour et suppression d'éléments de la liste globale
    Route::put('/shopping-list/{itemId}/status', [App\Http\Controllers\GlobalShoppingListController::class, 'updateStatus'])->name('web.shopping-list.update-status');
    Route::delete('/shopping-list/clear-checked', [App\Http\Controllers\GlobalShoppingListController::class, 'clearChecked'])->name('web.shopping-list.clear-checked');
    Route::delete('/shopping-list/{itemId}', [App\Http\Controllers\GlobalShoppingListController::class, 'destroy'])->name('web.shopping-list.destroy');
    Route::put('/shopping-list/uncheck-all', [App\Http\Controllers\GlobalShoppingListController::class, 'uncheckAll'])->name('web.shopping-list.uncheck-all');
    Route::get('/shopping-list/items', [App\Http\Controllers\GlobalShoppingListController::class, 'getItems'])->name('web.shopping-list.get-items');
    // Ajout d'un menu à la liste globale
    Route::post('/menus/{menuId}/add-to-shopping-list', [App\Http\Controllers\GlobalShoppingListController::class, 'addFromMenu'])->name('web.shopping-list.add-from-menu');
    // Suppression d'une recette
    Route::delete('/recettes/{id}', [App\Http\Controllers\WebController::class, 'destroy'])->name('recettes.destroy');
    // Gestion des catégories
    Route::get('/categories', [App\Http\Controllers\WebController::class, 'categoriesView'])->name('categories.index');
    Route::get('/categories/create', [App\Http\Controllers\CategoryController::class, 'create'])->name('categories.create');
    Route::get('/categories/{id}/edit', [App\Http\Controllers\CategoryController::class, 'edit'])->name('categories.edit');
    // Suppression de la liste de courses
    Route::post('/shopping-list/add-recipe', [App\Http\Controllers\GlobalShoppingListController::class, 'addFromRecipe'])->name('web.shopping-list.add-recipe');
});

require __DIR__.'/auth.php';
