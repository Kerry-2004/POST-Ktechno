<?php
declare(strict_types=1);

session_start();

define('ROOT_PATH', __DIR__);
define('VIEW_PATH', ROOT_PATH . '/views');

require ROOT_PATH . '/config/config.php';
require ROOT_PATH . '/config/Database.php';
require ROOT_PATH . '/dao/BaseDAO.php';
require ROOT_PATH . '/dao/UtilisateurDAO.php';
require ROOT_PATH . '/dao/CommandeDAO.php';
require ROOT_PATH . '/dao/ProduitDAO.php';
require ROOT_PATH . '/dao/CategorieDAO.php';
require ROOT_PATH . '/dao/SessionCaisseDAO.php';
require ROOT_PATH . '/dao/RapportDAO.php';
require ROOT_PATH . '/dao/ClientDAO.php';
require ROOT_PATH . '/controllers/AuthController.php';
require ROOT_PATH . '/controllers/CommandeController.php';
require ROOT_PATH . '/controllers/CategorieController.php';
require ROOT_PATH . '/controllers/SessionCaisseController.php';
require ROOT_PATH . '/controllers/RapportController.php';
require ROOT_PATH . '/controllers/UtilisateurController.php';
require ROOT_PATH . '/controllers/ClientController.php';
require ROOT_PATH . '/helpers.php';


$page   = sanitize($_GET['page']   ?? 'login');
$action = sanitize($_GET['action'] ?? '');

$auth          = new AuthController();
$commande      = new CommandeController();
$categorie     = new CategorieController();
$sessionCaisse = new SessionCaisseController();
$rapport       = new RapportController();
$utilisateur   = new UtilisateurController();
$client        = new ClientController();

// ── Routes publiques ──────────────────────────────────────
if ($page === 'login') {
    $action === 'post' ? $auth->login() : $auth->showLogin();
    exit;
}
if ($page === 'logout') {
    $auth->logout();
    exit;
}

// Toutes les routes suivantes = auth obligatoire
AuthController::requireAuth();

switch ($page) {

    // ── Tableau de bord ──────────────────────────────────
    case 'dashboard':
        $commande->dashboard();
        break;

    // ── POS (caisse) ──────────────────────────────────────
    case 'pos':
        $commande->showPos();
        break;

    // ── Historique ────────────────────────────────────────
    case 'history':
        $commande->history();
        break;

    // ── Crédits / Versements ──────────────────────────────
    case 'credits':
        $commande->credits();
        break;

    // ── Commandes CRUD + email ────────────────────────────
    case 'commande':
        match ($action) {
            'store'      => $commande->store(),
            'edit'       => $commande->edit(),
            'update'     => $commande->update(),
            'cancel'     => $commande->cancel(),
            'ticket'     => $commande->ticket(),
            'sendEmail'  => $commande->sendEmail(),
            'versement'  => $commande->storeVersement(),
            default      => redirect('index.php?page=dashboard'),
        };
        break;

    // ── Produits CRUD ─────────────────────────────────────
    case 'produits':
        switch ($action) {
            case 'store':       $commande->storeProduct();   break;
            case 'update':      $commande->updateProduct();  break;
            case 'delete':      $commande->deleteProduct();  break;
            case 'toggle':      $commande->toggleProduct();  break;
            case 'updateStock': $commande->updateStock();    break;
            default:            $commande->produits();       break;
        }
        break;

    // ── Catégories CRUD ───────────────────────────────────
    case 'categories':
        switch ($action) {
            case 'store':  $categorie->store();  break;
            case 'update': $categorie->update(); break;
            case 'delete': $categorie->delete(); break;
            default:       $categorie->index();  break;
        }
        break;

    // ── Session caisse ────────────────────────────────────
    case 'caisse':
        switch ($action) {
            case 'ouvrir': $sessionCaisse->ouvrir(); break;
            case 'fermer': $sessionCaisse->fermer(); break;
            default:       $sessionCaisse->index();  break;
        }
        break;

    // ── Rapports ─────────────────────────────────────────
    case 'rapports':
        switch ($action) {
            case 'exportCsv':          $rapport->exportCsv();            break;
            case 'exportHistoriqueCsv':$rapport->exportHistoriqueCsv();  break;
            default:                   $rapport->index();                 break;
        }
        break;

    // ── Utilisateurs (Admin) ─────────────────────────────
    case 'utilisateurs':
        switch ($action) {
            case 'store':  $utilisateur->store();  break;
            default:       $utilisateur->index();  break;
        }
        break;

    // ── Clients (AJAX search/store) ─────────────────────
    case 'clients':
        $action === 'search' ? $client->search() :
        ($action === 'store'  ? $client->store() :
        ($action === 'delete' ? $client->delete() : $client->index()));
        break;

    default:
        redirect('index.php?page=dashboard');
}