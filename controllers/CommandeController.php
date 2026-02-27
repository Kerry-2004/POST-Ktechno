<?php
class CommandeController
{
    private CommandeDAO  $commandeDAO;
    private ProduitDAO   $produitDAO;
    private CategorieDAO $categorieDAO;

    public function __construct()
    {
        $this->commandeDAO  = new CommandeDAO();
        $this->produitDAO   = new ProduitDAO();
        $this->categorieDAO = new CategorieDAO();
    }

    // ════════════════════════════════════════════════
    //  DASHBOARD
    // ════════════════════════════════════════════════

    public function dashboard(): void
    {
        $rapportDAO      = new RapportDAO();
        $stats           = $this->commandeDAO->getStats();
        $commandes       = $this->commandeDAO->findAll();
        $topProduits     = $rapportDAO->getTopProduits(5, date('Y-m-01'), date('Y-m-d'));
        $parCaissier     = $rapportDAO->getVentesParCaissier(date('Y-m-01'), date('Y-m-d'));
        $caParCategorie  = $rapportDAO->getCaParCategorie(date('Y-m-01'), date('Y-m-d'));
        $caParJour       = $rapportDAO->getCaParJour(7);
        $totalProduits   = count($this->produitDAO->findAllAdmin());
        $sessionCaisseDAO = new SessionCaisseDAO();
        $sessionActive   = $sessionCaisseDAO->getSessionDuJour();
        require VIEW_PATH . '/orders/dashboard.php';
    }

    // ════════════════════════════════════════════════
    //  CAISSE (POS)
    // ════════════════════════════════════════════════

    public function showPos(): void
    {
        $produits   = $this->produitDAO->findAll();
        $categories = $this->categorieDAO->findAll();
        // Vérifier session caisse active
        $sessionCaisseDAO = new SessionCaisseDAO();
        $sessionActive    = $sessionCaisseDAO->getSessionActive((int)($_SESSION['user_id'] ?? 0));
        require VIEW_PATH . '/orders/pos.php';
    }

