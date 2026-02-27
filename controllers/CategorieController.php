<?php
class CategorieController
{
    private CategorieDAO $dao;

    public function __construct()
    {
        $this->dao = new CategorieDAO();
    }

    /** Page liste des catégories */
    public function index(): void
    {
        AuthController::requireAuth();
        if (!isAdmin()) { redirect('index.php?page=dashboard'); }
        $categories = $this->dao->findAll();
        require VIEW_PATH . '/orders/categories.php';
    }

    /** Créer une catégorie (AJAX JSON) */
    public function store(): void
    {
        ob_clean();
        header('Content-Type: application/json');
        $data    = json_decode(file_get_contents('php://input'), true);
        $nom     = sanitize($data['nom']     ?? '');
        $couleur = sanitize($data['couleur'] ?? '#453dde');

        if (empty($nom)) {
            echo json_encode(['success' => false, 'message' => 'Le nom est requis.']);
            exit;
        }
        if ($this->dao->exists($nom)) {
            echo json_encode(['success' => false, 'message' => 'Cette catégorie existe déjà.']);
            exit;
        }
        try {
            $id = $this->dao->create($nom, $couleur);
            echo json_encode(['success' => true, 'id' => $id, 'nom' => $nom, 'couleur' => $couleur]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur serveur.']);
        }
        exit;
    }

    /** Modifier une catégorie (AJAX JSON) */
    public function update(): void
    {
        ob_clean();
        header('Content-Type: application/json');
        $id      = (int)($_GET['id'] ?? 0);
        $data    = json_decode(file_get_contents('php://input'), true);
        $nom     = sanitize($data['nom']     ?? '');
        $couleur = sanitize($data['couleur'] ?? '#453dde');

        if (!$id || empty($nom)) {
            echo json_encode(['success' => false, 'message' => 'Données invalides.']);
            exit;
        }
        if ($this->dao->exists($nom, $id)) {
            echo json_encode(['success' => false, 'message' => 'Ce nom est déjà utilisé.']);
            exit;
        }
        try {
            $this->dao->update($id, $nom, $couleur);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour.']);
        }
        exit;
    }

    /** Supprimer une catégorie (AJAX JSON) */
    public function delete(): void
    {
        ob_clean();
        header('Content-Type: application/json');
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID invalide.']);
            exit;
        }
        try {
            $this->dao->delete($id);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression.']);
        }
        exit;
    }
}
