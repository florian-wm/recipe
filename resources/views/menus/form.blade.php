<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecipeFlo - @if(isset($menu) && $menu) Modifier @else Créer @endif Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-30">
        <div class="container mx-auto flex flex-col md:flex-row items-center justify-between gap-4 py-4 px-4 md:px-8">
            <div class="flex items-center gap-4 w-full md:w-auto">
                <span class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-orange-400 to-pink-500 text-white text-2xl font-bold shadow-sm">RF</span>
                <div class="flex flex-col">
                    <span class="text-xl md:text-2xl font-semibold text-gray-900 leading-tight">RecipeFlo</span>
                    <span class="text-sm text-gray-400 leading-tight">Vos recettes, menus & courses en un clin d'œil</span>
                </div>
            </div>
            <div class="flex items-center justify-end w-full md:w-auto mt-3 md:mt-0 gap-2">
                <a href="{{ route('recettes.index') }}" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors flex items-center gap-2">
                    <i class="fas fa-utensils"></i> Recettes
                </a>
                <a href="{{ route('web.menus.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-clipboard-list mr-2"></i>Menus
                </a>
                <a href="{{ route('web.shopping-list.global') }}" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-shopping-cart mr-2"></i>Liste de Courses
                </a>
            </div>
        </div>
    </header>
    <main class="container mx-auto px-6 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recipe Selection -->
            <div class="lg:col-span-2">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-utensils mr-3 text-orange-500"></i>
                        Sélection des recettes
                    </h3>
                    <!-- Search and Filter -->
                    <div class="mb-4">
                        <input type="text" id="recipe-search" placeholder="Rechercher une recette..." 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    </div>
                    <!-- Recipe Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto">
                        @foreach($recipes as $recipe)
                        <div class="recipe-card border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow cursor-pointer" 
                             data-recipe-id="{{ $recipe->id }}" 
                             data-recipe-title="{{ $recipe->title }}"
                             data-recipe-portions="{{ $recipe->portions }}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-800 recipe-title">{{ $recipe->title }}</h4>
                                    <p class="text-sm text-gray-600">{{ $recipe->portions }} portions</p>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach($recipe->categories as $category)
                                        <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">{{ $category->name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <button type="button" onclick="toggleRecipe({{ $recipe->id }})" 
                                        class="recipe-toggle ml-2 px-3 py-1 bg-gray-100 text-gray-600 rounded hover:bg-gray-200 transition-colors">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Menu Preview -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-6 sticky top-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-clipboard-list mr-3 text-orange-500"></i>
                        Planning du menu
                    </h2>
                    <form id="menu-form" action="{{ $action }}" method="POST" onsubmit="return submitMenuForm(event);">
                        @csrf
                        @if(isset($method) && $method === 'PUT')
                            @method('PUT')
                        @endif
                        <div class="mb-4 flex justify-end">
                            <button type="button" id="add-meal-btn" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors flex items-center gap-2">
                                <i class="fas fa-plus"></i> Ajouter un repas
                            </button>
                        </div>
                        <div id="meals-list" class="space-y-6">
                            <!-- Les repas s'affichent ici dynamiquement -->
                        </div>
                        <div class="mb-4">
                            <label for="menu-name" class="block text-sm font-medium text-gray-700 mb-2">Nom du menu *</label>
                            <input type="text" id="menu-name" name="name" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                   placeholder="Ex: Menu de la semaine" value="{{ old('name', $menu->name ?? '') }}">
                        </div>
                        <div class="border-t pt-4 mt-6">
                            <button type="submit" class="w-full bg-orange-500 text-white py-3 px-4 rounded-lg hover:bg-orange-600 transition-colors">
                                <i class="fas fa-save mr-2"></i>{{ $submitLabel ?? (isset($menu) && $menu ? 'Enregistrer le menu' : 'Créer le menu') }}
                            </button>
                        </div>
                    </form>
                    <!-- Formulaire d'ajout de repas (affiché dynamiquement) -->
                    <template id="meal-form-template">
                        <div class="bg-gray-50 rounded-lg p-4 mb-4 border border-orange-200">
                            <div class="flex flex-col md:flex-row gap-4 mb-2">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jour</label>
                                    <select class="meal-day w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                                        <option value="Lundi">Lundi</option>
                                        <option value="Mardi">Mardi</option>
                                        <option value="Mercredi">Mercredi</option>
                                        <option value="Jeudi">Jeudi</option>
                                        <option value="Vendredi">Vendredi</option>
                                        <option value="Samedi">Samedi</option>
                                        <option value="Dimanche">Dimanche</option>
                                    </select>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de repas</label>
                                    <select class="meal-label w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                                        <option value="Déjeuner">Déjeuner</option>
                                        <option value="Dîner">Dîner</option>
                                        <option value="Petit-déjeuner">Petit-déjeuner</option>
                                        <option value="Goûter">Goûter</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                </div>
                                <div class="flex items-end">
                                    <button type="button" class="cancel-meal-form px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">Annuler</button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Plats</label>
                                <div class="flex flex-wrap gap-2 mb-2">
                                    <!-- Liste des plats sélectionnés -->
                                </div>
                                <select class="meal-recipe-select w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    <option value="">Ajouter un plat...</option>
                                    @foreach($recipes as $recipe)
                                        <option value="{{ $recipe->id }}">{{ $recipe->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex justify-end mt-4">
                                <button type="button" class="save-meal-btn px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">Ajouter ce repas</button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </main>
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; 2025 RecipeFlo - Tous droits réservés</p>
        </div>
    </footer>
    <script>
        const MEAL_LABEL_ORDER = {
            'Petit-déjeuner': 0,
            'Déjeuner': 1,
            'Goûter': 2,
            'Dîner': 3,
            'Autre': 4
        };
        const WEEK_DAYS_ORDER = {
            'Lundi': 1,
            'Mardi': 2,
            'Mercredi': 3,
            'Jeudi': 4,
            'Vendredi': 5,
            'Samedi': 6,
            'Dimanche': 7
        };
        let meals = @json($mealsForJs ?? []);
        window.ALL_RECIPES = [
            @foreach($recipes as $recipe)
                {id: {{ $recipe->id }}, title: @json($recipe->title)},
            @endforeach
        ];
        // ... (le reste du JS est inchangé)
    </script>
</body>
</html> 