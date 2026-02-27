<?php
class ProduitDAO extends BaseDAO
{
    /**
     * Tous les produits pour le catalogue POS (actifs seulement)
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

    public function create(string $nom, float $prix, string $categorie, int $actif = 1): int
    {
        $this->execute(
            'INSERT INTO produits (nom, prix, categorie, actif) VALUES (:nom, :prix, :cat, :actif)',
            [':nom' => $nom, ':prix' => $prix, ':cat' => $categorie, ':actif' => $actif]
        );
        return (int) $this->lastInsertId();
    }

    public function update(int $id, string $nom, float $prix, string $categorie, int $actif = 1): bool
    {
        $stmt = $this->execute(
            'UPDATE produits SET nom = :nom, prix = :prix, categorie = :cat, actif = :actif WHERE id = :id',
            [':nom' => $nom, ':prix' => $prix, ':cat' => $categorie, ':actif' => $actif, ':id' => $id]
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
}