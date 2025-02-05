<?php

namespace src;

use PDO;
use PDOException;
use Exception;
use InvalidArgumentException;

class UserManager
{
    private PDO $db;

    public function __construct()
    {
        $dsn = "mysql:host=localhost;dbname=user_management;charset=utf8";
        $username = "root";
        $password = "";
        try {
            $this->db = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    public function addUser(string $name, string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Email invalide.");
        }

        $stmt = $this->db->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
        $stmt->execute(['name' => $name, 'email' => $email]);
    }

    public function removeUser(int $id): void
    {
        $user = $this->getUser($id);

        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function getUsers(): array
    {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }

    public function getUser(int $id): array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        if (!$user) throw new Exception("Utilisateur introuvable.");
        return $user;
    }

    public function updateUser(int $id, string $name, string $email): void
    {
        $user = $this->getUser($id);

        $stmt = $this->db->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
        $stmt->execute(['id' => $id, 'name' => $name, 'email' => $email]);
    }

    public function removeAllUsers()
    {
        $stmt = $this->db->query("DELETE FROM users");
        $stmt->execute();
    }

    public function assignRole(int $id, string $role): void
    {
        $validRoles = ['admin', 'user', 'editor']; // Liste des rôles valides
        if (!in_array($role, $validRoles)) {
            throw new InvalidArgumentException("Rôle invalide.");
        }

        $stmt = $this->db->prepare("UPDATE users SET role = :role WHERE id = :id");
        $stmt->execute(['id' => $id, 'role' => $role]);
    }

    public function getUserRole(int $id): string
    {
        $stmt = $this->db->prepare("SELECT role FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        if (!$user) {
            throw new Exception("Utilisateur introuvable.");
        }

        return $user['role'];
    }

}

?>
