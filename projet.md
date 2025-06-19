# Spécification fonctionnelle – RecipeFlo

## Objectif général
RecipeFlo est une application web permettant de gérer des recettes de cuisine, de composer des menus personnalisés et de générer automatiquement des listes de courses intelligentes et persistantes.

---

## 1. Gestion des Recettes

### 1.1 Création et édition
- L'utilisateur peut créer une recette avec :
  - Un titre
  - Une description
  - Un nombre de portions
  - Un âge minimum conseillé (optionnel)
  - Une ou plusieurs images (optionnel)
- L'utilisateur peut modifier ou supprimer une recette existante.

### 1.2 Ingrédients
- Chaque recette contient une liste d'ingrédients.
- Pour chaque ingrédient :
  - Nom
  - Quantité
  - Unité (g, kg, ml, etc.)

### 1.3 Étapes de préparation
- Chaque recette contient une liste ordonnée d'étapes de préparation (texte).

### 1.4 Catégories
- Une recette peut appartenir à plusieurs catégories (ex : Petit-déjeuner, Entrée, Plat, Dessert, etc.).
- Les catégories sont personnalisables.

### 1.5 Recherche et filtres
- L'utilisateur peut rechercher une recette par nom ou filtrer par catégorie.

---

## 2. Gestion des Menus

### 2.1 Création de menus
- L'utilisateur peut créer un menu en sélectionnant plusieurs recettes existantes.
- Il donne un nom au menu.
- Il peut ajuster le nombre de portions pour chaque recette du menu.

---

## 3. Liste de Courses

### 3.1 Génération automatique
- L'utilisateur peut générer une liste de courses à partir d'un menu (toutes les recettes et éléments personnalisés du menu).
- Les ingrédients identiques sont fusionnés (quantités additionnées, unités gérées intelligemment).

### 3.2 Liste globale persistante
- L'utilisateur dispose d'une liste de courses globale, persistante entre les sessions.
- Il peut ajouter des articles manuellement ou depuis un menu.
- Il peut cocher/décocher les articles achetés.
- Il peut supprimer des articles ou nettoyer tous les articles cochés.
- Il peut imprimer ou exporter la liste de courses.

### 3.3 Historique et suggestions
- Les articles récemment achetés sont affichés dans une section dédiée.
- Les éléments personnalisés fréquemment utilisés sont suggérés lors de l'ajout manuel.

---

## 4. Interface Utilisateur

### 4.1 Navigation
- Accès rapide aux sections : Recettes, Menus, Liste de courses.
- Boutons d'action contextuels (ajouter, éditer, supprimer, générer liste, etc.).

### 4.2 Responsive
- L'interface s'adapte à tous les écrans (mobile, tablette, desktop).

### 4.3 Feedback utilisateur
- Messages de confirmation, erreurs, succès.
- Animations et transitions pour les actions importantes.

---

## 5. Sécurité & Validation

- Protection CSRF sur tous les formulaires.
- Validation stricte des données côté serveur et client.
- Nettoyage des entrées utilisateur pour éviter les injections.
- inscription et Connection requise

---

## 6. API (si besoin d'une version SPA ou mobile)

- Endpoints CRUD pour recettes, menus, listes de courses, éléments personnalisés.
- Authentification (optionnelle, si multi-utilisateur).

---

## 7. Fonctionnalités avancées (optionnelles)

- Partage de menus ou listes de courses par lien ou email.
- Export PDF avancé.
- Système de tags ou favoris sur les recettes.

---

## 8. Résumé des écrans principaux

- **Accueil** : Vue d'ensemble, accès rapide aux recettes, menus, liste de courses.
- **Recettes** : Liste, création, édition, suppression, recherche, filtres.
- **Menus** : Liste, création, édition, suppression, brouillons, détail.
- **Liste de courses** : Génération depuis menu, ajout manuel, gestion globale, impression/export.

---
Projet en laravel avec php 8.3 