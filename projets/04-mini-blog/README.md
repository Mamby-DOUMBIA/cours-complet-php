# Projet 04 — Mini blog dynamique

> **Niveau :** Avancé | **Durée :** 10-15 heures

Blog complet avec : articles, catégories, commentaires, système d'administration.

## Fonctionnalités
- Liste d'articles avec pagination
- Détail d'un article avec commentaires
- Système d'administration (CRUD articles)
- Catégories et tags
- Recherche full-text

## Base de données

```sql
CREATE DATABASE mini_blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mini_blog;

CREATE TABLE categories (
    id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom  VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE articles (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titre       VARCHAR(255) NOT NULL,
    slug        VARCHAR(255) NOT NULL UNIQUE,
    contenu     TEXT NOT NULL,
    resume      VARCHAR(500),
    categorie_id INT UNSIGNED,
    publie      TINYINT(1) DEFAULT 0,
    cree_le     DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE SET NULL,
    FULLTEXT(titre, contenu)  -- Pour la recherche full-text
);

CREATE TABLE commentaires (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    article_id INT UNSIGNED NOT NULL,
    auteur_nom VARCHAR(100) NOT NULL,
    auteur_email VARCHAR(255) NOT NULL,
    contenu    TEXT NOT NULL,
    approuve   TINYINT(1) DEFAULT 0,
    cree_le    DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
);
```

## Structure des fichiers

```
04-mini-blog/
├── public/
│   ├── index.php           ← Liste des articles
│   ├── article.php         ← Détail d'un article
│   ├── categorie.php       ← Articles d'une catégorie
│   └── recherche.php       ← Page de recherche
├── admin/
│   ├── index.php           ← Tableau de bord admin
│   ├── articles.php        ← Gestion des articles
│   └── commentaires.php    ← Modération des commentaires
├── includes/
│   ├── fonctions.php       ← Fonctions utilitaires
│   └── auth-admin.php      ← Protection admin
└── config/
    └── database.php
```

Voir les fichiers PHP dans ce dossier pour l'implémentation complète.
