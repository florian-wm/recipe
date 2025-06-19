<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecipeFlo - Mes Menus</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
                    <span class="text-sm text-gray-400 leading-tight">Vos menus, recettes & courses en un clin d'œil</span>
                </div>
            </div>
            <div class="flex items-center justify-end w-full md:w-auto mt-3 md:mt-0 gap-2">
                <span class="hidden md:inline-block text-gray-500 text-sm mr-2"><i class="fas fa-clipboard-list mr-1"></i>{{ $finishedMenus->count() }} menus</span>
                <a href="{{ route('web.shopping-list.global') }}" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                    <i class="fas fa-shopping-cart mr-2"></i>Liste de Courses
                </a>
                <a href="{{ route('recettes.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-utensils mr-2"></i>Recettes
                </a>
                <a href="{{ route('web.menus.create') }}" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors flex items-center gap-2">
                    <i class="fas fa-plus"></i> Nouveau menu
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-6 py-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-1">Mes Menus</h2>
            <p class="text-gray-600">Créez et gérez vos menus personnalisés</p>
        </div>
        @if($finishedMenus->count() > 0)
        @php
            $borderColors = ['#fdba74', '#f472b6', '#60a5fa', '#6ee7b7', '#facc15', '#a5b4fc', '#fca5a5', '#fcd34d'];
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($finishedMenus as $menu)
            @php $borderColor = $borderColors[$menu->id % count($borderColors)]; @endphp
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden" style="border-top: 4px solid {{ $borderColor }}">
                <div class="p-6 flex flex-col h-full">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="flex-1 text-base md:text-lg font-bold text-gray-900 break-words leading-snug">{{ $menu->name }}</h3>
                        <div class="flex gap-0.5 ml-2 flex-shrink-0">
                            <a href="{{ route('web.menus.edit', $menu->id) }}" class="p-1.5 rounded-full hover:bg-blue-100 text-blue-600 text-sm" title="Éditer"><i class="fas fa-edit"></i></a>
                            <button onclick="deleteMenu({{ $menu->id }})" class="p-1.5 rounded-full hover:bg-red-100 text-red-600 text-sm" title="Supprimer"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                    <div class="flex gap-2 mb-4">
                        <span class="inline-flex items-center px-2 py-1 bg-orange-100 text-orange-700 text-xs rounded-full"><i class="fas fa-utensils mr-1"></i>{{ $menu->meals->flatMap->mealRecipes->count() }} recettes</span>
                        <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full"><i class="fas fa-users mr-1"></i>{{ $menu->meals->flatMap->mealRecipes->sum('portion') }} portions</span>
                    </div>
                    <div class="mb-4">
                        <h4 class="text-xs font-semibold text-gray-500 mb-1">Recettes :</h4>
                        <div class="flex gap-2 overflow-x-auto">
                            @foreach($menu->meals->flatMap->mealRecipes->take(4) as $mr)
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded">{{ $mr->recipe ? $mr->recipe->title : '' }}</span>
                            @endforeach
                            @if($menu->meals->flatMap->mealRecipes->count() > 4)
                                <span class="px-2 py-1 bg-gray-200 text-gray-500 text-xs rounded">+{{ $menu->meals->flatMap->mealRecipes->count() - 4 }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex gap-2 mt-auto">
                        <button onclick="addToGlobalList({{ $menu->id }})" class="flex-1 bg-green-100 text-green-700 py-2 px-4 rounded-lg hover:bg-green-200 transition-colors" title="Ajouter à la liste de courses"><i class="fas fa-plus"></i></button>
                        <button class="flex-1 bg-blue-100 text-blue-700 py-2 px-4 rounded-lg hover:bg-blue-200 transition-colors" title="Partager"><i class="fas fa-share"></i></button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-12">
            <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Aucun menu créé</h3>
            <p class="text-gray-500 mb-6">Commencez par créer votre premier menu !</p>
            <a href="{{ route('web.menus.create') }}" class="inline-flex items-center px-6 py-3 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors">
                <i class="fas fa-plus mr-2"></i>Créer un menu
            </a>
        </div>
        @endif
        @if($finishedMenus->count() > 0)
        <div class="mt-12 bg-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-bolt mr-3 text-orange-500"></i>
                Actions rapides
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-shopping-cart text-3xl text-orange-500 mb-2"></i>
                    <h4 class="font-semibold text-gray-800 mb-1">Liste de courses rapide</h4>
                    <p class="text-sm text-gray-600">Générer une liste depuis des recettes sélectionnées</p>
                </div>
                <div class="text-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-calendar text-3xl text-green-500 mb-2"></i>
                    <h4 class="font-semibold text-gray-800 mb-1">Planification</h4>
                    <p class="text-sm text-gray-600">Planifier vos menus de la semaine</p>
                </div>
                <div class="text-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-share-alt text-3xl text-blue-500 mb-2"></i>
                    <h4 class="font-semibold text-gray-800 mb-1">Partage</h4>
                    <p class="text-sm text-gray-600">Partager vos menus avec vos proches</p>
                </div>
            </div>
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
        // Add menu items to global shopping list
        function addToGlobalList(menuId) {
            if (!confirm('Ajouter tous les éléments de ce menu à votre liste de courses globale ?')) {
                return;
            }
            fetch(`/menus/${menuId}/add-to-shopping-list`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
            }).then(res => {
                if (res.ok) {
                    alert('Ajouté à la liste de courses !');
                } else {
                    alert('Erreur lors de l\'ajout.');
                }
            });
        }
        function deleteMenu(menuId) {
            if (!confirm('Supprimer ce menu ?')) return;
            fetch(`/api/menus/${menuId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(res => {
                if (res.ok) {
                    location.reload();
                } else {
                    alert('Erreur lors de la suppression.');
                }
            });
        }
    </script>
</body>
</html> 