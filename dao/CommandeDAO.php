<?php
class CommandeDAO extends BaseDAO
{
    /** Crée une commande + ses lignes dans une transaction */
    public function create(string $clientName, array $lignes, int $userId = 0, string $paymentMethod = 'especes', float $amountPaid = 0, float $discount = 0, ?int $clientId = null): int
    {
        $this->db->beginTransaction();
        try {
            $subtotal = array_reduce($lignes, fn($c, $l) => $c + $l['quantite'] * $l['prix_unitaire'], 0);
            $total    = max(0, $subtotal - $discount);

            $this->execute(
                'INSERT INTO commandes (client_name, total_amount, status, user_id, payment_method, amount_paid, discount, client_id)
                 VALUES (:name, :total, :status, :uid, :pm, :ap, :disc, :cid)',
                [
                    ':name'   => $clientName,
                    ':total'  => $total,
                    ':status' => 'validee',
                    ':uid'    => $userId ?: null,
                    ':pm'     => $paymentMethod,
                    ':ap'     => $amountPaid,
                    ':disc'   => $discount,
                    ':cid'    => $clientId,
                ]
            );
            $commandeId = (int) $this->lastInsertId();

            foreach ($lignes as $l) {
                $this->execute(
                    'INSERT INTO ligne_commandes (commande_id, produit_nom, quantite, prix_unitaire)
                     VALUES (:cid, :nom, :qte, :prix)',
                    [
                        ':cid'  => $commandeId,
                        ':nom'  => $l['produit_nom'],
                        ':qte'  => (int)$l['quantite'],
                        ':prix' => (float)$l['prix_unitaire'],
                    ]
                );
            }

            $this->db->commit();
            return $commandeId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Liste toutes les commandes avec filtres optionnels
     * Supporte maintenant : date_debut, date_fin, status, search (client name ou id)
     */
    public function findAll(
        string $dateDebut = '',
        string $dateFin   = '',
        string $status    = '',
        string $search    = '',
        int    $page      = 1,
        int    $perPage   = 25
    ): array {
        $sql    = "SELECT c.*, u.login as caissier FROM commandes c LEFT JOIN utilisateurs u ON u.id = c.user_id WHERE 1=1";
        $params = [];

        if ($dateDebut) { $sql .= ' AND DATE(c.created_at) >= :debut'; $params[':debut']  = $dateDebut; }
        if ($dateFin)   { $sql .= ' AND DATE(c.created_at) <= :fin';   $params[':fin']    = $dateFin;   }
        if ($status)    { $sql .= ' AND c.status = :status';            $params[':status'] = $status;    }
        if ($search) {
            if (is_numeric($search)) {
                $sql .= ' AND c.id = :sid';
                $params[':sid'] = (int)$search;
            } else {
                $sql .= ' AND c.client_name LIKE :sname';
                $params[':sname'] = '%' . $search . '%';
            }
        }

        $sql .= ' ORDER BY c.created_at DESC';

        // Pagination
        $offset         = ($page - 1) * $perPage;
        $sql           .= ' LIMIT :limit OFFSET :offset';
        $params[':limit']  = $perPage;
        $params[':offset'] = $offset;

        return $this->fetchAll($sql, $params);
    }

    /** Compte le total de commandes selon les mêmes filtres (pour pagination) */
    public function countAll(
        string $dateDebut = '',
        string $dateFin   = '',
        string $status    = '',
        string $search    = ''
    ): int {
        $sql    = "SELECT COUNT(*) as nb FROM commandes c WHERE 1=1";
        $params = [];
        if ($dateDebut) { $sql .= ' AND DATE(c.created_at) >= :debut'; $params[':debut']  = $dateDebut; }
        if ($dateFin)   { $sql .= ' AND DATE(c.created_at) <= :fin';   $params[':fin']    = $dateFin;   }
        if ($status)    { $sql .= ' AND c.status = :status';            $params[':status'] = $status;    }
        if ($search) {
            if (is_numeric($search)) {
                $sql .= ' AND c.id = :sid';
                $params[':sid'] = (int)$search;
            } else {
                $sql .= ' AND c.client_name LIKE :sname';
                $params[':sname'] = '%' . $search . '%';
            }
        }
        $r = $this->fetchOne($sql, $params);
        return (int)($r['nb'] ?? 0);
    }

    /** Retourne une commande avec ses lignes */
    public function findById(int $id): array|false
    {
        $commande = $this->fetchOne(
            "SELECT c.*, u.login as caissier FROM commandes c
             LEFT JOIN utilisateurs u ON u.id = c.user_id
             WHERE c.id = :id",
            [':id' => $id]
        );
        if (!$commande) return false;
        $commande['lignes'] = $this->getLignes($id);
        return $commande;
    }

    /** Met à jour client + lignes (recalcul total) */
    public function update(int $id, string $clientName, array $lignes, float $discount = 0, ?int $clientId = null): bool
    {
        $this->db->beginTransaction();
        try {
            $subtotal = array_reduce($lignes, fn($c, $l) => $c + $l['quantite'] * $l['prix_unitaire'], 0);
            $total    = max(0, $subtotal - $discount);

            $this->execute(
                "UPDATE commandes SET client_name=:name, total_amount=:total, discount=:disc, client_id=:cid, updated_at=NOW()
                 WHERE id=:id AND status != 'annulee'",
                [':name' => $clientName, ':total' => $total, ':disc' => $discount, ':cid' => $clientId, ':id' => $id]
            );
            $this->execute('DELETE FROM ligne_commandes WHERE commande_id = :id', [':id' => $id]);

            foreach ($lignes as $l) {
                $this->execute(
                    'INSERT INTO ligne_commandes (commande_id, produit_nom, quantite, prix_unitaire)
                     VALUES (:cid, :nom, :qte, :prix)',
                    [
                        ':cid'  => $id,
                        ':nom'  => $l['produit_nom'],
                        ':qte'  => (int)$l['quantite'],
                        ':prix' => (float)$l['prix_unitaire'],
                    ]
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

    /** Export : toutes les commandes (sans pagination) pour CSV/PDF */
    public function findAllForExport(
        string $dateDebut = '',
        string $dateFin   = '',
        string $status    = '',
        string $search    = ''
    ): array {
        $sql    = "SELECT c.*, u.login as caissier FROM commandes c LEFT JOIN utilisateurs u ON u.id = c.user_id WHERE 1=1";
        $params = [];
        if ($dateDebut) { $sql .= ' AND DATE(c.created_at) >= :debut'; $params[':debut']  = $dateDebut; }
        if ($dateFin)   { $sql .= ' AND DATE(c.created_at) <= :fin';   $params[':fin']    = $dateFin;   }
        if ($status)    { $sql .= ' AND c.status = :status';            $params[':status'] = $status;    }
        if ($search) {
            if (is_numeric($search)) {
                $sql .= ' AND c.id = :sid';
                $params[':sid'] = (int)$search;
            } else {
                $sql .= ' AND c.client_name LIKE :sname';
                $params[':sname'] = '%' . $search . '%';
            }
        }
        $sql .= ' ORDER BY c.created_at DESC';
        return $this->fetchAll($sql, $params);
    }

    /** Récupère toutes les commandes payées à crédit */
    public function findCredits(): array
    {
        return $this->fetchAll(
            "SELECT c.*, u.login as caissier FROM commandes c
             LEFT JOIN utilisateurs u ON u.id = c.user_id
             WHERE c.payment_method = 'credit' AND c.status = 'validee'
             ORDER BY c.created_at DESC"
        );
    }

    /** Récupère l'historique des versements d'une commande */
    public function getVersements(int $commandeId): array
    {
        return $this->fetchAll(
            "SELECT v.*, u.login as caissier FROM versements v
             LEFT JOIN utilisateurs u ON u.id = v.user_id
             WHERE v.commande_id = :cid
             ORDER BY v.date_versement DESC",
            [':cid' => $commandeId]
        );
    }

    /** Ajoute un versement et met à jour le montant payé */
    public function addVersement(int $commandeId, float $montant, int $userId): bool
    {
        $this->db->beginTransaction();
        try {
            // Ajouter ligne dans versements
            $this->execute(
                "INSERT INTO versements (commande_id, user_id, montant) VALUES (:cid, :uid, :montant)",
                [':cid' => $commandeId, ':uid' => $userId ?: null, ':montant' => $montant]
            );

            // Maj amount_paid de la commande
            $this->execute(
                "UPDATE commandes SET amount_paid = amount_paid + :montant WHERE id = :id",
                [':montant' => $montant, ':id' => $commandeId]
            );

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
