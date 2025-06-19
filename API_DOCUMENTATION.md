# API Documentation - RecipeFlo

## Base URL
```
http://localhost:8000
```

## Endpoints

### Ingrédients

#### GET /ingredients
Récupère tous les ingrédients disponibles.

**Réponse :**
```json
[
  {
    "id": 1,
    "name": "Farine",
    "unit": "g",
    "created_at": "2025-06-18T07:00:00.000000Z",
    "updated_at": "2025-06-18T07:00:00.000000Z"
  }
]
```

#### POST /ingredients
Crée un nouvel ingrédient.

**Corps de la requête :**
```json
{
  "name": "Nouvel ingrédient",
  "unit": "g"
}
```

#### GET /ingredients/{id}
Récupère un ingrédient spécifique avec ses recettes associées.

#### PUT /ingredients/{id}
Met à jour un ingrédient.

#### DELETE /ingredients/{id}
Supprime un ingrédient.

### Recettes

#### GET /recipes
Récupère toutes les recettes avec leurs ingrédients et étapes.

**Réponse :**
```json
[
  {
    "id": 1,
    "title": "Crêpes classiques",
    "description": "Des crêpes légères et délicieuses...",
    "image": "crepes.jpg",
    "portion": 4,
    "age": 3,
    "ingredients": [
      {
        "id": 1,
        "name": "Farine",
        "unit": "g",
        "pivot": {
          "quantity": 250
        }
      }
    ],
    "steps": [
      {
        "id": 1,
        "description": "Mélanger la farine et le sel...",
        "order": 1
      }
    ]
  }
]
```

#### POST /recipes
Crée une nouvelle recette.

**Corps de la requête :**
```json
{
  "title": "Nouvelle recette",
  "description": "Description de la recette",
  "image": "image.jpg",
  "portion": 4,
  "age": 3,
  "ingredients": [
    {
      "id": 1,
      "quantity": 250
    }
  ],
  "steps": [
    {
      "description": "Première étape",
      "order": 1
    }
  ]
}
```

#### GET /recipes/{id}
Récupère une recette spécifique.

#### PUT /recipes/{id}
Met à jour une recette.

#### DELETE /recipes/{id}
Supprime une recette.

### Menus

#### GET /menus
Récupère tous les menus avec leurs recettes.

#### POST /menus
Crée un nouveau menu.

**Corps de la requête :**
```json
{
  "name": "Menu du week-end",
  "recipe_ids": [1, 2, 3]
}
```

#### GET /menus/{id}
Récupère un menu spécifique avec ses recettes, ingrédients et étapes.

#### PUT /menus/{id}
Met à jour un menu.

#### DELETE /menus/{id}
Supprime un menu.

### Listes de courses

#### GET /shopping-list/menu/{menuId}
Génère une liste de courses à partir d'un menu.

**Réponse :**
```json
{
  "menu": {
    "id": 1,
    "name": "Menu du week-end",
    "recipes": [...]
  },
  "shopping_list": [
    {
      "ingredient_id": 1,
      "name": "Farine",
      "unit": "g",
      "quantity": 500
    }
  ]
}
```

#### POST /shopping-list/recipes
Génère une liste de courses à partir de recettes sélectionnées.

**Corps de la requête :**
```json
{
  "recipe_ids": [1, 2, 3]
}
```

#### POST /shopping-list/menu/{menuId}/portions
Génère une liste de courses avec ajustement des portions.

**Corps de la requête :**
```json
{
  "portions": [
    {
      "recipe_id": 1,
      "multiplier": 2.5
    },
    {
      "recipe_id": 2,
      "multiplier": 1.5
    }
  ]
}
```

## Exemples d'utilisation

### Créer une recette complète
```bash
curl -X POST http://localhost:8000/recipes \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Pancakes américains",
    "description": "Des pancakes moelleux et délicieux",
    "portion": 4,
    "age": 3,
    "ingredients": [
      {"id": 1, "quantity": 200},
      {"id": 3, "quantity": 2},
      {"id": 4, "quantity": 300}
    ],
    "steps": [
      {"description": "Mélanger les ingrédients secs", "order": 1},
      {"description": "Ajouter les ingrédients liquides", "order": 2},
      {"description": "Faire cuire à feu moyen", "order": 3}
    ]
  }'
```

### Créer un menu
```bash
curl -X POST http://localhost:8000/menus \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Menu familial",
    "recipe_ids": [1, 2]
  }'
```

### Générer une liste de courses
```bash
curl -X GET http://localhost:8000/shopping-list/menu/1
```

## Codes de statut HTTP

- `200` : Succès
- `201` : Créé avec succès
- `400` : Requête invalide
- `404` : Ressource non trouvée
- `422` : Erreur de validation
- `500` : Erreur serveur 