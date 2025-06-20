<?php

use App\Models\User;
use App\Models\Recipe;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Ingredient;
use App\Models\Step;

// Test index recettes (public)
test('la page index des recettes affiche les titres et catégories', function () {
    $cat = Category::factory()->create(['name' => 'Desserts']);
    $recipe = Recipe::factory()->create(['title' => 'Tarte aux pommes']);
    $recipe->categories()->attach($cat);
    $ingredient = Ingredient::factory()->create(['name' => 'Pomme']);
    $recipe->ingredients()->attach($ingredient, ['quantity' => 2]);
    Step::factory()->create(['recipe_id' => $recipe->id, 'description' => 'Couper les pommes', 'order' => 1]);

    $response = $this->get('/recettes');
    $response->assertStatus(200)
        ->assertSee('Tarte aux pommes')
        ->assertSee('Desserts')
        ->assertSee('Pomme');
});

test('la page détail recette affiche les infos principales', function () {
    $cat = Category::factory()->create(['name' => 'Entrées']);
    $recipe = Recipe::factory()->create(['title' => 'Salade verte', 'description' => 'Une salade toute simple.']);
    $recipe->categories()->attach($cat);
    $ingredient = Ingredient::factory()->create(['name' => 'Salade']);
    $recipe->ingredients()->attach($ingredient, ['quantity' => 1]);
    Step::factory()->create(['recipe_id' => $recipe->id, 'description' => 'Laver la salade', 'order' => 1]);

    $response = $this->get('/recettes/' . $recipe->id);
    $response->assertStatus(200)
        ->assertSee('Salade verte')
        ->assertSee('Une salade toute simple.')
        ->assertSee('Salade')
        ->assertSee('Laver la salade');
});

test('la page index des menus affiche les noms de menus', function () {
    $menu = Menu::factory()->create(['name' => 'Menu du dimanche']);
    $response = $this->get('/menus');
    $response->assertStatus(200)
        ->assertSee('Menu du dimanche');
});

test('la page index des catégories est accessible et affiche le titre', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->get('/categories');
    $response->assertStatus(200)
        ->assertSee('Catégories');
});

test('le formulaire de création de recette est accessible pour un utilisateur authentifié', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->get('/recettes/create');
    $response->assertStatus(200)
        ->assertSee('Nouvelle Recette');
});

test('le formulaire d\'édition de recette est accessible pour un utilisateur authentifié', function () {
    $user = User::factory()->create();
    $recipe = Recipe::factory()->create(['title' => 'Gratin dauphinois']);
    $this->actingAs($user);
    $response = $this->get('/recettes/' . $recipe->id . '/edit');
    $response->assertStatus(200)
        ->assertSee('Gratin dauphinois');
});

test('la page recettes par catégorie affiche les bonnes recettes', function () {
    $cat = Category::factory()->create(['name' => 'Soupes']);
    $recipe = Recipe::factory()->create(['title' => 'Soupe de légumes']);
    $recipe->categories()->attach($cat);
    $response = $this->get('/categories/' . $cat->id . '/recettes');
    $response->assertStatus(200);
});

test('le formulaire de création de menu est accessible pour un utilisateur authentifié', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->get('/menus/create');
    $response->assertStatus(200)
        ->assertSee('Créer');
});

test('le formulaire d\'édition de menu est accessible pour un utilisateur authentifié', function () {
    $user = User::factory()->create();
    $menu = Menu::factory()->create(['name' => 'Menu test']);
    $this->actingAs($user);
    $response = $this->get('/menus/' . $menu->id . '/edit');
    $response->assertStatus(200)
        ->assertSee('Menu test');
});

test('la liste de courses à partir de recettes sélectionnées fonctionne', function () {
    $user = User::factory()->create();
    $recipe = Recipe::factory()->create(['title' => 'Cake salé']);
    $ingredient = Ingredient::factory()->create(['name' => 'Farine']);
    $recipe->ingredients()->attach($ingredient, ['quantity' => 100]);
    $this->actingAs($user);
    $response = $this->post('/shopping-list/recipes', [
        'recipe_ids' => [$recipe->id],
        'portions' => [$recipe->id => 2],
        'custom_items' => [['name' => 'Bière', 'quantity' => '1', 'unit' => 'bouteille']]
    ]);
    $response->assertStatus(200);
});

test('la création de recette fonctionne et redirige', function () {
    $user = User::factory()->create();
    $cat = Category::factory()->create();
    $ingredient = Ingredient::factory()->create();
    $this->actingAs($user);
    $response = $this->post('/recettes', [
        'title' => 'Clafoutis',
        'description' => 'Un bon clafoutis',
        'portion' => 4,
        'age' => 2,
        'ingredients' => [
            ['name' => $ingredient->name, 'quantity' => 100, 'unit' => $ingredient->unit]
        ],
        'category_ids' => [$cat->id],
        'steps' => [
            ['description' => 'Mélanger', 'order' => 1]
        ]
    ]);
    $response->assertRedirect('/recettes');
    $this->assertDatabaseHas('recipes', ['title' => 'Clafoutis']);
});

test('la validation échoue si un champ obligatoire manque à la création de recette', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $response = $this->post('/recettes', [
        'title' => '',
        'description' => '',
        'portion' => '',
        'age' => '',
        'ingredients' => [],
        'steps' => []
    ]);
    $response->assertSessionHasErrors(['title', 'description', 'portion', 'age', 'ingredients', 'steps']);
});

test('la mise à jour d\'un menu fonctionne et redirige', function () {
    $user = User::factory()->create();
    $menu = Menu::factory()->create(['name' => 'Ancien nom']);
    $this->actingAs($user);
    $response = $this->put('/menus/' . $menu->id, [
        'name' => 'Nouveau nom',
        'meals_json' => json_encode([])
    ]);
    $response->assertRedirect('/menus');
    $this->assertDatabaseHas('menus', ['name' => 'Nouveau nom']);
});

test('la validation échoue si le nom du menu est manquant à la mise à jour', function () {
    $user = User::factory()->create();
    $menu = Menu::factory()->create();
    $this->actingAs($user);
    $response = $this->put('/menus/' . $menu->id, [
        'name' => '',
        'meals_json' => json_encode([])
    ]);
    $response->assertSessionHasErrors(['name']);
});

test('un utilisateur non authentifié est redirigé pour les routes protégées', function () {
    $recipe = Recipe::factory()->create();
    $menu = Menu::factory()->create();
    $this->get('/recettes/create')->assertRedirect('/login');
    $this->get('/recettes/' . $recipe->id . '/edit')->assertRedirect('/login');
    $this->get('/menus/create')->assertRedirect('/login');
    $this->get('/menus/' . $menu->id . '/edit')->assertRedirect('/login');
    $this->post('/recettes', [])->assertRedirect('/login');
    $this->put('/menus/' . $menu->id, [])->assertRedirect('/login');
}); 