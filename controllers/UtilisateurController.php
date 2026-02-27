<?php
declare(strict_types=1);

class UtilisateurController
{
    private UtilisateurDAO $dao;

    public function __construct()
    {
        $this->dao = new UtilisateurDAO();
    }

    /**
     * Affiche la liste des utilisateurs et le formulaire d'ajout
     */
    public function index(): void
    {
        if (!isAdmin()) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Accès refusé.'];
            redirect('index.php?page=dashboard');
        }

        $users = $this->dao->findAll();
        $pageTitle = 'Gestion des Utilisateurs';
        require VIEW_PATH . '/utilisateurs/index.php';
    }

    /**
     * Enregistre un nouvel utilisateur
     */
    public function store(): void
    {
        if (!isAdmin()) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Accès refusé.'];
            redirect('index.php?page=dashboard');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php?page=utilisateurs');
        }

        $login    = sanitize($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';
        $role     = sanitize($_POST['role'] ?? 'caissier');

        if (empty($login) || empty($password)) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Veuillez remplir tous les champs.'];
            redirect('index.php?page=utilisateurs');
        }

        // Vérifier si le login existe déjà
        if ($this->dao->findByLogin($login)) {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Ce login est déjà utilisé.'];
            redirect('index.php?page=utilisateurs');
        }

        if ($this->dao->create($login, $password, $role)) {
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Utilisateur ajouté avec succès.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Une erreur est survenue lors de l\'ajout.'];
        }

        redirect('index.php?page=utilisateurs');
    }
}