    /** Créer une commande (AJAX JSON) */
    public function store(): void
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents('php://input'), true);

        $clientName    = sanitize($data['client_name'] ?? '');
        $lignes        = $data['lignes']         ?? [];
        $paymentMethod = sanitize($data['payment_method'] ?? 'especes');
        $amountPaid    = (float)($data['amount_paid']    ?? 0);
        $discount      = (float)($data['discount']       ?? 0);
        $userId        = (int)($_SESSION['user_id']      ?? 0);
        $clientId      = isset($data['client_id']) ? (int)$data['client_id'] : null;

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
            $id = $this->commandeDAO->create(
                $clientName, $lignesPropres, $userId, $paymentMethod, $amountPaid, $discount, $clientId
            );

            // Décrémente le stock pour chaque ligne
            foreach ($lignesPropres as $l) {
                $this->produitDAO->decrementerStockParNom($l['produit_nom'], $l['quantite']);
            }

            $commande = $this->commandeDAO->findById($id);

            // Calcul rendu de monnaie
            $rendu = ($paymentMethod === 'especes' && $amountPaid > 0)
                ? max(0, $amountPaid - (float)$commande['total_amount'])
                : 0;

            echo json_encode(['success' => true, 'commande' => $commande, 'rendu' => $rendu]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur serveur.']);
        }
    }

    // ════════════════════════════════════════════════
    //  HISTORIQUE
    // ════════════════════════════════════════════════

    public function history(): void
    {
        $dateDebut = sanitize($_GET['date_debut'] ?? '');
        $dateFin   = sanitize($_GET['date_fin']   ?? '');
        $status    = sanitize($_GET['status']     ?? '');
        $search    = sanitize($_GET['search']     ?? '');
        $page      = max(1, (int)($_GET['p']      ?? 1));
        $perPage   = 25;

        $total     = $this->commandeDAO->countAll($dateDebut, $dateFin, $status, $search);
        $commandes = $this->commandeDAO->findAll($dateDebut, $dateFin, $status, $search, $page, $perPage);
        $pages     = max(1, (int)ceil($total / $perPage));

        require VIEW_PATH . '/orders/history.php';
    }

    // ════════════════════════════════════════════════
    //  EDIT / UPDATE / CANCEL / TICKET
    // ════════════════════════════════════════════════

    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        if (!$id) redirect('index.php?page=history');

        $commande = $this->commandeDAO->findById($id);
        if (!$commande || $commande['status'] === 'annulee') {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Modification impossible.'];
            redirect('index.php?page=history');
        }
        $produits   = $this->produitDAO->findAll();
        $categories = $this->categorieDAO->findAll();
        require VIEW_PATH . '/orders/edit.php';
    }

    public function update(): void
    {
        header('Content-Type: application/json');
        $id   = (int) ($_GET['id'] ?? 0);
        $data = json_decode(file_get_contents('php://input'), true);

        $clientName    = sanitize($data['client_name'] ?? '');
        $lignes        = $data['lignes'] ?? [];
        $discount      = (float)($data['discount'] ?? 0);
        $clientId      = isset($data['client_id']) ? (int)$data['client_id'] : null;
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
            $this->commandeDAO->update($id, $clientName, $lignesPropres, $discount, $clientId);
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
    //  ENVOI EMAIL DU TICKET
    // ════════════════════════════════════════════════

    public function sendEmail(): void
    {
        header('Content-Type: application/json');
        $id   = (int)($_GET['id'] ?? 0);
        $data = json_decode(file_get_contents('php://input'), true);
        $to   = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);

        if (!$id || !$to) {
            echo json_encode(['success' => false, 'message' => 'Données invalides.']);
            return;
        }

        $commande = $this->commandeDAO->findById($id);
        if (!$commande) {
            echo json_encode(['success' => false, 'message' => 'Commande introuvable.']);
            return;
        }

        // Construire le contenu HTML du ticket
        $lignesHtml = '';
        foreach ($commande['lignes'] as $l) {
            $sous = (float)$l['quantite'] * (float)$l['prix_unitaire'];
            $lignesHtml .= "<tr>
                <td style='padding:4px 8px;'>{$l['produit_nom']}</td>
                <td style='padding:4px 8px;text-align:center;'>{$l['quantite']}</td>
                <td style='padding:4px 8px;text-align:right;'>" . number_format((float)$l['prix_unitaire'], 2) . " HTG</td>
                <td style='padding:4px 8px;text-align:right;'>" . number_format($sous, 2) . " HTG</td>
            </tr>";
        }

        $subject = COMPANY_NAME . ' — Ticket #' . $commande['id'];
        $msg     = "
        <html><body style='font-family:Arial,sans-serif;max-width:500px;margin:auto;'>
        <h2 style='color:#453dde;'>" . COMPANY_NAME . "</h2>
        <p style='color:#666;'>" . COMPANY_ADDRESS . " | " . COMPANY_PHONE . "</p>
        <hr>
        <p><strong>Commande #" . $commande['id'] . "</strong> — " . htmlspecialchars($commande['client_name']) . "</p>
        <p>Date : " . date('d/m/Y H:i', strtotime($commande['created_at'])) . "</p>
        <table width='100%' border='1' cellpadding='0' cellspacing='0' style='border-collapse:collapse;font-size:13px;'>
        <thead><tr style='background:#453dde;color:#fff;'>
            <th style='padding:6px 8px;text-align:left;'>Produit</th>
            <th style='padding:6px 8px;'>Qté</th>
            <th style='padding:6px 8px;'>PU</th>
            <th style='padding:6px 8px;'>Sous-total</th>
        </tr></thead><tbody>{$lignesHtml}</tbody>
        </table>
        <p style='text-align:right;font-size:1.2em;font-weight:bold;color:#453dde;margin-top:12px;'>
            TOTAL : " . number_format((float)$commande['total_amount'], 2) . " HTG
        </p>
        <hr>
        <p style='color:#888;font-size:12px;text-align:center;'>" . COMPANY_FOOTER . "</p>
        </body></html>";

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . COMPANY_NAME . " <noreply@" . ($_SERVER['HTTP_HOST'] ?? 'pos.local') . ">\r\n";

        $sent = mail($to, $subject, $msg, $headers);
        echo json_encode(['success' => $sent, 'message' => $sent ? 'Email envoyé !' : 'Échec d\'envoi.']);
    }

    // ════════════════════════════════════════════════
    //  PRODUITS CRUD
    // ════════════════════════════════════════════════

    public function produits(): void
    {
        $produits   = $this->produitDAO->findAllAdmin();
        $categories = $this->categorieDAO->findAll();
        require VIEW_PATH . '/orders/produits.php';
    }

    public function storeProduct(): void
    {
        ob_clean();
        header('Content-Type: application/json');

        // Handle multipart (image upload) or JSON
        if (!empty($_FILES['image'])) {
            $nom       = sanitize($_POST['nom']       ?? '');
            $prix      = (float)($_POST['prix']       ?? 0);
            $categorie = sanitize($_POST['categorie'] ?? 'Général');
            $actif     = (int)($_POST['actif']        ?? 1);
            $stock     = (int)($_POST['stock']        ?? 0);
            $barcode   = sanitize($_POST['barcode']   ?? '');
            $imageUrl  = $this->handleImageUpload($_FILES['image']);
        } else {
            $data      = json_decode(file_get_contents('php://input'), true);
            $nom       = sanitize($data['nom']       ?? '');
            $prix      = (float)($data['prix']       ?? 0);
            $categorie = sanitize($data['categorie'] ?? 'Général');
            $actif     = (int)($data['actif']        ?? 1);
            $stock     = (int)($data['stock']        ?? 0);
            $barcode   = sanitize($data['barcode']   ?? '');
            $imageUrl  = '';
        }

        if (empty($nom)) {
            echo json_encode(['success' => false, 'message' => 'Le nom du produit est requis.']);
            exit;
        }

        try {
            $id = $this->produitDAO->create($nom, $prix, $categorie, $actif, $stock, $imageUrl, $barcode);
            echo json_encode(['success' => true, 'id' => $id]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur serveur.']);
        }
        exit;
    }

    public function updateProduct(): void
    {
        ob_clean();
        header('Content-Type: application/json');

        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID invalide.']);
            exit;
        }

        $existing = $this->produitDAO->findById($id);
        if (!$existing) {
            echo json_encode(['success' => false, 'message' => 'Produit introuvable.']);
            exit;
        }
        $imageUrl = $existing['image_url'] ?? '';

        if (!empty($_FILES['image'])) {
            $nom       = sanitize($_POST['nom']       ?? '');
            $prix      = (float)($_POST['prix']       ?? 0);
            $categorie = sanitize($_POST['categorie'] ?? 'Général');
            $actif     = (int)($_POST['actif']        ?? 1);
            $stock     = (int)($_POST['stock']        ?? 0);
            $barcode   = sanitize($_POST['barcode']   ?? '');
            $uploaded  = $this->handleImageUpload($_FILES['image']);
            if ($uploaded) {
                $imageUrl = $uploaded;
            }
        } else {
            $data      = json_decode(file_get_contents('php://input'), true);
            $nom       = sanitize($data['nom']       ?? '');
            $prix      = (float)($data['prix']       ?? 0);
            $categorie = sanitize($data['categorie'] ?? 'Général');
            $actif     = (int)($data['actif']        ?? 1);
            $stock     = (int)($data['stock']        ?? 0);
            $barcode   = sanitize($data['barcode']   ?? '');
        }

        if (empty($nom)) {
            echo json_encode(['success' => false, 'message' => 'Données invalides.']);
            exit;
        }

        try {
            $this->produitDAO->update($id, $nom, $prix, $categorie, $actif, $stock, $imageUrl, $barcode);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur de mise à jour.']);
        }
        exit;
    }

    public function deleteProduct(): void
    {
        ob_clean();
        header('Content-Type: application/json');
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) { echo json_encode(['success' => false, 'message' => 'ID invalide.']); exit; }
        try {
            $this->produitDAO->delete($id);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression.']);
        }
        exit;
    }

    public function toggleProduct(): void
    {
        ob_clean();
        header('Content-Type: application/json');
        $id   = (int)($_GET['id'] ?? 0);
        $data = json_decode(file_get_contents('php://input'), true);
        $actif = (int)($data['actif'] ?? 0);
        if (!$id) { echo json_encode(['success' => false, 'message' => 'ID invalide.']); exit; }
        try {
            $this->produitDAO->setActif($id, $actif);
            echo json_encode(['success' => true, 'actif' => $actif]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur serveur.']);
        }
        exit;
    }

    /** Met à jour le stock d'un produit (AJAX) */
    public function updateStock(): void
    {
        ob_clean();
        header('Content-Type: application/json');
        $id   = (int)($_GET['id'] ?? 0);
        $data = json_decode(file_get_contents('php://input'), true);
        $stock = (int)($data['stock'] ?? 0);
        if (!$id) { echo json_encode(['success' => false, 'message' => 'ID invalide.']); exit; }
        try {
            $this->produitDAO->setStock($id, max(0, $stock));
            echo json_encode(['success' => true, 'stock' => max(0, $stock)]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Erreur serveur.']);
        }
        exit;
    }

    // ════════════════════════════════════════════════
    //  CREDITS & VERSEMENTS
    // ════════════════════════════════════════════════

    public function credits(): void
    {
        $credits = $this->commandeDAO->findCredits();
        foreach ($credits as &$c) {
            $c['versements'] = $this->commandeDAO->getVersements((int)$c['id']);
        }
        require VIEW_PATH . '/orders/credits.php';
    }

    public function storeVersement(): void
    {
        ob_clean();
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents('php://input'), true);
        $commandeId = (int)($data['commande_id'] ?? 0);
        $montant    = (float)($data['montant'] ?? 0);
        $userId     = (int)($_SESSION['user_id'] ?? 0);

        if (!$commandeId || $montant <= 0) {
            echo json_encode(['success' => false, 'message' => 'Données invalides.']);
            exit;
        }

        $commande = $this->commandeDAO->findById($commandeId);
        if (!$commande || $commande['payment_method'] !== 'credit') {
            echo json_encode(['success' => false, 'message' => 'Commande introuvable ou non éligible.']);
            exit;
        }

        $restant = max(0, (float)$commande['total_amount'] - (float)$commande['amount_paid']);
        if ($montant > $restant) {
            echo json_encode(['success' => false, 'message' => 'Le montant dépasse le reste à payer.']);
            exit;
        }

        $success = $this->commandeDAO->addVersement($commandeId, $montant, $userId);
        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement.']);
        }
        exit;
    }

    // ════════════════════════════════════════════════
    //  UTILITAIRES
    // ════════════════════════════════════════════════

    /** Gère l'upload d'une image produit */
    private function handleImageUpload(array $file): string
    {
        $uploadDir = ROOT_PATH . '/public/uploads/produits/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        if (!in_array($file['type'], $allowed))  return '';
        if ($file['size'] > 2 * 1024 * 1024)     return '';   // 2 Mo max

        $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('prod_', true) . '.' . strtolower($ext);
        $dest     = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return 'public/uploads/produits/' . $filename;
        }
        return '';
    }
}