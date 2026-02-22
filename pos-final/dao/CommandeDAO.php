<?php
class CommandeDAO extends BaseDAO
{
    /** Crée une commande + ses lignes dans une transaction */
    public function create(string $clientName, array $lignes): int
    {
        $this->db->beginTransaction();
        try {
            $total = array_reduce($lignes, fn($c, $l) => $c + $l['quantite'] * $l['prix_unitaire'], 0);

            $this->execute(
                'INSERT INTO commandes (client_name, total_amount, status) VALUES (:name, :total, :status)',
                [':name' => $clientName, ':total' => $total, ':status' => 'validee']
            );
            $commandeId = (int) $this->lastInsertId();

            foreach ($lignes as $l) {
                $this->execute(
                    'INSERT INTO ligne_commandes (commande_id, produit_nom, quantite, prix_unitaire)
                     VALUES (:cid, :nom, :qte, :prix)',
                    [':cid' => $commandeId, ':nom' => $l['produit_nom'],
                     ':qte' => (int)$l['quantite'], ':prix' => (float)$l['prix_unitaire']]
                );
            }

            $this->db->commit();
            return $commandeId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /** Liste toutes les commandes avec filtres optionnels */
    public function findAll(string $dateDebut = '', string $dateFin = '', string $status = ''): array
    {
        $sql = 'SELECT * FROM commandes WHERE 1=1';
        $params = [];

        if ($dateDebut) { $sql .= ' AND DATE(created_at) >= :debut'; $params[':debut'] = $dateDebut; }
        if ($dateFin)   { $sql .= ' AND DATE(created_at) <= :fin';   $params[':fin']   = $dateFin; }
        if ($status)    { $sql .= ' AND status = :status';            $params[':status'] = $status; }

        $sql .= ' ORDER BY created_at DESC';
        return $this->fetchAll($sql, $params);
    }

    /** Retourne une commande avec ses lignes */
    public function findById(int $id): array|false
    {
        $commande = $this->fetchOne('SELECT * FROM commandes WHERE id = :id', [':id' => $id]);
        if (!$commande) return false;
        $commande['lignes'] = $this->getLignes($id);
        return $commande;
    }

    /** Met à jour client + lignes (recalcul total) */
    public function update(int $id, string $clientName, array $lignes): bool
    {
        $this->db->beginTransaction();
        try {
            $total = array_reduce($lignes, fn($c, $l) => $c + $l['quantite'] * $l['prix_unitaire'], 0);

            $this->execute(
                "UPDATE commandes SET client_name=:name, total_amount=:total, updated_at=NOW()
                 WHERE id=:id AND status != 'annulee'",
                [':name' => $clientName, ':total' => $total, ':id' => $id]
            );
            $this->execute('DELETE FROM ligne_commandes WHERE commande_id = :id', [':id' => $id]);

            foreach ($lignes as $l) {
                $this->execute(
                    'INSERT INTO ligne_commandes (commande_id, produit_nom, quantite, prix_unitaire)
                     VALUES (:cid, :nom, :qte, :prix)',
                    [':cid' => $id, ':nom' => $l['produit_nom'],
                     ':qte' => (int)$l['quantite'], ':prix' => (float)$l['prix_unitaire']]
                );
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /** Annulation logique */
    public function annuler(int $id): bool
    {
        $stmt = $this->execute(
            "UPDATE commandes SET status='annulee', updated_at=NOW() WHERE id=:id",
            [':id' => $id]
        );
        return $stmt->rowCount() > 0;
    }

    public function getLignes(int $commandeId): array
    {
        return $this->fetchAll(
            'SELECT * FROM ligne_commandes WHERE commande_id = :id',
            [':id' => $commandeId]
        );
    }

    /** Stats tableau de bord */
    public function getStats(): array
    {
        $today = $this->fetchOne(
            "SELECT COUNT(*) as nb, COALESCE(SUM(total_amount),0) as total
             FROM commandes WHERE DATE(created_at)=CURDATE() AND status='validee'"
        );
        $month = $this->fetchOne(
            "SELECT COUNT(*) as nb, COALESCE(SUM(total_amount),0) as total
             FROM commandes WHERE MONTH(created_at)=MONTH(NOW()) AND YEAR(created_at)=YEAR(NOW()) AND status='validee'"
        );
        return ['today' => $today, 'month' => $month];
    }
}
