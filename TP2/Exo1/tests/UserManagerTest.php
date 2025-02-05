<?php

require_once __DIR__ . '/../UserManager.php';
use PHPUnit\Framework\TestCase;

class UserManagerTest extends TestCase
{
    private UserManager $userManager;

    // Vider bdd avant chaque test (sinon test pas pertinent)
    protected function setUp(): void
    {
        $this->userManager = new UserManager();
        $this->userManager->removeAllUsers();
    }

    // Vérifie qu’un utilisateur est bien ajouté à la base de données.
    public function testAddUser(): void
    {
        $name = 'Test User';
        $email = 'testuser@example.com';

        $this->userManager->addUser($name, $email);

        $users = $this->userManager->getUsers();
        $this->assertCount(1, $users);
        $this->assertEquals($name, $users[0]['name']);
        $this->assertEquals($email, $users[0]['email']);
    }

    // Vérifie que les modifications d’un utilisateur sont correctement enregistrées.
    public function testUpdateUser(): void
    {
        $name = 'Updated User';
        $email = 'updateduser@example.com';

        // add user
        $this->userManager->addUser('Original Name', 'original@example.com');
        $users = $this->userManager->getUsers();
        $userId = $users[0]['id'];  // ID de l'utilisateur ajouté

        // msj user
        $this->userManager->updateUser($userId, $name, $email);

        // verif user
        $updatedUser = $this->userManager->getUser($userId);
        $this->assertEquals($name, $updatedUser['name']);
        $this->assertEquals($email, $updatedUser['email']);
    }

    // Vérifie qu’un utilisateur est bien supprimé.
    public function testRemoveUser(): void
    {
        // add utilisateur
        $this->userManager->addUser('To Be Removed', 'toremove@example.com');
        $users = $this->userManager->getUsers();
        $userId = $users[0]['id'];

        // Supp user
        $this->userManager->removeUser($userId);

        // Vérif
        $this->expectException(Exception::class);
        $this->userManager->getUser($userId);
    }

    //Vérifie que la récupération de la liste des utilisateurs fonctionne.
    public function testGetUsers(): void
    {
        $this->userManager->addUser('User 1', 'user1@example.com');
        $this->userManager->addUser('User 2', 'user2@example.com');

        $users = $this->userManager->getUsers();
        $this->assertCount(2, $users);
    }

    // Vérifie qu’une tentative de modification d’un livre inexistant génère une erreur.
    public function testInvalidUpdateThrowsException(): void
    {
        $this->expectException(Exception::class);
        $this->userManager->updateUser(9999, 'Nonexistent User', 'nonexistent@example.com');
    }

    //Vérifie qu’une tentative de suppression d’un livre inexistant génère une erreur.
    public function testInvalidDeleteThrowsException(): void
    {
        $this->expectException(Exception::class);
        $this->userManager->removeUser(9999);
    }

    // Nettoyer après les tests sinon ben y'aura cohésion si on veut refaire le test
    protected function tearDown(): void
    {
        $this->userManager->removeAllUsers();
    }
}
