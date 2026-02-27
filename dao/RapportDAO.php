<?php
class RapportDAO extends BaseDAO
{
    /** Stats CA par jour sur les N derniers jours */
    public function getCaParJour(int $jours = 7): array
    {
        return $this->fetchAll(
            "SELECT DATE(created_at) as jour,
                    COUNT(*) as nb_ventes,
                    COALESCE(SUM(total_amount),0) as ca
             FROM commandes
             WHERE status='validee'
               AND created_at >= DATE_SUB(CURDATE(), INTERVAL :j DAY)
             GROUP BY DATE(created_at)
             ORDER BY jour ASC",
            [':j' => $jours]
        );
    }

    /** Top N produits les plus vendus (par quantité) */
    public function getTopProduits(int $limit = 10, string $dateDebut = '', string $dateFin = ''): array
    {
        $sql = "SELECT l.produit_nom,
                       SUM(l.quantite) as total_qte,
                       SUM(l.quantite * l.prix_unitaire) as total_ca
                FROM ligne_commandes l
                JOIN commandes c ON c.id = l.commande_id
                WHERE c.status='validee'";
        $params = [];
        if ($dateDebut) { $sql .= ' AND DATE(c.created_at) >= :debut'; $params[':debut'] = $dateDebut; }
        if ($dateFin)   { $sql .= ' AND DATE(c.created_at) <= :fin';   $params[':fin']   = $dateFin;   }
        $sql .= ' GROUP BY l.produit_nom ORDER BY total_qte DESC LIMIT :lim';
        $params[':lim'] = $limit;
        return $this->fetchAll($sql, $params);
    }

    /** Ventes par caissier */
    public function getVentesParCaissier(string $dateDebut = '', string $dateFin = ''): array
    {
        $sql = "SELECT u.login as caissier,
                       COUNT(c.id) as nb_ventes,
                       COALESCE(SUM(c.total_amount),0) as ca_total
                FROM commandes c
                LEFT JOIN utilisateurs u ON u.id = c.user_id
                WHERE c.status='validee'";
        $params = [];
        if ($dateDebut) { $sql .= ' AND DATE(c.created_at) >= :debut'; $params[':debut'] = $dateDebut; }
        if ($dateFin)   { $sql .= ' AND DATE(c.created_at) <= :fin';   $params[':fin']   = $dateFin;   }
        $sql .= ' GROUP BY c.user_id, u.login ORDER BY ca_total DESC';
        return $this->fetchAll($sql, $params);
    }

    /** Stats globales par mode de paiement */
    public function getModesPaiement(string $dateDebut = '', string $dateFin = ''): array
    {
        $sql = "SELECT payment_method,
                       COUNT(*) as nb,
                       COALESCE(SUM(total_amount),0) as total
                FROM commandes WHERE status='validee'";
        $params = [];
        if ($dateDebut) { $sql .= ' AND DATE(created_at) >= :debut'; $params[':debut'] = $dateDebut; }
        if ($dateFin)   { $sql .= ' AND DATE(created_at) <= :fin';   $params[':fin']   = $dateFin;   }
        $sql .= ' GROUP BY payment_method';
        return $this->fetchAll($sql, $params);
    }

    /** Résumé global pour une période */
    public function getResume(string $dateDebut = '', string $dateFin = ''): array
    {
        $sql = "SELECT COUNT(*) as nb_ventes,
                       COALESCE(SUM(total_amount),0) as ca_total,
                       COALESCE(AVG(total_amount),0) as panier_moyen,
                       COALESCE(MAX(total_amount),0) as plus_grosse_vente
                FROM commandes WHERE status='validee'";
        $params = [];
        if ($dateDebut) { $sql .= ' AND DATE(created_at) >= :debut'; $params[':debut'] = $dateDebut; }
        if ($dateFin)   { $sql .= ' AND DATE(created_at) <= :fin';   $params[':fin']   = $dateFin;   }
        return $this->fetchOne($sql, $params) ?: [];
    }

    /** CA par catégorie */
    public function getCaParCategorie(string $dateDebut = '', string $dateFin = ''): array
    {
        $sql = "SELECT p.categorie,
                       SUM(l.quantite) as total_qte,
                       SUM(l.quantite * l.prix_unitaire) as ca
                FROM ligne_commandes l
                JOIN commandes c ON c.id = l.commande_id
                LEFT JOIN produits p ON p.nom = l.produit_nom
                WHERE c.status='validee'";
        $params = [];
        if ($dateDebut) { $sql .= ' AND DATE(c.created_at) >= :debut'; $params[':debut'] = $dateDebut; }
        if ($dateFin)   { $sql .= ' AND DATE(c.created_at) <= :fin';   $params[':fin']   = $dateFin;   }
        $sql .= ' GROUP BY p.categorie ORDER BY ca DESC';
        return $this->fetchAll($sql, $params);
    }
}
