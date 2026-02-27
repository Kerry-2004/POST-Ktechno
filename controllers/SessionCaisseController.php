<?php
class SessionCaisseController
{
    private SessionCaisseDAO $dao;

    public function __construct()
    {
        $this->dao = new SessionCaisseDAO();
    }

    /** Page principale — ouverture/fermeture caisse */
    public function index(): void
    {
        AuthController::requireAuth();
        $userId        = (int)($_SESSION['user_id'] ?? 0);
        $sessionActive = $this->dao->getSessionActive($userId);
        $sessionJour   = $this->dao->getSessionDuJour();
        $historique    = $this->dao->findAll();
        $caEncaisse    = $sessionActive ? $this->dao->getCaEncaisse((int)$sessionActive['id']) : 0;
        require VIEW_PATH . '/orders/caisse.php';
    }

    /** Ouvrir une session (AJAX JSON) */
    public function ouvrir(): void
    {
        ob_clean();
        header('Content-Type: application/json');
        $userId = (int)($_SESSION['user_id'] ?? 0);
        $data   = json_decode(file_get_contents('php://input'), true);
        $solde  = (float)($data['solde_ouverture'] ?? 0);
        $notes  = sanitize($data['notes'] ?? '');

        // Vérifier si déjà une session ouverte
        $existing = $this->dao->getSessionActive($userId);
        if ($existing) {
            echo json_encode(['success' => false, 'message' => 'Une session est déjà ouverte.']);
            exit;
        }

        try {
            $id = $this->dao->ouvrir($userId, $solde, $notes);
            echo json_encode(['success' => true, 'session_id' => $id, 'solde_ouverture' => $solde]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur serveur.']);
        }
        exit;
    }

    /** Fermer une session (AJAX JSON) */
    public function fermer(): void
    {
        ob_clean();
        header('Content-Type: application/json');
        $data          = json_decode(file_get_contents('php://input'), true);
        $sessionId     = (int)($data['session_id']     ?? 0);
        $soldeFermeture = (float)($data['solde_fermeture'] ?? 0);

        if (!$sessionId) {
            echo json_encode(['success' => false, 'message' => 'Session introuvable.']);
            exit;
        }
        try {
            $this->dao->fermer($sessionId, $soldeFermeture);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la fermeture.']);
        }
        exit;
    }
}
