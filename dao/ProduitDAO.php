<?php
class ProduitDAO extends BaseDAO
{
    /**
     * Tous les produits actifs pour le catalogue POS
     */
    public function findAll(): array
    {
        return $this->fetchAll(
            'SELECT * FROM produits WHERE actif = 1 ORDER BY categorie, nom'
        );
    }

    /**
     * Tous les produits pour la page de gestion (actifs + inactifs)
     */
    public function findAllAdmin(): array
    {
        return $this->fetchAll(
            'SELECT * FROM produits ORDER BY categorie, nom'
        );
    }

    public function findById(int $id): array|false
    {
        return $this->fetchOne(
            'SELECT * FROM produits WHERE id = :id',
            [':id' => $id]
        );
    }

    public function create(string $nom, float $prix, string $categorie, int $actif = 1, int $stock = 0, string $imageUrl = '', string $barcode = ''): int
    {
        $this->execute(
            'INSERT INTO produits (nom, prix, categorie, actif, stock, image_url, barcode)
             VALUES (:nom, :prix, :cat, :actif, :stock, :img, :barcode)',
            [
                ':nom'     => $nom,
                ':prix'    => $prix,
                ':cat'     => $categorie,
                ':actif'   => $actif,
                ':stock'   => $stock,
                ':img'     => $imageUrl ?: null,
                ':barcode' => $barcode ?: null,
            ]
        );
        return (int) $this->lastInsertId();
    }

    public function update(int $id, string $nom, float $prix, string $categorie, int $actif = 1, int $stock = 0, string $imageUrl = '', string $barcode = ''): bool
    {
        $stmt = $this->execute(
            'UPDATE produits SET nom=:nom, prix=:prix, categorie=:cat, actif=:actif,
             stock=:stock, image_url=:img, barcode=:barcode WHERE id=:id',
            [
                ':nom'     => $nom,
                ':prix'    => $prix,
                ':cat'     => $categorie,
                ':actif'   => $actif,
                ':stock'   => $stock,
                ':img'     => $imageUrl ?: null,
                ':barcode' => $barcode ?: null,
                ':id'      => $id,
            ]
        );
        return $stmt->rowCount() > 0;
    }

    /**
     * Décrémente le stock lors d'une vente
     */
    public function decrementerStock(int $produitId, int $quantite): void
    {
        $this->execute(
            'UPDATE produits SET stock = GREATEST(0, stock - :qte) WHERE id = :id',
            [':qte' => $quantite, ':id' => $produitId]
        );
    }

    /**
     * Décrémente le stock par nom de produit (utilisé lors de la validation de commande)
     */
    public function decrementerStockParNom(string $nom, int $quantite): void
    {
        $this->execute(
            'UPDATE produits SET stock = GREATEST(0, stock - :qte) WHERE nom = :nom',
            [':qte' => $quantite, ':nom' => $nom]
        );
    }

    /**
     * Met à jour uniquement le stock
     */
    public function setStock(int $id, int $stock): bool
    {
        $stmt = $this->execute(
            'UPDATE produits SET stock=:stock WHERE id=:id',
            [':stock' => $stock, ':id' => $id]
        );
        return $stmt->rowCount() > 0;
    }

    /**
     * Suppression douce (soft delete)
     */
    public function delete(int $id): bool
    {
        $stmt = $this->execute(
            'UPDATE produits SET actif = 0 WHERE id = :id',
            [':id' => $id]
        );
        return $stmt->rowCount() > 0;
    }

    /**
     * Basculer actif / inactif depuis la page de gestion
     */
    public function setActif(int $id, int $actif): bool
    {
        $stmt = $this->execute(
            'UPDATE produits SET actif = :actif WHERE id = :id',
            [':actif' => $actif, ':id' => $id]
        );
        return $stmt->rowCount() > 0;
    }

    /**
     * Produits en rupture de stock
     */
    public function getEnRupture(): array
    {
        return $this->fetchAll(
            'SELECT * FROM produits WHERE actif=1 AND stock <= 0 ORDER BY nom'
        );
    }

    /**
     * Produits avec stock faible (< seuil)
     */
    public function getStockFaible(int $seuil = 5): array
    {
        return $this->fetchAll(
            'SELECT * FROM produits WHERE actif=1 AND stock > 0 AND stock < :seuil ORDER BY stock ASC',
            [':seuil' => $seuil]
        );
    }
}