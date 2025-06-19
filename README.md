# 🍳 RecipeFlo - Application de Gestion de Recettes et Menus

**RecipeFlo** est une application web moderne développée en **PHP 8.3** et **Laravel 12** pour la gestion complète de vos recettes de cuisine, menus et listes de courses.

## ✨ Fonctionnalités Principales

### 📝 Gestion des Recettes
- **Création et édition** de recettes avec interface moderne
- **Multi-catégories** par recette (Petit-déjeuner, Entrées, Plats, Desserts, etc.)
- **Gestion des ingrédients** avec quantités et unités
- **Étapes de préparation** ordonnées
- **Images** pour chaque recette
- **Informations détaillées** : portions, âge minimum, description
- **Recherche et filtres** par catégorie

### 🍽️ Gestion des Menus
- **Création de menus personnalisés** en sélectionnant plusieurs recettes
- **Système de brouillons** pour sauvegarder et reprendre plus tard
- **Ajustement des portions** par recette dans le menu
- **Éléments personnalisés** ajoutables aux menus (lessive, pain, etc.)
- **Prévisualisation** en temps réel lors de la création
- **Édition complète** des menus existants

### 🛒 Liste de Courses Globale et Persistante
- **Liste unique et persistante** entre les sessions
- **Ajout depuis les menus** ou manuellement
- **Cochage/décochage** des articles trouvés
- **Gestion intelligente** : évite les doublons, additionne les quantités
- **Notes personnalisées** pour chaque article
- **Historique** des articles récemment achetés
- **Impression** de la liste
- **Actions en lot** : nettoyer les cochés, tout remettre en liste

### 🏷️ Éléments Personnalisés
- **Système de suggestions** basé sur l'usage fréquent
- **Sauvegarde automatique** des éléments utilisés
- **Réutilisation** des éléments courants
- **Gestion par menu** avec persistance

## 🚀 Installation

### Prérequis
- PHP 8.3+
- Composer
- MySQL 8.0+
- Node.js (optionnel, pour les assets)

### 1. Cloner le projet
```bash
git clone <repository-url>
cd RecipeFlo
```

### 2. Installer les dépendances
```bash
composer install
```

### 3. Configuration de l'environnement
```bash
cp .env.example .env
```

Modifier le fichier `.env` avec vos paramètres :
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=florecipe
DB_USERNAME=root
DB_PASSWORD=toor
```

### 4. Générer la clé d'application
```bash
php artisan key:generate
```

### 5. Exécuter les migrations
```bash
php artisan migrate
```

### 6. Peupler la base de données
```bash
php artisan db:seed
```

### 7. Démarrer le serveur
```bash
php artisan serve
```

L'application sera accessible sur `http://localhost:8000`

## 📱 Utilisation

### Navigation Principale
- **Accueil** : Vue d'ensemble des recettes
- **Recettes** : Gestion complète des recettes
- **Menus** : Création et gestion des menus
- **Liste de Courses** : Liste globale persistante

### Créer une Recette
1. Aller sur `/recettes/create`
2. Remplir les informations : titre, description, portions, âge
3. Ajouter des ingrédients avec quantités
4. Créer les étapes de préparation
5. Sélectionner les catégories
6. Ajouter une image (optionnel)

### Créer un Menu
1. Aller sur `/web/menus/create`
2. Donner un nom au menu
3. Sélectionner les recettes souhaitées
4. Ajuster les portions par recette
5. Ajouter des éléments personnalisés si besoin
6. Sauvegarder ou créer un brouillon

### Utiliser la Liste de Courses
1. Accéder à `/web/shopping-list`
2. **Ajouter manuellement** : utiliser le formulaire en haut
3. **Ajouter depuis un menu** : cliquer sur le bouton "+" vert sur un menu
4. **Cocher les achats** : cliquer sur les cases à cocher
5. **Gérer la liste** : supprimer, nettoyer, imprimer

## 🏗️ Architecture Technique

