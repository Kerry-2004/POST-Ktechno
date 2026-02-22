<?php
class AuthController
{
    private UtilisateurDAO $dao;

    public function __construct()
    {
        $this->dao = new UtilisateurDAO();
    }

    public function showLogin(): void
    {
        if (self::isLoggedIn()) redirect('index.php?page=dashboard');
        require VIEW_PATH . '/auth/login.php';
    }

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php?page=login');

        $login    = sanitize($_POST['login'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($login) || empty($password)) {
            $loginError = 'Veuillez remplir tous les champs.';
            require VIEW_PATH . '/auth/login.php';
            return;
        }

        $utilisateur = $this->dao->findByLogin($login);

        if ($utilisateur && password_verify($password, $utilisateur['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id']    = $utilisateur['id'];
            $_SESSION['user_login'] = $utilisateur['login'];
            $_SESSION['user_role']  = $utilisateur['role'];
            $_SESSION['logged_at']  = time();
            redirect('index.php?page=dashboard');
        } else {
            sleep(1); // Ralentir le brute-force
            $loginError = 'Identifiants incorrects.';
            require VIEW_PATH . '/auth/login.php';
        }
    }

    public function logout(): void
    {
        session_destroy();
        redirect('index.php?page=login');
    }

    public static function isLoggedIn(): bool
    {
        if (empty($_SESSION['user_id'])) return false;
        if (time() - ($_SESSION['logged_at'] ?? 0) > SESSION_LIFETIME) {
            session_destroy();
            return false;
        }
        $_SESSION['logged_at'] = time();
        return true;
    }

    public static function requireAuth(): void
    {
        if (!self::isLoggedIn()) redirect('index.php?page=login');
    }
}
