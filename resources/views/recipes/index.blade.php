<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecipeFlo - Nos Recettes</title>
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
                <span class="hidden md:inline-block text-gray-500 text-sm mr-2"><i class="fas fa-utensils mr-1"></i>{{ $recipes->count() }} recettes</span>
                <!-- Menu déroulant actions (desktop) -->
                <div class="relative hidden md:block">
                    <button id="actions-menu-btn-header" class="p-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 transition" title="Actions" type="button">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div id="actions-menu-header" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 z-30">
                        <button id="filter-favorites-header" class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center gap-2 text-yellow-600">
                            <i class="fas fa-star"></i> Favoris
                        </button>
                        <button id="export-all-header" class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center gap-2 text-blue-600">
                            <i class="fas fa-file-export"></i> Exporter tout
                        </button>
                        <button id="import-btn-header" class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center gap-2 text-green-600">
                            <i class="fas fa-file-import"></i> Importer
                        </button>
                        <a href="{{ route('categories.index') }}" class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center gap-2 text-yellow-700">
                            <i class="fas fa-tags"></i> Catégories
                        </a>
                        <input type="file" id="import-file-header" accept="application/json" class="hidden">
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-8">
        <!-- Search and Filters -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="relative flex-1 max-w-md flex items-center gap-2">
                    <input type="text" id="search" placeholder="Rechercher une recette..." 
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <!-- Menu déroulant actions (mobile/tablette) -->
                    <div class="relative ml-2 md:hidden">
                        <button id="actions-menu-btn" class="p-2 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-600 transition" title="Actions" type="button">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div id="actions-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 z-30">
                            <button id="filter-favorites" class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center gap-2 text-yellow-600">
                                <i class="fas fa-star"></i> Favoris
                            </button>
                            <button id="export-all" class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center gap-2 text-blue-600">
                                <i class="fas fa-file-export"></i> Exporter tout
                            </button>
                            <button id="import-btn" class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center gap-2 text-green-600">
                                <i class="fas fa-file-import"></i> Importer
                            </button>
                            <a href="{{ route('categories.index') }}" class="w-full text-left px-4 py-3 hover:bg-gray-50 flex items-center gap-2 text-yellow-700">
                                <i class="fas fa-tags"></i> Catégories
                            </a>
                            <input type="file" id="import-file" accept="application/json" class="hidden">
                        </div>
                    </div>
                </div>
                <div class="flex gap-2 items-center">
                    <a href="{{ route('web.shopping-list.global') }}" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                        <i class="fas fa-shopping-cart mr-2"></i>Liste de Courses
                    </a>
                    <a href="{{ route('web.menus.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        <i class="fas fa-clipboard-list mr-2"></i>Mes Menus
                    </a>
                    <a href="{{ route('recettes.create') }}" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors flex items-center gap-2">
                        <i class="fas fa-plus"></i> Nouvelle recette
                    </a>
                </div>
            </div>
        </div>

        <!-- Categories Filter -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Filtrer par catégorie :</h3>
            <div class="flex flex-wrap gap-3">
                <button class="category-filter px-4 py-2 bg-orange-500 text-white rounded-full hover:bg-orange-600 transition-colors" data-category="all">
                    Toutes ({{ $recipes->count() }})
                </button>
                @foreach($categories as $category)
                <button class="category-filter px-4 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 transition-colors" 
                        data-category="{{ $category->id }}" 
                        style="border: 2px solid {{ $category->color }}">
                    {{ $category->name }} ({{ $category->recipes_count }})
                </button>
                @endforeach
            </div>
        </div>

        <!-- Recipes Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($recipes as $recipe)
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden recipe-card" 
                 data-categories="{{ $recipe->categories->pluck('id')->implode(',') }}" data-favorite="{{ $recipe->is_favorite ? '1' : '0' }}" data-id="{{ $recipe->id }}">
                <!-- Recipe Image -->
                <div class="h-48 bg-gradient-to-br from-orange-100 to-red-100 flex items-center justify-center relative">
                    @if($recipe->image)
                        @if(Str::startsWith($recipe->image, 'http'))
                            <img src="{{ $recipe->image }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('storage/' . $recipe->image) }}" alt="{{ $recipe->title }}" class="w-full h-full object-cover">
                        @endif
                    @else
                        <div class="text-center text-gray-500">
                            <i class="fas fa-utensils text-4xl mb-2"></i>
                            <p class="text-sm">Aucune image</p>
                        </div>
                    @endif
                    <!-- Bouton Favori -->
                    <button class="absolute top-2 right-2 favorite-btn" title="Ajouter aux favoris">
                        <i class="fas fa-star text-2xl transition" style="color: {{ $recipe->is_favorite ? '#FFD700' : '#E5E7EB' }};"></i>
                    </button>
                    <!-- Bouton Export -->
                    <button class="absolute top-2 left-2 export-btn bg-white bg-opacity-80 rounded-full p-2 shadow hover:bg-orange-100 transition" title="Exporter cette recette">
                        <i class="fas fa-file-export text-orange-500"></i>
                    </button>
                    <!-- Categories Badges -->
                    <div class="absolute top-2 left-2 flex flex-wrap gap-1">
                        @foreach($recipe->categories->take(2) as $category)
                        <span class="px-2 py-1 text-xs rounded-full text-white font-medium" 
                              style="background-color: {{ $category->color }}">
                            {{ $category->name }}
                        </span>
                        @endforeach
                        @if($recipe->categories->count() > 2)
                        <span class="px-2 py-1 bg-gray-800 text-white text-xs rounded-full">
                            +{{ $recipe->categories->count() - 2 }}
                        </span>
                        @endif
                    </div>
                </div>

                <!-- Recipe Content -->
                <div class="p-6">
                    <div class="flex items-start justify-between mb-3">
                        <h3 class="text-xl font-semibold text-gray-800 recipe-title">{{ $recipe->title }}</h3>
                        <div class="flex items-center text-orange-500">
                            <i class="fas fa-star text-sm"></i>
                        </div>
                    </div>

                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $recipe->description }}</p>

                    <!-- Recipe Stats -->
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-users mr-1"></i>
                            <span>{{ $recipe->portion }} pers.</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-1"></i>
                            <span>{{ $recipe->steps->count() }} étapes</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-child mr-1"></i>
                            <span>{{ $recipe->age }}+ ans</span>
                        </div>
                    </div>

                    <!-- Ingredients Preview -->
                    <div class="mb-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Ingrédients principaux :</h4>
                        <div class="flex flex-wrap gap-1">
                            @foreach($recipe->ingredients->take(3) as $ingredient)
                                <span class="px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full">
                                    {{ $ingredient->name }}
                                </span>
                            @endforeach
                            @if($recipe->ingredients->count() > 3)
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">
                                    +{{ $recipe->ingredients->count() - 3 }} autres
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <a href="{{ route('recettes.show', $recipe->id) }}" 
                           class="flex-1 bg-orange-500 text-white text-center py-2 px-4 rounded-lg hover:bg-orange-600 transition-colors">
                            <i class="fas fa-eye mr-2"></i>Voir
                        </a>
                        <a href="{{ route('recettes.edit', $recipe->id) }}" 
                           class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                            <i class="fas fa-edit"></i>
                        </a>
                        <div class="relative">
                            <button onclick="toggleQuickActions({{ $recipe->id }})" 
                                    class="px-3 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors">
                                <i class="fas fa-plus"></i>
                            </button>
                            <!-- Quick Actions Dropdown -->
                            <div id="quick-actions-{{ $recipe->id }}" 
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10 hidden">
                                <div class="py-1">
                                    <button onclick="addToMenu({{ $recipe->id }}, '{{ $recipe->title }}')" 
                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <i class="fas fa-clipboard-list mr-2 text-blue-500"></i>
                                        Ajouter à un menu
                                    </button>
                                    <button onclick="generateShoppingList([{{ $recipe->id }}])" 
                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <i class="fas fa-shopping-cart mr-2 text-green-500"></i>
                                        Liste de courses
                                    </button>
                                    <button onclick="addToFavorites({{ $recipe->id }})" 
                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                                        <i class="fas fa-heart mr-2 text-red-500"></i>
                                        Ajouter aux favoris
                                    </button>
                                </div>
                            </div>
                        </div>
                        @auth
                        <form class="delete-recipe-form" data-recipe-id="{{ $recipe->id }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Empty State -->
        @if($recipes->isEmpty())
        <div class="text-center py-12">
            <i class="fas fa-utensils text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Aucune recette trouvée</h3>
            <p class="text-gray-500">Commencez par ajouter votre première recette !</p>
        </div>
        @endif
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; 2025 RecipeFlo - Tous droits réservés</p>
        </div>
    </footer>

    <script>
        // Search functionality
        document.getElementById('search').addEventListener('input', function(e) {
            filterRecipes();
        });

        // Category filter functionality
        document.querySelectorAll('.category-filter').forEach(button => {
            button.addEventListener('click', function() {
                // Update active state
                document.querySelectorAll('.category-filter').forEach(btn => {
                    btn.classList.remove('bg-orange-500', 'text-white');
                    btn.classList.add('bg-gray-200', 'text-gray-700');
                });
                this.classList.remove('bg-gray-200', 'text-gray-700');
                this.classList.add('bg-orange-500', 'text-white');
                
                filterRecipes();
            });
        });

        function normalize(str) {
            return str.normalize('NFD').replace(/\p{Diacritic}/gu, '').toLowerCase();
        }

        function filterRecipes() {
            const searchTerm = normalize(document.getElementById('search').value);
            const activeCategory = document.querySelector('.category-filter.bg-orange-500').dataset.category;
            const recipeCards = document.querySelectorAll('.recipe-card');
            
            recipeCards.forEach(card => {
                const title = normalize(card.querySelector('.recipe-title').textContent);
                const description = normalize(card.querySelector('p').textContent);
                const categories = card.dataset.categories.split(',').map(id => id.trim());
                
                const matchesSearch = title.includes(searchTerm) || description.includes(searchTerm);
                const matchesCategory = activeCategory === 'all' || categories.includes(activeCategory);
                
                if (matchesSearch && matchesCategory) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Quick Actions Functions
        function toggleQuickActions(recipeId) {
            // Close all other dropdowns
            document.querySelectorAll('[id^="quick-actions-"]').forEach(dropdown => {
                if (dropdown.id !== `quick-actions-${recipeId}`) {
                    dropdown.classList.add('hidden');
                }
            });
            
            // Toggle current dropdown
            const dropdown = document.getElementById(`quick-actions-${recipeId}`);
            dropdown.classList.toggle('hidden');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.relative')) {
                document.querySelectorAll('[id^="quick-actions-"]').forEach(dropdown => {
                    dropdown.classList.add('hidden');
                });
            }
        });

        function addToMenu(recipeId, recipeTitle) {
            // Redirect to menu creation page with pre-selected recipe
            window.location.href = `/web/menus/create?recipe=${recipeId}`;
        }

        function generateShoppingList(recipeIds) {
            // Create form data
            const formData = new FormData();
            formData.append('recipe_ids', JSON.stringify(recipeIds));
            formData.append('_token', '{{ csrf_token() }}');
            
            // Submit to shopping list endpoint
            fetch('/web/shopping-list/recipes', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.text())
            .then(html => {
                // Open in new window
                const newWindow = window.open('', '_blank');
                newWindow.document.write(html);
                newWindow.document.close();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la génération de la liste de courses');
            });
        }

        function addToFavorites(recipeId) {
            // For now, just show a message (favorites feature not implemented yet)
            alert('Fonctionnalité favoris à venir !');
            
            // TODO: Implement favorites functionality
            // This could involve:
            // - Adding a favorites table
            // - Creating a favorites controller
            // - Storing user preferences
        }

        document.querySelectorAll('.delete-recipe-form').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                if (!confirm('Supprimer cette recette ?')) return;
                const recipeId = this.dataset.recipeId;
                try {
                    const response = await fetch(`/api/recipes/${recipeId}`, {
                        method: 'DELETE',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': this.querySelector('input[name="_token"]').value
                        }
                    });
                    if (response.ok) {
                        this.closest('.recipe-card').remove();
                    } else {
                        alert('Erreur lors de la suppression.');
                    }
                } catch (err) {
                    alert('Erreur lors de la suppression.');
                }
            });
        });

        // Gestion favoris
        function updateFavoriteUI(card, isFav) {
            const star = card.querySelector('.favorite-btn i');
            star.style.color = isFav ? '#FFD700' : '#E5E7EB';
            card.dataset.favorite = isFav ? '1' : '0';
        }
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            btn.onclick = async function(e) {
                e.preventDefault();
                const card = btn.closest('.recipe-card');
                const id = card.dataset.id;
                const isFav = card.dataset.favorite === '1';
                const res = await fetch(`/api/recipes/${id}/favorite`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value
                    },
                    body: JSON.stringify({ is_favorite: !isFav })
                });
                if (res.ok) {
                    updateFavoriteUI(card, !isFav);
                }
            };
        });
        // Filtre favoris
        const favFilterBtn = document.getElementById('filter-favorites');
        favFilterBtn.onclick = function() {
            const showFavs = !favFilterBtn.classList.toggle('bg-yellow-500');
            document.querySelectorAll('.recipe-card').forEach(card => {
                if (showFavs && card.dataset.favorite !== '1') {
                    card.style.display = 'none';
                } else {
                    card.style.display = '';
                }
            });
        };

        // Export tout
        document.getElementById('export-all').onclick = function() {
            window.location = '/api/recipes/export';
        };
        // Export une recette
        document.querySelectorAll('.export-btn').forEach(btn => {
            btn.onclick = function(e) {
                e.preventDefault();
                const card = btn.closest('.recipe-card');
                const id = card.dataset.id;
                window.location = `/api/recipes/export?ids[]=${id}`;
            };
        });
        // Import
        document.getElementById('import-btn').onclick = function() {
            document.getElementById('import-file').click();
        };
        document.getElementById('import-file').onchange = async function() {
            const file = this.files[0];
            if (!file) return;
            const formData = new FormData();
            formData.append('file', file);
            const res = await fetch('/api/recipes/import', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || document.querySelector('input[name="_token"]')?.value
                },
                body: formData
            });
            if (res.ok) {
                alert('Import terminé !');
                window.location.reload();
            } else {
                alert('Erreur lors de l\'import.');
            }
        };

        // Menu déroulant actions (mobile/tablette)
        const menuBtn = document.getElementById('actions-menu-btn');
        const menu = document.getElementById('actions-menu');
        if(menuBtn && menu) {
            menuBtn.onclick = function(e) {
                e.stopPropagation();
                menu.classList.toggle('hidden');
            };
            document.addEventListener('click', function(e) {
                if (!menu.contains(e.target) && e.target !== menuBtn) {
                    menu.classList.add('hidden');
                }
            });
        }
        // Menu déroulant actions (desktop)
        const menuBtnHeader = document.getElementById('actions-menu-btn-header');
        const menuHeader = document.getElementById('actions-menu-header');
        if(menuBtnHeader && menuHeader) {
            menuBtnHeader.onclick = function(e) {
                e.stopPropagation();
                menuHeader.classList.toggle('hidden');
            };
            document.addEventListener('click', function(e) {
                if (!menuHeader.contains(e.target) && e.target !== menuBtnHeader) {
                    menuHeader.classList.add('hidden');
                }
            });
        }
    </script>
</body>
</html> 