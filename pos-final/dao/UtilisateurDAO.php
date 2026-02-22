<?php
class UtilisateurDAO extends BaseDAO
{
    public function findByLogin(string $login): array|false
    {
        return $this->fetchOne(
            'SELECT * FROM utilisateurs WHERE login = :login LIMIT 1',
            [':login' => $login]
        );
    }

    public function findById(int $id): array|false
    {
        return $this->fetchOne(
            'SELECT id, login, role, created_at FROM utilisateurs WHERE id = :id',
            [':id' => $id]
        );
    }

    public function findAll(): array
    {
        return $this->fetchAll('SELECT id, login, role, created_at FROM utilisateurs ORDER BY id');
    }

    public function create(string $login, string $password, string $role = 'caissier'): bool
    {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $this->execute(
            'INSERT INTO utilisateurs (login, password_hash, role) VALUES (:login, :hash, :role)',
            [':login' => $login, ':hash' => $hash, ':role' => $role]
        );
        return true;
    }
}
