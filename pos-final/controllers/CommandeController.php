<?php
class CommandeController
{
    private CommandeDAO $commandeDAO;
    private ProduitDAO  $produitDAO;

    public function __construct()
    {
        $this->commandeDAO = new CommandeDAO();
        $this->produitDAO  = new ProduitDAO();
    }

    // ════════════════════════════════════════════════
    //  COMMANDES
    // ════════════════════════════════════════════════

    public function dashboard(): void
    {
        $stats     = $this->commandeDAO->getStats();
        $commandes = $this->commandeDAO->findAll();
        require VIEW_PATH . '/orders/dashboard.php';
    }

    public function showPos(): void
    {
        $produits = $this->produitDAO->findAll();
        require VIEW_PATH . '/orders/pos.php';
    }

    /** Créer une commande (AJAX JSON) */
    public function store(): void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        $clientName = sanitize($data['client_name'] ?? '');
        $lignes     = $data['lignes'] ?? [];

        if (empty($clientName)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Nom du client requis.']);
            return;
        }

        $lignesPropres = [];
        foreach ($lignes as $l) {
            $nom  = sanitize($l['produit_nom'] ?? '');
            $qte  = (int)   ($l['quantite']     ?? 0);
            $prix = (float) ($l['prix_unitaire'] ?? 0);
            if ($nom && $qte > 0 && $prix >= 0) {
                $lignesPropres[] = ['produit_nom' => $nom, 'quantite' => $qte, 'prix_unitaire' => $prix];
            }
        }

        if (empty($lignesPropres)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Panier vide ou invalide.']);
            return;
        }

        try {
            $id       = $this->commandeDAO->create($clientName, $lignesPropres);
            $commande = $this->commandeDAO->findById($id);
            echo json_encode(['success' => true, 'commande' => $commande]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur serveur.']);
        }
    }

    public function history(): void
    {
        $dateDebut = sanitize($_GET['date_debut'] ?? '');
        $dateFin   = sanitize($_GET['date_fin']   ?? '');
        $status    = sanitize($_GET['status']      ?? '');
        $commandes = $this->commandeDAO->findAll($dateDebut, $dateFin, $status);
        require VIEW_PATH . '/orders/history.php';
    }

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if (!$id) redirect('index.php?page=history');

        $commande = $this->commandeDAO->findById($id);
        if (!$commande || $commande['status'] === 'annulee') {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Modification impossible.'];
            redirect('index.php?page=history');
        }
        $produits = $this->produitDAO->findAll();
        require VIEW_PATH . '/orders/edit.php';
    }

    public function update(): void
    {
        header('Content-Type: application/json');
        $id   = (int) ($_GET['id'] ?? 0);
        $data = json_decode(file_get_contents('php://input'), true);

        $clientName    = sanitize($data['client_name'] ?? '');
        $lignes        = $data['lignes'] ?? [];
        $lignesPropres = [];

        foreach ($lignes as $l) {
            $nom  = sanitize($l['produit_nom'] ?? '');
            $qte  = (int)   ($l['quantite']     ?? 0);
            $prix = (float) ($l['prix_unitaire'] ?? 0);
            if ($nom && $qte > 0) {
                $lignesPropres[] = ['produit_nom' => $nom, 'quantite' => $qte, 'prix_unitaire' => $prix];
            }
        }

        try {
            $this->commandeDAO->update($id, $clientName, $lignesPropres);
            $commande = $this->commandeDAO->findById($id);
            echo json_encode(['success' => true, 'commande' => $commande]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur de mise à jour.']);
        }
    }

    public function cancel(): void
    {
        header('Content-Type: application/json');
        $id = (int) ($_GET['id'] ?? 0);
        if ($this->commandeDAO->annuler($id)) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Annulation impossible.']);
        }
    }

    public function ticket(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if (!$id) redirect('index.php?page=history');
        $commande = $this->commandeDAO->findById($id);
        if (!$commande) redirect('index.php?page=history');
        require VIEW_PATH . '/orders/ticket.php';
    }

    // ════════════════════════════════════════════════
    //  PRODUITS
    // ════════════════════════════════════════════════

    /** Page de gestion des produits */
    public function produits(): void
    {
        $produits = $this->produitDAO->findAllAdmin();
        require VIEW_PATH . '/orders/produits.php';
    }

    /** Ajouter un produit (AJAX JSON) */
    public function storeProduct(): void
    {
        ob_clean();
        header('Content-Type: application/json');

        $data      = json_decode(file_get_contents('php://input'), true);
        $nom       = sanitize($data['nom']       ?? '');
        $prix      = (float)($data['prix']       ?? 0);
        $categorie = sanitize($data['categorie'] ?? 'Général');
        $actif     = (int)($data['actif']        ?? 1);

        if (empty($nom)) {
            echo json_encode(['success' => false, 'message' => 'Le nom du produit est requis.']);
            exit;
        }
        if ($prix < 0) {
            echo json_encode(['success' => false, 'message' => 'Le prix ne peut pas être négatif.']);
            exit;
        }

        try {
            $id = $this->produitDAO->create($nom, $prix, $categorie, $actif);
            echo json_encode(['success' => true, 'id' => $id]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur serveur.']);
        }
        exit;
    }

    /** Modifier un produit (AJAX JSON) */
    public function updateProduct(): void
    {
        ob_clean();
        header('Content-Type: application/json');

        $id        = (int)($_GET['id']           ?? 0);
        $data      = json_decode(file_get_contents('php://input'), true);
        $nom       = sanitize($data['nom']       ?? '');
        $prix      = (float)($data['prix']       ?? 0);
        $categorie = sanitize($data['categorie'] ?? 'Général');
        $actif     = (int)($data['actif']        ?? 1);

        if (!$id || empty($nom)) {
            echo json_encode(['success' => false, 'message' => 'Données invalides.']);
            exit;
        }

        try {
            $this->produitDAO->update($id, $nom, $prix, $categorie, $actif);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur de mise à jour.']);
        }
        exit;
    }

    /** Supprimer un produit (AJAX JSON) */
    public function deleteProduct(): void
    {
        ob_clean();
        header('Content-Type: application/json');

        $id = (int)($_GET['id'] ?? 0);

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID invalide.']);
            exit;
        }

        try {
            $this->produitDAO->delete($id);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression.']);
        }
        exit;
    }

    /** Basculer actif / inactif (AJAX JSON) */
    public function toggleProduct(): void
    {
        ob_clean();
        header('Content-Type: application/json');

        $id    = (int)($_GET['id'] ?? 0);
        $data  = json_decode(file_get_contents('php://input'), true);
        $actif = (int)($data['actif'] ?? 0);

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID invalide.']);
            exit;
        }

        try {
            $this->produitDAO->setActif($id, $actif);
            echo json_encode(['success' => true, 'actif' => $actif]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur serveur.']);
        }
        exit;
    }
}