### Base de Données
- **MySQL** avec migrations Laravel
- **Relations** : many-to-many, one-to-many
- **Tables principales** : recipes, ingredients, steps, categories, menus, shopping_lists

### Modèles Principaux
- `Recipe` : Gestion des recettes
- `Ingredient` : Ingrédients avec unités
- `Step` : Étapes de préparation
- `Category` : Catégories de recettes
- `Menu` : Menus avec brouillons
- `ShoppingList` : Liste de courses globale
- `CustomItem` : Éléments personnalisés

### Contrôleurs
- `WebController` : Interface utilisateur
- `RecipeController` : API des recettes
- `MenuController` : API des menus
- `GlobalShoppingListController` : Liste de courses globale
- `CustomItemController` : Éléments personnalisés

### Services
- `ShoppingListService` : Génération de listes de courses

## 🔧 Fonctionnalités Avancées

### Système de Brouillons
- Sauvegarder un menu en cours
- Reprendre l'édition plus tard
- Distinction visuelle entre menus finis et brouillons

### Gestion Intelligente des Listes
- **Détection des doublons** : addition automatique des quantités
- **Source tracking** : indique l'origine (menu ou manuel)
- **Persistance** : conservation entre les sessions
- **Historique** : suivi des achats récents

### Interface Moderne
- **Tailwind CSS** pour un design responsive
- **Font Awesome** pour les icônes
- **JavaScript** pour les interactions dynamiques
- **AJAX** pour les mises à jour en temps réel

## 📊 API Endpoints

### Recettes
- `GET /recipes` - Liste des recettes
- `POST /recipes` - Créer une recette
- `GET /recipes/{id}` - Détails d'une recette
- `PUT /recipes/{id}` - Modifier une recette
- `DELETE /recipes/{id}` - Supprimer une recette

### Menus
- `GET /menus` - Liste des menus
- `POST /menus` - Créer un menu
- `GET /menus/{id}` - Détails d'un menu
- `PUT /menus/{id}` - Modifier un menu
- `DELETE /menus/{id}` - Supprimer un menu

### Liste de Courses Globale
- `GET /web/shopping-list` - Page de la liste
- `POST /web/shopping-list/custom-item` - Ajouter un élément
- `PUT /web/shopping-list/{id}/status` - Cocher/décocher
- `DELETE /web/shopping-list/{id}` - Supprimer un élément
- `POST /web/menus/{id}/add-to-shopping-list` - Ajouter depuis un menu

## 🎨 Interface Utilisateur

### Design Responsive
- **Mobile-first** : optimisé pour tous les écrans
- **Navigation intuitive** : accès rapide aux fonctionnalités
- **Feedback visuel** : animations et transitions
- **Couleurs cohérentes** : thème orange/rouge pour la cuisine

### Fonctionnalités UX
- **Recherche en temps réel** : filtrage instantané
- **Actions rapides** : boutons contextuels sur les recettes
- **Prévisualisation** : aperçu avant sauvegarde
- **Validation** : messages d'erreur clairs

## 🔒 Sécurité

- **CSRF Protection** : tokens sur tous les formulaires
- **Validation** : règles strictes sur les données
- **Sanitisation** : nettoyage des entrées utilisateur
- **Migrations sécurisées** : structure de base de données robuste

## 🚀 Déploiement

### Production
1. Configurer l'environnement de production
2. Optimiser les performances (cache, assets)
3. Configurer la base de données de production
4. Déployer avec un serveur web (Apache/Nginx)

### Docker (optionnel)
```bash
docker-compose up -d
```

## 🤝 Contribution

1. Fork le projet
2. Créer une branche feature (`git checkout -b feature/AmazingFeature`)
3. Commit les changements (`git commit -m 'Add AmazingFeature'`)
4. Push vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrir une Pull Request

## 📝 Licence

Ce projet est développé pour un usage personnel et éducatif.

## 🆘 Support

Pour toute question ou problème :
- Vérifier la documentation
- Consulter les logs Laravel
- Tester les migrations et seeders

---

**RecipeFlo** - Simplifiez votre gestion culinaire ! 🍳✨