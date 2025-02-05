<?php
class UserManager {
    private PDO $db;

    public function __construct() {
        // Connexion à la base de données sur WAMP
        $dsn = "mysql:host=localhost;dbname=user_management;charset=utf8";
        $username = "root"; // Utilisateur par défaut de WAMP
        $password = ""; // Mot de passe vide par défaut
        try {
            $this->db = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            // Gestion des erreurs de connexion
            die("Erreur de connexion à la base de données : " . $e->getMessage());
        }
    }

    public function addUser(string $name, string $email): void {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Email invalide.");
        }

        $stmt = $this->db->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
        $stmt->execute(['name' => $name, 'email' => $email]);
    }

    public function removeUser(int $id): void {
        // Vérification si l'utilisateur existe
        $user = $this->getUser($id); // Si l'utilisateur n'existe pas, une exception sera lancée ici

        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    public function getUsers(): array {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll();
    }

    public function getUser(int $id): array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        if (!$user) throw new Exception("Utilisateur introuvable.");
        return $user;
    }

    public function updateUser(int $id, string $name, string $email): void {
        // Vérification si l'utilisateur existe
        $user = $this->getUser($id); // Si l'utilisateur n'existe pas, une exception sera lancée ici

        $stmt = $this->db->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id");
        $stmt->execute(['id' => $id, 'name' => $name, 'email' => $email]);
    }

    public function removeAllUsers()
    {
        // Supprimer tous les utilisateurs de la base
        $stmt = $this->db->query("DELETE FROM users");
        $stmt->execute();
    }

}
?>
