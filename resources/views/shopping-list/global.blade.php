<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecipeFlo - Liste de Courses</title>
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
                <span class="hidden md:inline-block text-gray-500 text-sm mr-2"><i class="fas fa-shopping-cart mr-1"></i>{{ $uncheckedItems->count() }} à acheter</span>
                <a href="{{ route('web.menus.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-clipboard-list mr-2"></i>Menus
                </a>
                <a href="{{ route('recettes.index') }}" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors flex items-center gap-2">
                    <i class="fas fa-utensils"></i> Recettes
                </a>
                <button onclick="printList()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-print mr-2"></i>Imprimer
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-1">Ma Liste de Courses</h2>
            <p class="text-gray-600">Gérez vos achats de manière persistante</p>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Shopping List -->
            <div class="lg:col-span-2">
                <!-- Add Custom Item -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-plus-circle mr-3 text-green-500"></i>
                        Ajouter un article
                    </h3>
                    <form id="add-item-form" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Article *</label>
                                <input type="text" id="item-name" name="name" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="Ex: Lessive, Pain, etc.">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantité</label>
                                <input type="text" id="item-quantity" name="quantity"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="1">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Unité</label>
                                <input type="text" id="item-unit" name="unit"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="kg, l, etc.">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes (optionnel)</label>
                            <textarea id="item-notes" name="notes" rows="2"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                      placeholder="Ex: Marque préférée, taille spécifique..."></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                <i class="fas fa-plus mr-2"></i>Ajouter à la liste
                            </button>
                        </div>
                    </form>
                    <!-- Ajout ingrédients d'une recette -->
                    <form id="add-recipe-to-list-form" class="mt-6 flex flex-col md:flex-row gap-4 items-end">
                        @csrf
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ajouter les ingrédients d'une recette</label>
                            <select id="recipe-select" name="recipe_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Choisir une recette…</option>
                                @foreach($recipes as $recipe)
                                    <option value="{{ $recipe->id }}" data-portion="{{ $recipe->portion ?? 1 }}">{{ $recipe->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Portions</label>
                            <input type="number" id="recipe-portion" name="portion" min="0.1" step="0.1" value="1" class="w-24 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <button type="submit" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Ajouter les ingrédients
                        </button>
                    </form>
                </div>
                <!-- Unchecked Items -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="fas fa-shopping-cart mr-3 text-orange-500"></i>
                            À acheter ({{ $uncheckedItems->count() }})
                        </h3>
                        <div class="flex gap-2">
                            <button onclick="uncheckAll()" class="px-3 py-1 bg-yellow-500 text-white rounded text-sm hover:bg-yellow-600 transition-colors">
                                <i class="fas fa-undo mr-1"></i>Tout remettre
                            </button>
                            <button onclick="clearChecked()" class="px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600 transition-colors">
                                <i class="fas fa-trash mr-1"></i>Nettoyer
                            </button>
                            <button onclick="deleteAllItems()" class="px-3 py-1 bg-gray-800 text-white rounded text-sm hover:bg-black transition-colors">
                                <i class="fas fa-trash-alt mr-1"></i>Tout supprimer
                            </button>
                        </div>
                    </div>
                    <div id="unchecked-items-container">
                        @if($uncheckedItems->count() > 0)
                            <div class="space-y-3">
                                @foreach($uncheckedItems as $item)
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors" data-item-id="{{ $item->id }}">
                                    <div class="flex items-center">
                                        <input type="checkbox" class="item-checkbox mr-4 w-5 h-5 text-green-500 rounded focus:ring-green-500" 
                                               data-item-id="{{ $item->id }}" onchange="toggleItem({{ $item->id }}, this.checked)">
                                        <div>
                                            <h4 class="font-medium text-gray-800">{{ $item->name }}</h4>
                                            <div class="flex items-center text-sm text-gray-600 mt-1">
                                                <span class="mr-2">{{ $item->quantity }} {{ $item->unit }}</span>
                                                @if($item->source)
                                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">{{ $item->source }}</span>
                                                @endif
                                            </div>
                                            @if($item->notes)
                                            <p class="text-sm text-gray-500 mt-1">{{ $item->notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button onclick="deleteItem({{ $item->id }})" class="px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition-colors text-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-4"></i>
                                <h4 class="text-lg font-semibold text-gray-600 mb-2">Liste vide</h4>
                                <p class="text-gray-500">Ajoutez des articles à votre liste de courses</p>
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Recently Checked Items -->
                @if($checkedItems->count() > 0)
                <div class="bg-white rounded-xl shadow-md p-6 mt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-check-circle mr-3 text-green-500"></i>
                        Récemment achetés ({{ $checkedItems->count() }})
                    </h3>
                    <div class="space-y-2">
                        @foreach($checkedItems as $item)
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <div>
                                    <h4 class="font-medium text-gray-800 line-through">{{ $item->name }}</h4>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <span class="mr-2">{{ $item->quantity }} {{ $item->unit }}</span>
                                        <span class="text-xs text-gray-500">{{ $item->updated_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            <button onclick="uncheckItem({{ $item->id }})" class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition-colors text-sm">
                                <i class="fas fa-undo"></i>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            <!-- Quick Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-6 sticky top-8">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-bolt mr-3 text-orange-500"></i>
                        Actions rapides
                    </h3>
                    <div class="space-y-4">
                        <a href="{{ route('web.menus.create') }}" class="block w-full p-4 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors text-center">
                            <i class="fas fa-plus mr-2"></i>Créer un menu
                        </a>
                        <a href="{{ route('web.menus.index') }}" class="block w-full p-4 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-center">
                            <i class="fas fa-clipboard-list mr-2"></i>Mes menus
                        </a>
                        <a href="{{ route('recettes.index') }}" class="block w-full p-4 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors text-center">
                            <i class="fas fa-utensils mr-2"></i>Voir les recettes
                        </a>
                    </div>
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
        // Add custom item
        document.getElementById('add-item-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {
                name: formData.get('name'),
                quantity: formData.get('quantity') || '1',
                unit: formData.get('unit') || null,
                notes: formData.get('notes') || null
            };
            
            fetch('/api/shopping-list/custom-item', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.item) {
                    location.reload(); // Reload to show new item
                } else {
                    alert('Erreur lors de l\'ajout de l\'article');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de l\'ajout de l\'article');
            });
        });

        // Toggle item status
        function toggleItem(itemId, isChecked) {
            fetch(`/api/shopping-list/${itemId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ is_checked: isChecked })
            })
            .then(response => response.json())
            .then(data => {
                if (isChecked) {
                    // Move item to checked section
                    const itemElement = document.querySelector(`[data-item-id="${itemId}"]`);
                    if (itemElement) {
                        itemElement.style.opacity = '0.5';
                        setTimeout(() => location.reload(), 500);
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la mise à jour');
            });
        }

        // Uncheck item (move back to unchecked)
        function uncheckItem(itemId) {
            fetch(`/api/shopping-list/${itemId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ is_checked: false })
            })
            .then(response => response.json())
            .then(data => {
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la mise à jour');
            });
        }

        // Delete item
        function deleteItem(itemId) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) {
                return;
            }
            
            fetch(`/api/shopping-list/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                const itemElement = document.querySelector(`[data-item-id="${itemId}"]`);
                if (itemElement) {
                    itemElement.remove();
                    updateItemCount();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la suppression');
            });
        }

        // Clear checked items
        function clearChecked() {
            if (!confirm('Êtes-vous sûr de vouloir supprimer tous les articles cochés ?')) {
                return;
            }
            
            fetch('/api/shopping-list/clear-checked', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors du nettoyage');
            });
        }

        // Uncheck all items
        function uncheckAll() {
            if (!confirm('Remettre tous les articles cochés dans la liste ?')) {
                return;
            }
            
            fetch('/api/shopping-list/uncheck-all', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la remise en liste');
            });
        }

        // Print list
        function printList() {
            const uncheckedItems = document.querySelectorAll('#unchecked-items-container .item-checkbox:not(:checked)');
            let printContent = '<h2>Liste de Courses - RecipeFlo</h2><ul>';
            
            uncheckedItems.forEach(checkbox => {
                const itemElement = checkbox.closest('[data-item-id]');
                const name = itemElement.querySelector('h4').textContent;
                const quantity = itemElement.querySelector('.text-sm span').textContent;
                printContent += `<li>☐ ${name} - ${quantity}</li>`;
            });
            
            printContent += '</ul>';
            
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Liste de Courses</title>
                        <style>
                            body { font-family: Arial, sans-serif; padding: 20px; }
                            h2 { color: #333; }
                            ul { list-style: none; padding: 0; }
                            li { padding: 8px 0; border-bottom: 1px solid #eee; }
                        </style>
                    </head>
                    <body>${printContent}</body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }

        // Update item count
        function updateItemCount() {
            const uncheckedCount = document.querySelectorAll('#unchecked-items-container .item-checkbox').length;
            const countElement = document.querySelector('.text-2xl.font-semibold');
            if (countElement) {
                countElement.textContent = uncheckedCount;
            }
        }

        // Add enter key support for form
        document.getElementById('item-name').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('add-item-form').dispatchEvent(new Event('submit'));
            }
        });

        // Supprimer tous les items (cochés ou non)
        function deleteAllItems() {
            if (!confirm('Êtes-vous sûr de vouloir supprimer toute la liste ?')) {
                return;
            }
            fetch('/api/shopping-list/all', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la suppression totale');
            });
        }

        // Ajout ingrédients d'une recette à la liste
        document.getElementById('add-recipe-to-list-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const recipeId = document.getElementById('recipe-select').value;
            const portion = document.getElementById('recipe-portion').value;
            if (!recipeId) {
                alert('Veuillez choisir une recette.');
                return;
            }
            fetch('/api/shopping-list/add-recipe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ recipe_id: recipeId, portion: portion })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Erreur lors de l\'ajout des ingrédients');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de l\'ajout des ingrédients');
            });
        });

        // Pré-remplir la portion par défaut de la recette sélectionnée
        document.getElementById('recipe-select').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const portion = selected.getAttribute('data-portion') || 1;
            document.getElementById('recipe-portion').value = portion;
        });
    </script>
</body>
</html> 