<?php
/** Nettoie une chaîne contre les failles XSS */
function sanitize(string $v): string
{
    return htmlspecialchars(trim($v), ENT_QUOTES, 'UTF-8');
}

/** Redirection HTTP */
function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

/** Formate un montant en euros */
function formatPrice(float $amount): string
{
    return number_format($amount, 2, ',', ' ') . ' HTG';
}

/** Formate une date MySQL en français */
function formatDate(string $date, bool $withTime = true): string
{
    $ts = strtotime($date);
    return $withTime ? date('d/m/Y H:i', $ts) : date('d/m/Y', $ts);
}

/** Retourne et vide le message flash */
function getFlash(): ?array
{
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

/** Badge HTML de statut */
function statusBadge(string $status): string
{
    $map = [
        'validee'  => ['Validée',  'badge-success'],
        'en_cours' => ['En cours', 'badge-warning'],
        'annulee'  => ['Annulée',  'badge-danger'],
    ];
    $s = $map[$status] ?? [$status, 'badge-secondary'];
    return '<span class="badge ' . $s[1] . '">' . $s[0] . '</span>';
}

/** Vérifie si l'utilisateur est admin */
function isAdmin(): bool
{
    return ($_SESSION['user_role'] ?? '') === 'admin';
}
