# Projet 05 — API REST en PHP

> **Niveau :** Expert | **Durée :** 8-10 heures

API REST simple sans framework, conforme aux standards HTTP.

## Endpoints

| Méthode | URL | Description |
|---------|-----|-------------|
| GET | /api/articles | Liste tous les articles |
| GET | /api/articles/{id} | Récupère un article |
| POST | /api/articles | Crée un article |
| PUT | /api/articles/{id} | Met à jour un article |
| DELETE | /api/articles/{id} | Supprime un article |

## public/index.php — Front Controller API

```php
<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

require_once '../config/database.php';

$methode = $_SERVER['REQUEST_METHOD'];
$uri     = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri     = preg_replace('#^/api#', '', $uri);  // Supprimer préfixe /api
$segments = array_filter(explode('/', trim($uri, '/')));
$segments = array_values($segments);

$ressource = $segments[0] ?? null;
$id        = isset($segments[1]) ? (int) $segments[1] : null;

try {
    switch ($ressource) {
        case 'articles':
            (new ArticleController(getConnexion()))->handle($methode, $id);
            break;
        default:
            reponse(404, ['erreur' => "Ressource '$ressource' introuvable."]);
    }
} catch (Throwable $e) {
    reponse(500, ['erreur' => 'Erreur interne du serveur.']);
    error_log($e->getMessage());
}

function reponse(int $code, mixed $donnees): never {
    http_response_code($code);
    echo json_encode($donnees, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit();
}
```

## ArticleController.php

```php
<?php
declare(strict_types=1);

class ArticleController {
    public function __construct(private PDO $pdo) {}

    public function handle(string $methode, ?int $id): void {
        match($methode) {
            'GET'    => $id ? $this->show($id) : $this->index(),
            'POST'   => $this->store(),
            'PUT'    => $id ? $this->update($id) : reponse(400, ['erreur' => 'ID requis.']),
            'DELETE' => $id ? $this->destroy($id) : reponse(400, ['erreur' => 'ID requis.']),
            default  => reponse(405, ['erreur' => "Méthode '$methode' non autorisée."]),
        };
    }

    private function index(): never {
        $page     = max(1, (int) ($_GET['page'] ?? 1));
        $parPage  = min(50, max(1, (int) ($_GET['par_page'] ?? 10)));
        $offset   = ($page - 1) * $parPage;

        $total    = $this->pdo->query("SELECT COUNT(*) FROM articles WHERE publie=1")->fetchColumn();
        $articles = $this->pdo->prepare("SELECT id, titre, resume, cree_le FROM articles WHERE publie=1 ORDER BY cree_le DESC LIMIT :limit OFFSET :offset");
        $articles->bindValue(':limit',  $parPage, PDO::PARAM_INT);
        $articles->bindValue(':offset', $offset,  PDO::PARAM_INT);
        $articles->execute();

        reponse(200, [
            'donnees'    => $articles->fetchAll(),
            'pagination' => [
                'total'      => (int) $total,
                'page'       => $page,
                'par_page'   => $parPage,
                'total_pages'=> (int) ceil($total / $parPage),
            ]
        ]);
    }

    private function show(int $id): never {
        $stmt = $this->pdo->prepare("SELECT * FROM articles WHERE id = :id AND publie = 1");
        $stmt->execute([':id' => $id]);
        $article = $stmt->fetch();
        $article ? reponse(200, $article) : reponse(404, ['erreur' => "Article #$id introuvable."]);
    }

    private function store(): never {
        $corps = $this->corpsJSON();
        $titre   = trim($corps['titre']   ?? '');
        $contenu = trim($corps['contenu'] ?? '');

        if (strlen($titre) < 3)   reponse(422, ['erreur' => 'Titre trop court (min. 3 chars).']);
        if (strlen($contenu) < 10) reponse(422, ['erreur' => 'Contenu trop court (min. 10 chars).']);

        $stmt = $this->pdo->prepare("INSERT INTO articles (titre, contenu, publie) VALUES (:titre, :contenu, 1)");
        $stmt->execute([':titre' => $titre, ':contenu' => $contenu]);
        $id = (int) $this->pdo->lastInsertId();

        http_response_code(201);
        header("Location: /api/articles/$id");
        reponse(201, ['id' => $id, 'titre' => $titre, 'message' => 'Article créé.']);
    }

    private function update(int $id): never {
        $corps = $this->corpsJSON();
        $stmt  = $this->pdo->prepare("UPDATE articles SET titre = :titre, contenu = :contenu WHERE id = :id");
        $stmt->execute([':titre' => $corps['titre'] ?? '', ':contenu' => $corps['contenu'] ?? '', ':id' => $id]);
        $stmt->rowCount() ? reponse(200, ['message' => 'Article mis à jour.']) : reponse(404, ['erreur' => "Article #$id introuvable."]);
    }

    private function destroy(int $id): never {
        $stmt = $this->pdo->prepare("DELETE FROM articles WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $stmt->rowCount() ? reponse(204, []) : reponse(404, ['erreur' => "Article #$id introuvable."]);
    }

    private function corpsJSON(): array {
        $corps = json_decode(file_get_contents('php://input'), true);
        if (!is_array($corps)) reponse(400, ['erreur' => 'Corps JSON invalide.']);
        return $corps;
    }
}
```

## Tester l'API

```bash
# Lister les articles
curl http://localhost/api/articles

# Créer un article
curl -X POST http://localhost/api/articles \
  -H "Content-Type: application/json" \
  -d '{"titre":"Mon article","contenu":"Contenu de mon article API."}'

# Récupérer un article
curl http://localhost/api/articles/1

# Mettre à jour
curl -X PUT http://localhost/api/articles/1 \
  -H "Content-Type: application/json" \
  -d '{"titre":"Titre modifié","contenu":"Contenu mis à jour."}'

# Supprimer
curl -X DELETE http://localhost/api/articles/1
```
