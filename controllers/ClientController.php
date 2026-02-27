<?php
declare(strict_types=1);

class ClientController
{
    private ClientDAO $dao;

    public function __construct()
    {
        $this->dao = new ClientDAO();
    }

    /**
     * Recherche de clients (AJAX)
     */
    public function search(): void
    {
        header('Content-Type: application/json');
        $q = sanitize($_GET['q'] ?? '');
        
        if (strlen($q) < 1) {
            echo json_encode([]);
            return;
        }

        try {
            $clients = $this->dao->search($q);
            echo json_encode($clients);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur de recherche.']);
        }
    }

    /**
     * Création rapide d'un client (AJAX)
     */
    public function store(): void
    {
        header('Content-Type: application/json');
        
        $nom       = sanitize($_POST['nom'] ?? '');
        $telephone = sanitize($_POST['telephone'] ?? '');
        $email     = sanitize($_POST['email'] ?? '');
        $adresse   = sanitize($_POST['adresse'] ?? '');

        if (empty($nom)) {
            echo json_encode(['success' => false, 'message' => 'Le nom est obligatoire']);
            return;
        }

        try {
            $id = $this->dao->create($nom, $telephone, $email, $adresse);
            $client = $this->dao->findById($id);
            echo json_encode(['success' => true, 'client' => $client]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function index(): void
    {
        $clients = $this->dao->findAll();
        $pageTitle = "Gestion Clients";
        require VIEW_PATH . '/clients/index.php';
    }

    public function delete(): void
    {
        if (!isAdmin()) {
            setFlash('Accès refusé', 'error');
            redirect('index.php?page=dashboard');
        }

        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $this->dao->delete($id);
            setFlash('Client supprimé avec succès');
        }
        redirect('index.php?page=clients');
    }
}
