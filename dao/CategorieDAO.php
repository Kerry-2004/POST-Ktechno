<?php
class CategorieDAO extends BaseDAO
{
    public function findAll(): array
    {
        return $this->fetchAll('SELECT * FROM categories ORDER BY nom');
    }

    public function findById(int $id): array|false
    {
        return $this->fetchOne('SELECT * FROM categories WHERE id = :id', [':id' => $id]);
    }

    public function create(string $nom, string $couleur): int
    {
        $this->execute(
            'INSERT INTO categories (nom, couleur) VALUES (:nom, :couleur)',
            [':nom' => $nom, ':couleur' => $couleur]
        );
        return (int) $this->lastInsertId();
    }

    public function update(int $id, string $nom, string $couleur): bool
    {
        $stmt = $this->execute(
            'UPDATE categories SET nom=:nom, couleur=:couleur WHERE id=:id',
            [':nom' => $nom, ':couleur' => $couleur, ':id' => $id]
        );
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id): bool
    {
        // Reset products of this category to "Général" before deleting
        $cat = $this->fetchOne('SELECT nom FROM categories WHERE id=:id', [':id' => $id]);
        if ($cat) {
            $this->execute(
                "UPDATE produits SET categorie='Général' WHERE categorie=:nom",
                [':nom' => $cat['nom']]
            );
        }
        $stmt = $this->execute('DELETE FROM categories WHERE id=:id', [':id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function exists(string $nom, int $excludeId = 0): bool
    {
        $r = $this->fetchOne(
            'SELECT id FROM categories WHERE nom=:nom AND id != :excl',
            [':nom' => $nom, ':excl' => $excludeId]
        );
        return (bool)$r;
    }
}
