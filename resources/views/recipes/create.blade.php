<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecipeFlo - Nouvelle Recette</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Tom Select -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <style>
    /* Forcer un fond blanc sur le dropdown Tom Select */
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
        @php
            $action = route('recettes.store');
            $method = 'POST';
            $submitLabel = 'Enregistrer la recette';
        @endphp
        @include('recipes.form', compact('categories', 'ingredients', 'action', 'method', 'submitLabel'))
    </main>
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; 2025 RecipeFlo - Tous droits réservés</p>
        </div>
    </footer>
    <script>
        let ingredientIndex = 1;
        let stepIndex = 1;

        // Liste des ingrédients pour autocomplétion
        const allIngredients = [
            @foreach($ingredients as $ingredient)
                {id: {{ $ingredient->id }}, name: "{{ addslashes($ingredient->name) }}", unit: "{{ addslashes($ingredient->unit ?? '') }}"},
            @endforeach
        ];

        function initTomSelectForRow(row, index) {
            const input = row.querySelector('.ingredient-autocomplete');
            const hiddenId = row.querySelector('.ingredient-id');
            const unitInput = row.querySelector('.ingredient-unit');
            // Détruire TomSelect si déjà instancié
            if (input.tomselect) input.tomselect.destroy();
            new TomSelect(input, {
                valueField: 'name',
                labelField: 'name',
                searchField: ['name'],
                options: allIngredients,
                create: true,
                onChange: function(value) {
                    // Quand on sélectionne, remplir l'id caché et l'unité
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
                },
                maxItems: 1,
            });
        }

        // Initialiser la première ligne
        initTomSelectForRow(document.querySelector('.ingredient-row'), 0);

        // Ajout dynamique d'une ligne d'ingrédient
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

        // Suppression d'une ligne d'ingrédient
        document.getElementById('ingredients-container').addEventListener('click', function(e) {
            if (e.target.closest('.remove-ingredient')) {
                const row = e.target.closest('.ingredient-row');
                row.remove();
            }
        });

        // Add step
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

        // Remove step
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-step')) {
                e.target.closest('.step-row').remove();
                // Renumber steps
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

        // Form submission
        document.getElementById('recipe-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            try {
                const response = await fetch('/api/recipes', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                });
                if (response.ok) {
                    window.location.href = '/recettes';
                } else {
                    const data = await response.json();
                    alert('Erreur : ' + (data.message || 'Vérifiez les champs du formulaire.'));
                }
            } catch (err) {
                alert('Erreur lors de la création de la recette.');
            }
        });
    </script>
</body>
</html> 