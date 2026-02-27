<?php
class SessionCaisseDAO extends BaseDAO
{
    /** Ouvre une session caisse */
    public function ouvrir(int $userId, float $soldeOuverture, string $notes = ''): int
    {
        $this->execute(
            'INSERT INTO sessions_caisse (user_id, solde_ouverture, notes) VALUES (:uid, :sol, :notes)',
            [':uid' => $userId, ':sol' => $soldeOuverture, ':notes' => $notes]
        );
        return (int) $this->lastInsertId();
    }

    /** Ferme la session caisse active de l'utilisateur */
    public function fermer(int $sessionId, float $soldeFermeture): bool
    {
        $stmt = $this->execute(
            "UPDATE sessions_caisse SET solde_fermeture=:sf, ferme_a=NOW() WHERE id=:id AND ferme_a IS NULL",
            [':sf' => $soldeFermeture, ':id' => $sessionId]
        );
        return $stmt->rowCount() > 0;
    }

    /** Session active de l'utilisateur */
    public function getSessionActive(int $userId): array|false
    {
        return $this->fetchOne(
            "SELECT s.*, u.login as caissier FROM sessions_caisse s
             LEFT JOIN utilisateurs u ON u.id = s.user_id
             WHERE s.user_id=:uid AND s.ferme_a IS NULL
             ORDER BY s.ouvert_a DESC LIMIT 1",
            [':uid' => $userId]
        );
    }

    /** Session active du jour (tous utilisateurs) */
    public function getSessionDuJour(): array|false
    {
        return $this->fetchOne(
            "SELECT s.*, u.login as caissier FROM sessions_caisse s
             LEFT JOIN utilisateurs u ON u.id = s.user_id
             WHERE DATE(s.ouvert_a)=CURDATE() AND s.ferme_a IS NULL
             ORDER BY s.ouvert_a DESC LIMIT 1"
        );
    }

    /** Historique de toutes les sessions */
    public function findAll(): array
    {
        return $this->fetchAll(
            "SELECT s.*, u.login as caissier FROM sessions_caisse s
             LEFT JOIN utilisateurs u ON u.id = s.user_id
             ORDER BY s.ouvert_a DESC"
        );
    }

    /** CA encaissé depuis l'ouverture de la session */
    public function getCaEncaisse(int $sessionId): float
    {
        $session = $this->fetchOne(
            'SELECT ouvert_a FROM sessions_caisse WHERE id=:id', [':id' => $sessionId]
        );
        if (!$session) return 0;
        $res = $this->fetchOne(
            "SELECT COALESCE(SUM(total_amount),0) as total
             FROM commandes WHERE status='validee' AND created_at >= :ouvert",
            [':ouvert' => $session['ouvert_a']]
        );
        return (float)($res['total'] ?? 0);
    }
}
