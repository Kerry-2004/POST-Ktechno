<?php
declare(strict_types=1);

class ClientDAO extends BaseDAO
{
    /**
     * Recherche des clients par nom, téléphone ou ID
     */
    public function search(string $query): array
    {
        $sql = "SELECT * FROM clients 
                WHERE nom LIKE :q1 
                OR telephone LIKE :q2 
                OR id = :id 
                ORDER BY nom ASC LIMIT 10";
        
        $params = [
            ':q1' => '%' . $query . '%',
            ':q2' => '%' . $query . '%',
            ':id' => is_numeric($query) ? (int)$query : 0
        ];

        return $this->fetchAll($sql, $params);
    }

    /**
     * Crée un nouveau client
     */
    public function create(string $nom, ?string $telephone = null, ?string $email = null, ?string $adresse = null): int
    {
        $this->execute(
            "INSERT INTO clients (nom, telephone, email, adresse) VALUES (:nom, :tel, :email, :adr)",
            [
                ':nom'   => $nom,
                ':tel'   => $telephone,
                ':email' => $email,
                ':adr'   => $adresse
            ]
        );
        return (int)$this->lastInsertId();
    }

    public function findAll(): array
    {
        return $this->fetchAll("SELECT * FROM clients ORDER BY created_at DESC");
    }

    public function delete(int $id): bool
    {
        $this->execute("DELETE FROM clients WHERE id = :id", [':id' => $id]);
        return true;
    }

    /**
     * Récupère un client par son ID
     */
    public function findById(int $id): array|false
    {
        return $this->fetchOne("SELECT * FROM clients WHERE id = :id", [':id' => $id]);
    }
}
