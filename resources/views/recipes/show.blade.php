<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecipeFlo - Détail Recette</title>
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

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recipe Image and Info -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md overflow-hidden sticky top-8">
                    <!-- Recipe Image -->
                    <div class="h-64 bg-gradient-to-br from-orange-100 to-red-100 flex items-center justify-center relative">
                        @if($recipe->image)
                            <img src="{{ asset('storage/' . $recipe->image) }}" alt="Image de la recette" class="mb-4 w-full max-w-md mx-auto rounded shadow">
                        @else
                            <div class="text-center text-gray-500">
                                <i class="fas fa-utensils text-6xl mb-4"></i>
                                <p class="text-lg">Aucune image</p>
                            </div>
                        @endif
                        
                        <!-- Categories Badges -->
                        @if($recipe->categories->count() > 0)
                        <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                            @foreach($recipe->categories as $category)
                            <span class="px-3 py-1 text-sm rounded-full text-white font-medium shadow-lg" 
                                  style="background-color: {{ $category->color }}">
                                {{ $category->name }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <!-- Recipe Info -->
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ $recipe->title }}</h2>
                        
                        <p class="text-gray-600 mb-6">{{ $recipe->description }}</p>

                        <!-- Recipe Stats -->
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-orange-500">{{ $recipe->portion }}</div>
                                <div class="text-sm text-gray-500">Personnes</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-orange-500">{{ $recipe->steps->count() }}</div>
                                <div class="text-sm text-gray-500">Étapes</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-orange-500">{{ $recipe->age }}+</div>
                                <div class="text-sm text-gray-500">Âge</div>
                            </div>
                        </div>

                        <!-- Categories Section -->
                        @if($recipe->categories->count() > 0)
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-3">Catégories :</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach($recipe->categories as $category)
                                <span class="px-3 py-1 text-sm rounded-full text-white font-medium" 
                                      style="background-color: {{ $category->color }}">
                                    {{ $category->name }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <button class="w-full bg-orange-500 text-white py-3 px-4 rounded-lg hover:bg-orange-600 transition-colors">
                                <i class="fas fa-plus mr-2"></i>Ajouter au menu
                            </button>
                            <a href="{{ route('recettes.edit', $recipe->id) }}" 
                               class="w-full bg-blue-500 text-white py-3 px-4 rounded-lg hover:bg-blue-600 transition-colors block text-center">
                                <i class="fas fa-edit mr-2"></i>Modifier la recette
                            </a>
                            <button onclick="generateShoppingList()" class="w-full bg-green-500 text-white py-3 px-4 rounded-lg hover:bg-green-600 transition-colors">
                                <i class="fas fa-shopping-cart mr-2"></i>Liste de courses
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recipe Details -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Ingredients -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-list-ul mr-3 text-orange-500"></i>
                        Ingrédients
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($recipe->ingredients as $ingredient)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-orange-500 rounded-full mr-3"></div>
                                <span class="font-medium text-gray-800">{{ $ingredient->name }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold text-orange-600">{{ $ingredient->pivot->quantity }}</span>
                                @if($ingredient->unit)
                                    <span class="text-gray-500 ml-1">{{ $ingredient->unit }}</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Steps -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-tasks mr-3 text-orange-500"></i>
                        Étapes de préparation
                    </h3>
                    
                    <div class="space-y-4">
                        @foreach($recipe->steps as $step)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-orange-500 text-white rounded-full flex items-center justify-center font-bold mr-4 mt-1">
                                {{ $step->order }}
                            </div>
                            <div class="flex-1">
                                <p class="text-gray-700 leading-relaxed">{{ $step->description }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Tips Section -->
                <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-6 border border-orange-200">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-lightbulb mr-3 text-orange-500"></i>
                        Conseils de préparation
                    </h3>
                    <div class="space-y-3 text-gray-700">
                        <div class="flex items-start">
                            <i class="fas fa-clock text-orange-500 mr-3 mt-1"></i>
                            <span>Préparez tous vos ingrédients avant de commencer</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-thermometer-half text-orange-500 mr-3 mt-1"></i>
                            <span>Respectez les températures de cuisson</span>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-balance-scale text-orange-500 mr-3 mt-1"></i>
                            <span>Pesez précisément vos ingrédients</span>
                        </div>
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
        function generateShoppingList() {
            const data = {
                recipe_ids: [{{ $recipe->id }}],
                portions: { {{ $recipe->id }}: {{ $recipe->portion }} }
            };
            
            fetch('/web/shopping-list/recipes', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.text())
            .then(html => {
                const newWindow = window.open('', '_blank');
                newWindow.document.write(html);
                newWindow.document.close();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Erreur lors de la génération de la liste de courses');
            });
        }
    </script>
</body>
</html> 