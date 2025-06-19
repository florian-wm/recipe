<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecipeFlo - @if(isset($recipe) && $recipe) Modifier @else Nouvelle @endif Recette</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Tom Select -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <style>
    .ts-dropdown, .tom-select .ts-dropdown {
        background: #fff !important;
        z-index: 9999;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    }
    .ts-dropdown .active, .tom-select .ts-dropdown .active {
        background: #f3f4f6 !important;
    }
    </style>
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
        <form id="recipe-form" class="max-w-4xl mx-auto space-y-8" enctype="multipart/form-data" method="POST" action="{{ $action }}">
            @csrf
            @if(isset($method) && $method === 'PUT')
                @method('PUT')
            @endif
            <!-- Basic Information -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-info-circle mr-3 text-orange-500"></i>
                    Informations de base
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Titre de la recette *</label>
                        <input type="text" id="title" name="title" required value="{{ old('title', $recipe->title ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                               placeholder="Ex: Crêpes classiques">
                    </div>
                    @if(isset($recipe) && $recipe && $recipe->image)
                        <img src="{{ asset('storage/' . $recipe->image) }}" alt="Image de la recette" class="mb-2 w-32 h-32 object-cover rounded">
                    @endif
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Image (URL)</label>
                        <input type="file" id="image" name="image" accept="image/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                               placeholder="https://example.com/image.jpg">
                    </div>
                    <div>
                        <label for="portion" class="block text-sm font-medium text-gray-700 mb-2">Nombre de personnes *</label>
                        <input type="number" id="portion" name="portion" min="1" required value="{{ old('portion', $recipe->portion ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                               placeholder="4">
                    </div>
                    <div>
                        <label for="age" class="block text-sm font-medium text-gray-700 mb-2">Âge minimum *</label>
                        <input type="number" id="age" name="age" min="0" required value="{{ old('age', $recipe->age ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                               placeholder="3">
                    </div>
                </div>
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea id="description" name="description" rows="4" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                              placeholder="Décrivez votre recette...">{{ old('description', $recipe->description ?? '') }}</textarea>
                </div>
            </div>
            <!-- Categories -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-tags mr-3 text-orange-500"></i>
                    Catégories
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach($categories as $category)
                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                        <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" 
                               {{ (isset($recipe) && $recipe && $recipe->categories->contains($category->id)) || (is_array(old('category_ids')) && in_array($category->id, old('category_ids', []))) ? 'checked' : '' }} class="mr-3">
                        <span class="text-sm font-medium" style="color: {{ $category->color }}">{{ $category->name }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <!-- Ingredients -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-list-ul mr-3 text-orange-500"></i>
                    Ingrédients *
                </h2>
                <div id="ingredients-container" class="space-y-4">
                    @php
                        $oldIngredients = old('ingredients', isset($recipe) && $recipe ? $recipe->ingredients->map(function($i) { return [
                            'id' => $i->id,
                            'name' => $i->name,
                            'quantity' => $i->pivot->quantity,
                            'unit' => $i->unit,
                        ]; })->toArray() : []);
                    @endphp
                    @foreach($oldIngredients as $index => $ingredient)
                    <div class="ingredient-row flex gap-4 items-end">
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ingrédient</label>
                            <input type="text" name="ingredients[{{ $index }}][name]" class="ingredient-autocomplete w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="Commencez à taper..." autocomplete="off" value="{{ $ingredient['name'] ?? '' }}" required>
                            <input type="hidden" name="ingredients[{{ $index }}][id]" class="ingredient-id" value="{{ $ingredient['id'] ?? '' }}">
                        </div>
                        <div class="w-32">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quantité</label>
                            <input type="number" name="ingredients[{{ $index }}][quantity]" step="0.1" min="0" required value="{{ $ingredient['quantity'] ?? '' }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                   placeholder="250">
                        </div>
                        <div class="w-32">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Unité</label>
                            <input type="text" name="ingredients[{{ $index }}][unit]" class="ingredient-unit w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="g, ml, ..." value="{{ $ingredient['unit'] ?? '' }}">
                        </div>
                        <button type="button" class="remove-ingredient px-3 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
                <button type="button" id="add-ingredient" class="mt-4 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Ajouter un ingrédient
                </button>
            </div>
            <!-- Steps -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-tasks mr-3 text-orange-500"></i>
                    Étapes de préparation *
                </h2>
                <div id="steps-container" class="space-y-4">
                    @php
                        $oldSteps = old('steps', isset($recipe) && $recipe ? $recipe->steps->map(function($s) { return [
                            'description' => $s->description,
                            'order' => $s->order,
                        ]; })->toArray() : []);
                    @endphp
                    @foreach($oldSteps as $index => $step)
                    <div class="step-row flex gap-4 items-start">
                        <div class="flex-shrink-0 w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold mt-3">
                            {{ $step['order'] ?? ($index+1) }}
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Étape {{ $step['order'] ?? ($index+1) }}</label>
                            <textarea name="steps[{{ $index }}][description]" rows="3" required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                                      placeholder="Décrivez cette étape...">{{ $step['description'] ?? '' }}</textarea>
                            <input type="hidden" name="steps[{{ $index }}][order]" value="{{ $step['order'] ?? ($index+1) }}">
                        </div>
                        <button type="button" class="remove-step px-3 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors mt-3">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
                <button type="button" id="add-step" class="mt-4 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Ajouter une étape
                </button>
            </div>
            <!-- Submit Buttons -->
            <div class="flex gap-4 justify-end">
                <a href="{{ route('recettes.index') }}" class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    <i class="fas fa-times mr-2"></i>Annuler
                </a>
                <button type="submit" class="px-6 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                    <i class="fas fa-save mr-2"></i>{{ $submitLabel ?? (isset($recipe) && $recipe ? 'Enregistrer les modifications' : 'Enregistrer la recette') }}
                </button>
            </div>
        </form>
    </main>
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; 2025 RecipeFlo - Tous droits réservés</p>
        </div>
    </footer>
    <script>
    let ingredientIndex = {{ count($oldIngredients) }};
    let stepIndex = {{ count($oldSteps) }};
    const allIngredients = [
        @foreach($ingredients as $ingredient)
            {id: {{ $ingredient->id }}, name: "{{ addslashes($ingredient->name) }}", unit: "{{ addslashes($ingredient->unit ?? '') }}"},
        @endforeach
    ];
    function initTomSelectForRow(row, index) {
        const input = row.querySelector('.ingredient-autocomplete');
        const hiddenId = row.querySelector('.ingredient-id');
        const unitInput = row.querySelector('.ingredient-unit');
        if (input.tomselect) input.tomselect.destroy();
        new TomSelect(input, {
            valueField: 'name',
            labelField: 'name',
            searchField: ['name'],
            options: allIngredients,
            create: true,
            maxItems: 1,
            onChange: function(value) {
                const found = allIngredients.find(i => i.name === value);
                if (found) {
                    hiddenId.value = found.id;
                    unitInput.value = found.unit || '';
                } else {
                    hiddenId.value = '';
                }
            },
            render: {
                option: function(data, escape) {
                    return `<div>${escape(data.name)}${data.unit ? ' <span class=\"text-xs text-gray-400\">(' + escape(data.unit) + ')</span>' : ''}</div>`;
                }
            }
        });
    }
    document.querySelectorAll('.ingredient-row').forEach((row, idx) => {
        initTomSelectForRow(row, idx);
    });
    document.getElementById('add-ingredient').addEventListener('click', function() {
        const container = document.getElementById('ingredients-container');
        const newRow = document.createElement('div');
        newRow.className = 'ingredient-row flex gap-4 items-end';
        newRow.innerHTML = `
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Ingrédient</label>
                <input type="text" name="ingredients[${ingredientIndex}][name]" class="ingredient-autocomplete w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="Commencez à taper..." autocomplete="off" required>
                <input type="hidden" name="ingredients[${ingredientIndex}][id]" class="ingredient-id">
            </div>
            <div class="w-32">
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantité</label>
                <input type="number" name="ingredients[${ingredientIndex}][quantity]" step="0.1" min="0" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                       placeholder="250">
            </div>
            <div class="w-32">
                <label class="block text-sm font-medium text-gray-700 mb-2">Unité</label>
                <input type="text" name="ingredients[${ingredientIndex}][unit]" class="ingredient-unit w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent" placeholder="g, ml, ...">
            </div>
            <button type="button" class="remove-ingredient px-3 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(newRow);
        initTomSelectForRow(newRow, ingredientIndex);
        ingredientIndex++;
    });
    document.getElementById('ingredients-container').addEventListener('click', function(e) {
        if (e.target.closest('.remove-ingredient')) {
            const row = e.target.closest('.ingredient-row');
            row.remove();
        }
    });
    document.getElementById('add-step').addEventListener('click', function() {
        const container = document.getElementById('steps-container');
        const newRow = document.createElement('div');
        newRow.className = 'step-row flex gap-4 items-start';
        newRow.innerHTML = `
            <div class="flex-shrink-0 w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold mt-3">
                ${stepIndex + 1}
            </div>
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Étape ${stepIndex + 1}</label>
                <textarea name="steps[${stepIndex}][description]" rows="3" required
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                          placeholder="Décrivez cette étape..."></textarea>
                <input type="hidden" name="steps[${stepIndex}][order]" value="${stepIndex + 1}">
            </div>
            <button type="button" class="remove-step px-3 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors mt-3">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.appendChild(newRow);
        stepIndex++;
    });
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-step')) {
            e.target.closest('.step-row').remove();
            document.querySelectorAll('.step-row').forEach((row, index) => {
                const number = row.querySelector('.w-8');
                const label = row.querySelector('label');
                const orderInput = row.querySelector('input[type="hidden"]');
                number.textContent = index + 1;
                label.textContent = `Étape ${index + 1}`;
                orderInput.value = index + 1;
            });
            stepIndex = document.querySelectorAll('.step-row').length;
        }
    });
    </script>
</body>
</html> 