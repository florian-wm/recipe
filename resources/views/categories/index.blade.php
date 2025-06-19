<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RecipeFlo - Catégories</title>
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
    <main class="container mx-auto px-6 py-10">
        <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-xl p-8 mb-10">
            <h2 class="text-xl font-semibold mb-4 flex items-center gap-2"><i class="fas fa-plus-circle text-green-500"></i>Ajouter une catégorie</h2>
            <form id="add-category-form" class="flex flex-col md:flex-row gap-4 items-end">
                @csrf
                <div class="flex-1">
                    <label class="block text-sm font-medium mb-1">Nom</label>
                    <input type="text" name="name" required class="px-4 py-2 border-2 border-orange-200 rounded-lg focus:ring-2 focus:ring-orange-400 w-full transition">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Couleur</label>
                    <input type="color" name="color" value="#FFA500" class="w-12 h-10 p-0 border-none rounded-lg shadow">
                </div>
                <button type="submit" class="flex items-center gap-2 px-5 py-2 bg-green-500 text-white rounded-lg shadow hover:bg-green-600 transition">
                    <i class="fas fa-check"></i>Ajouter
                </button>
            </form>
            <div id="form-feedback" class="mt-3 text-sm font-semibold"></div>
        </div>
        <div>
            <h2 class="text-xl font-semibold mb-6 flex items-center gap-2"><i class="fas fa-tags text-yellow-500"></i>Vos catégories</h2>
            <div id="categories-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"></div>
        </div>
    </main>
    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-6 text-center">
            <p>&copy; 2025 RecipeFlo - Tous droits réservés</p>
        </div>
    </footer>
    <script>
    function showFeedback(msg, color) {
        const el = document.getElementById('form-feedback');
        el.textContent = msg;
        el.style.color = color;
        setTimeout(() => { el.textContent = ''; }, 2000);
    }
    async function loadCategories() {
        const res = await fetch('/api/categories');
        const categories = await res.json();
        const grid = document.getElementById('categories-grid');
        grid.innerHTML = '';
        if(categories.length === 0) {
            grid.innerHTML = '<div class="col-span-full text-gray-400 text-center py-8">Aucune catégorie pour le moment.</div>';
            return;
        }
        categories.forEach(category => {
            const card = document.createElement('div');
            card.className = `relative bg-white rounded-2xl shadow-lg p-6 flex flex-col gap-3 border-t-4 transition hover:scale-105 hover:shadow-2xl duration-200`;
            card.style.borderTopColor = category.color;
            card.innerHTML = `
                <div class="flex items-center gap-3 mb-2">
                    <span class="inline-block w-8 h-8 rounded-full border-2 border-white shadow" style="background: ${category.color}"></span>
                    <span class="text-lg font-bold text-gray-800">${category.name}</span>
                </div>
                <div class="flex-1"></div>
                <div class="absolute top-3 right-3 flex gap-2">
                    <button title="Éditer" class="edit-btn bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-full p-2 shadow transition" data-id="${category.id}" data-name="${category.name}" data-color="${category.color}">
                        <i class="fas fa-pen"></i>
                    </button>
                    <button title="Supprimer" class="delete-btn bg-red-100 hover:bg-red-200 text-red-700 rounded-full p-2 shadow transition" data-id="${category.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            grid.appendChild(card);
        });
        addDeleteListeners();
        addEditListeners();
    }
    document.getElementById('add-category-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const data = {
            name: formData.get('name'),
            color: formData.get('color'),
        };
        const res = await fetch('/api/categories', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify(data)
        });
        if (res.ok) {
            form.reset();
            showFeedback('Catégorie ajoutée !', 'green');
            loadCategories();
        } else {
            showFeedback('Erreur lors de la création.', 'red');
        }
    });
    function addDeleteListeners() {
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.onclick = async function() {
                if (!confirm('Supprimer cette catégorie ?')) return;
                const id = this.dataset.id;
                const res = await fetch(`/api/categories/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                });
                if (res.ok) {
                    showFeedback('Catégorie supprimée.', 'orange');
                    loadCategories();
                } else {
                    showFeedback('Erreur lors de la suppression.', 'red');
                }
            };
        });
    }
    function addEditListeners() {
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.onclick = function() {
                const id = this.dataset.id;
                const name = this.dataset.name;
                const color = this.dataset.color;
                // Chercher la carte parente
                const card = btn.closest('div.relative');
                // Empêcher plusieurs formulaires
                if (card.querySelector('.edit-form')) return;
                // Masquer le contenu principal
                card.querySelectorAll(':scope > div:not(.absolute)').forEach(el => el.style.display = 'none');
                // Créer le formulaire inline
                const form = document.createElement('form');
                form.className = 'edit-form flex flex-col gap-3 mt-2';
                form.innerHTML = `
                    <label class='text-sm font-medium'>Nom
                        <input type='text' name='name' value='${name.replace(/'/g, "&#39;")}' required class='mt-1 px-3 py-2 border-2 border-orange-200 rounded-lg w-full'>
                    </label>
                    <label class='text-sm font-medium flex items-center gap-2'>Couleur
                        <input type='color' name='color' value='${color}' class='w-10 h-8 p-0 border-none rounded-lg shadow'>
                    </label>
                    <div class='flex gap-2 mt-2'>
                        <button type='submit' class='flex-1 bg-blue-500 text-white rounded-lg px-4 py-2 hover:bg-blue-600 transition'><i class='fas fa-check mr-1'></i>Valider</button>
                        <button type='button' class='flex-1 bg-gray-200 text-gray-700 rounded-lg px-4 py-2 hover:bg-gray-300 transition cancel-edit'><i class='fas fa-times mr-1'></i>Annuler</button>
                    </div>
                `;
                card.appendChild(form);
                // Annuler
                form.querySelector('.cancel-edit').onclick = function() {
                    form.remove();
                    card.querySelectorAll(':scope > div:not(.absolute)').forEach(el => el.style.display = '');
                };
                // Soumission
                form.onsubmit = function(e) {
                    e.preventDefault();
                    const newName = form.name.value;
                    const newColor = form.color.value;
                    fetch(`/api/categories/${id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({ name: newName, color: newColor })
                    }).then(res => {
                        if (res.ok) {
                            showFeedback('Catégorie modifiée.', 'blue');
                            loadCategories();
                        } else {
                            showFeedback('Erreur lors de la modification.', 'red');
                        }
                    });
                };
            };
        });
    }
    loadCategories();
    </script>
</body>
</html> 