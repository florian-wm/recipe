<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecipeFlo - Modifier Menu</title>
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
                    @php
                        $action = route('web.menus.update', $menu->id);
                        $method = 'PUT';
                        $submitLabel = 'Enregistrer le menu';
                    @endphp
                    @include('menus.form', compact('recipes', 'menu', 'mealsForJs', 'action', 'method', 'submitLabel'))
                </div>
            </div>
        </div>
    </main>
    <!-- Footer -->
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
        let meals = @json($mealsForJs);

        window.ALL_RECIPES = [
            @foreach($recipes as $recipe)
                {id: {{ $recipe->id }}, title: @json($recipe->title)},
            @endforeach
        ];

        function renderMeals() {
            const list = document.getElementById('meals-list');
            list.innerHTML = '';
            if (meals.length === 0) {
                list.innerHTML = '<div class="text-gray-400 text-center py-8">Aucun repas planifié pour ce menu.</div>';
                return;
            }
            // Tri par jour puis par type
            meals.sort((a, b) => {
                if (a.day !== b.day) return (WEEK_DAYS_ORDER[a.day] ?? 99) - (WEEK_DAYS_ORDER[b.day] ?? 99);
                return (MEAL_LABEL_ORDER[a.label] ?? 99) - (MEAL_LABEL_ORDER[b.label] ?? 99);
            });
            meals.forEach((meal, idx) => {
                const mealDiv = document.createElement('div');
                mealDiv.className = 'bg-orange-50 border-l-4 border-orange-400 rounded-lg p-4 flex flex-col gap-2 relative';
                mealDiv.innerHTML = `
                    <div class="flex items-center justify-between mb-1">
                        <div class="font-semibold text-gray-800">
                            <span class="inline-block mr-2"><i class="fas fa-calendar-alt text-orange-400"></i></span>
                            ${meal.day} <span class="mx-2">|</span> <span class="inline-block px-2 py-1 bg-orange-200 text-orange-800 text-xs rounded">${meal.label}</span>
                        </div>
                        <div class="flex gap-1">
                            <button type="button" class="edit-meal-btn text-blue-500 hover:text-blue-700 p-2 rounded-full" title="Éditer le repas" data-idx="${idx}"><i class="fas fa-pen"></i></button>
                            <button type="button" class="delete-meal-btn text-red-500 hover:text-red-700 p-2 rounded-full" title="Supprimer le repas" data-idx="${idx}"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 items-center">
                        ${meal.recipes.length === 0 ? '<span class="text-gray-400 text-sm">Aucun plat</span>' : meal.recipes.map((r, ridx) => `
                            <span class="bg-white border border-orange-200 text-gray-800 px-3 py-1 rounded-full flex items-center gap-2 text-sm">
                                ${r.title}
                                <button type="button" class="remove-recipe-btn ml-1 text-red-400 hover:text-red-600" data-meal-idx="${idx}" data-recipe-idx="${ridx}" title="Retirer ce plat"><i class="fas fa-times"></i></button>
                            </span>
                        `).join('')}
                    </div>
                `;
                list.appendChild(mealDiv);
            });
            // Ajout listeners suppression repas/plat/édition
            document.querySelectorAll('.delete-meal-btn').forEach(btn => {
                btn.onclick = function() {
                    const idx = parseInt(this.dataset.idx);
                    meals.splice(idx, 1);
                    renderMeals();
                };
            });
            document.querySelectorAll('.remove-recipe-btn').forEach(btn => {
                btn.onclick = function() {
                    const mealIdx = parseInt(this.dataset.mealIdx);
                    const recipeIdx = parseInt(this.dataset.recipeIdx);
                    meals[mealIdx].recipes.splice(recipeIdx, 1);
                    renderMeals();
                };
            });
            document.querySelectorAll('.edit-meal-btn').forEach(btn => {
                btn.onclick = function() {
                    const idx = parseInt(this.dataset.idx);
                    openMealForm(meals[idx], idx);
                };
            });
        }

        function openMealForm(meal = null, editIdx = null) {
            if (document.getElementById('meal-form-inline')) return;
            const tmpl = document.getElementById('meal-form-template');
            const clone = tmpl.content.cloneNode(true);
            const formDiv = clone.querySelector('div');
            formDiv.id = 'meal-form-inline';
            // Pré-remplissage si édition
            let selectedRecipes = meal ? meal.recipes.map(r => ({...r})) : [];
            if (meal) {
                formDiv.querySelector('.meal-day').value = meal.day;
                formDiv.querySelector('.meal-label').value = meal.label;
            }
            // Autocomplete plats
            const platsDiv = formDiv.querySelector('.flex.flex-wrap.gap-2.mb-2') || formDiv.querySelectorAll('div')[2];
            const input = document.createElement('input');
            input.type = 'text';
            input.placeholder = 'Rechercher un plat...';
            input.className = 'autocomplete-plat w-full px-3 py-2 border border-gray-300 rounded-lg mb-2';
            platsDiv.parentNode.insertBefore(input, platsDiv.nextSibling);
            const suggestionsDiv = document.createElement('div');
            suggestionsDiv.className = 'autocomplete-suggestions absolute z-50 bg-white border border-gray-200 rounded shadow max-h-40 overflow-y-auto w-full';
            suggestionsDiv.style.display = 'none';
            platsDiv.parentNode.appendChild(suggestionsDiv);
            input.oninput = function() {
                const val = this.value.toLowerCase();
                if (!val) { suggestionsDiv.style.display = 'none'; return; }
                const filtered = window.ALL_RECIPES.filter(r => r.title.toLowerCase().includes(val) && !selectedRecipes.find(s => s.id == r.id));
                suggestionsDiv.innerHTML = filtered.map(r => `<div class='px-3 py-2 cursor-pointer hover:bg-orange-100' data-id='${r.id}'>${r.title}</div>`).join('');
                suggestionsDiv.style.display = filtered.length ? 'block' : 'none';
                suggestionsDiv.querySelectorAll('div').forEach(div => {
                    div.onclick = function() {
                        selectedRecipes.push({id: this.dataset.id, title: this.textContent});
                        updatePlats();
                        input.value = '';
                        suggestionsDiv.style.display = 'none';
                    };
                });
            };
            input.onblur = function() { setTimeout(() => suggestionsDiv.style.display = 'none', 200); };
            function updatePlats() {
                platsDiv.innerHTML = selectedRecipes.map((r, idx) => `
                    <span class="bg-white border border-orange-200 text-gray-800 px-3 py-1 rounded-full flex items-center gap-2 text-sm">
                        ${r.title}
                        <input type="number" min="1" value="${r.portion || 1}" class="portion-input w-12 px-1 py-0.5 border border-gray-300 rounded text-xs ml-2" data-idx="${idx}" title="Portions">
                        <button type="button" class="remove-plat-btn ml-1 text-red-400 hover:text-red-600" data-idx="${idx}" title="Retirer"><i class="fas fa-times"></i></button>
                    </span>
                `).join('');
                platsDiv.querySelectorAll('.remove-plat-btn').forEach(btn => {
                    btn.onclick = function() {
                        selectedRecipes.splice(parseInt(this.dataset.idx), 1);
                        updatePlats();
                    };
                });
                platsDiv.querySelectorAll('.portion-input').forEach(input => {
                    input.onchange = function() {
                        const idx = parseInt(this.dataset.idx);
                        let val = parseInt(this.value);
                        if (isNaN(val) || val < 1) val = 1;
                        selectedRecipes[idx].portion = val;
                        this.value = val;
                    };
                });
            }
            updatePlats();
            // Annuler
            formDiv.querySelector('.cancel-meal-form').onclick = function() {
                formDiv.remove();
            };
            // Sauver
            formDiv.querySelector('.save-meal-btn').onclick = function() {
                const day = formDiv.querySelector('.meal-day').value;
                const label = formDiv.querySelector('.meal-label').value;
                if (!day || !label) return alert('Jour et type de repas obligatoires');
                // Nettoyer les portions (par défaut 1)
                const recipesWithPortion = selectedRecipes.map(r => ({id: r.id, title: r.title, portion: r.portion || 1}));
                if (editIdx !== null) {
                    meals[editIdx] = {day, label, recipes: recipesWithPortion};
                } else {
                    meals.push({day, label, recipes: recipesWithPortion});
                }
                renderMeals();
                formDiv.remove();
            };
            // Ajout au DOM
            document.getElementById('meals-list').prepend(formDiv);
            input.focus();
        }

        document.getElementById('add-meal-btn').onclick = function() {
            openMealForm();
        };

        function submitMenuForm(e) {
            e.preventDefault();
            const name = document.getElementById('menu-name').value;
            if (!name) return alert('Le nom du menu est obligatoire');
            fetch('/api/menus/{{ $menu->id }}', {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ name, meals })
            })
            .then(res => res.json())
            .then(data => {
                window.location.href = "{{ route('web.menus.index') }}";
            })
            .catch(() => alert('Erreur lors de la sauvegarde'));
            return false;
        }

        // Initialisation
        renderMeals();
    </script>
</body>
</html> 