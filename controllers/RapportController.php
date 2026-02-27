<?php
class RapportController
{
    private RapportDAO    $rapportDAO;
    private CommandeDAO   $commandeDAO;

    public function __construct()
    {
        $this->rapportDAO  = new RapportDAO();
        $this->commandeDAO = new CommandeDAO();
    }

    /** Page rapports avancés */
    public function index(): void
    {
        AuthController::requireAuth();
        if (!isAdmin()) { redirect('index.php?page=dashboard'); }

        $dateDebut = sanitize($_GET['date_debut'] ?? date('Y-m-01'));
        $dateFin   = sanitize($_GET['date_fin']   ?? date('Y-m-d'));

        $resume          = $this->rapportDAO->getResume($dateDebut, $dateFin);
        $topProduits     = $this->rapportDAO->getTopProduits(10, $dateDebut, $dateFin);
        $parCaissier     = $this->rapportDAO->getVentesParCaissier($dateDebut, $dateFin);
        $modesPaiement   = $this->rapportDAO->getModesPaiement($dateDebut, $dateFin);
        $parCategorie    = $this->rapportDAO->getCaParCategorie($dateDebut, $dateFin);
        $caParJour       = $this->rapportDAO->getCaParJour(30);

        require VIEW_PATH . '/orders/rapports.php';
    }

    /** Export CSV du rapport */
    public function exportCsv(): void
    {
        AuthController::requireAuth();
        if (!isAdmin()) { redirect('index.php?page=dashboard'); }

        $dateDebut = sanitize($_GET['date_debut'] ?? date('Y-m-01'));
        $dateFin   = sanitize($_GET['date_fin']   ?? date('Y-m-d'));
        $commandes = $this->commandeDAO->findAllForExport('', '', '', '');

        // ——— Re-filtre avec les dates ———
        $commandes = $this->commandeDAO->findAllForExport($dateDebut, $dateFin, '', '');

        $filename = 'rapport_' . $dateDebut . '_' . $dateFin . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');

        $out = fopen('php://output', 'w');
        // BOM UTF-8 pour Excel
        fputs($out, "\xEF\xBB\xBF");
        fputcsv($out, ['#', 'Client', 'Caissier', 'Mode paiement', 'Total HTG', 'Remise HTG', 'Statut', 'Date'], ';');

        foreach ($commandes as $c) {
            fputcsv($out, [
                $c['id'],
                $c['client_name'],
                $c['caissier'] ?? 'N/A',
                $c['payment_method'] ?? 'especes',
                number_format((float)$c['total_amount'], 2, ',', ' '),
                number_format((float)($c['discount'] ?? 0), 2, ',', ' '),
                $c['status'],
                $c['created_at'],
            ], ';');
        }
        fclose($out);
        exit;
    }

    /** Export CSV de l'historique commandes (depuis la page historique) */
    public function exportHistoriqueCsv(): void
    {
        AuthController::requireAuth();

        $dateDebut = sanitize($_GET['date_debut'] ?? '');
        $dateFin   = sanitize($_GET['date_fin']   ?? '');
        $status    = sanitize($_GET['status']     ?? '');
        $search    = sanitize($_GET['search']     ?? '');

        $commandes = $this->commandeDAO->findAllForExport($dateDebut, $dateFin, $status, $search);

        $filename  = 'historique_commandes_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');

        $out = fopen('php://output', 'w');
        fputs($out, "\xEF\xBB\xBF");
        fputcsv($out, ['#', 'Client', 'Caissier', 'Paiement', 'Total HTG', 'Remise', 'Statut', 'Date'], ';');

        foreach ($commandes as $c) {
            fputcsv($out, [
                $c['id'],
                $c['client_name'],
                $c['caissier'] ?? 'N/A',
                $c['payment_method'] ?? 'especes',
                number_format((float)$c['total_amount'], 2, ',', ' '),
                number_format((float)($c['discount'] ?? 0), 2, ',', ' '),
                $c['status'],
                $c['created_at'],
            ], ';');
        }
        fclose($out);
        exit;
    }
}
