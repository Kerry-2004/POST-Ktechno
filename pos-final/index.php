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
require ROOT_PATH . '/controllers/AuthController.php';
require ROOT_PATH . '/controllers/CommandeController.php';
require ROOT_PATH . '/helpers.php';

$page   = sanitize($_GET['page']   ?? 'login');
$action = sanitize($_GET['action'] ?? '');

$auth     = new AuthController();
$commande = new CommandeController();

// Routes publiques
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
    case 'dashboard':
        $commande->dashboard();
        break;
    case 'pos':
        $commande->showPos();
        break;
    case 'history':
        $commande->history();
        break;
    case 'commande':
        match ($action) {
            'store'  => $commande->store(),
            'edit'   => $commande->edit(),
            'update' => $commande->update(),
            'cancel' => $commande->cancel(),
            'ticket' => $commande->ticket(),
            default  => redirect('index.php?page=dashboard'),
        };
        break;

    // ── Produits CRUD ──────────────────────────────────
    // ── Produits CRUD ──────────────────────────────────
    case 'produits':
        switch ($action) {
            case 'store':
                $commande->storeProduct();
                break;
            case 'update':
                $commande->updateProduct();
                break;
            case 'delete':
                $commande->deleteProduct();
                break;
            case 'toggle':
                $commande->toggleProduct();
                break;
            default:
                $commande->produits();
                break;
        }
        break;
    // ───────────────────────────────────────────────────

    default:
        redirect('index.php?page=dashboard');
}