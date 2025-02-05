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
        $name = 'qspodqds';
        $email = 'qsdpokqd@qopsdkqs.com';

        $this->userManager->addUser($name, $email);

        $users = $this->userManager->getUsers();
        $this->assertCount(1, $users);
        $this->assertEquals($name, $users[0]['name']);
        $this->assertEquals($email, $users[0]['email']);
    }

    // Vérifie que les modifications d’un utilisateur sont correctement enregistrées.
    public function testUpdateUser(): void
    {
        $name = 'qspodk';
        $email = 'sqdokqds@qsdqsd.com';

        // add user
        $this->userManager->addUser('sldqsldk', 'qsdokq@qosdko.com');
        $users = $this->userManager->getUsers();
        $userId = $users[0]['id']; 
        $this->userManager->updateUser($userId, $name, $email);

        $updatedUser = $this->userManager->getUser($userId);
        $this->assertEquals($name, $updatedUser['name']);
        $this->assertEquals($email, $updatedUser['email']);
    }

    // Vérifie qu’un utilisateur est bien supprimé.
    public function testRemoveUser(): void
    {
        $this->userManager->addUser('qsdoqsd', 'qskdqsdi@qsodkqsodp.com');
        $users = $this->userManager->getUsers();
        $userId = $users[0]['id'];
        $this->userManager->removeUser($userId);

        // Vérif
        $this->expectException(Exception::class);
        $this->userManager->getUser($userId);
    }

    //Vérifie que la récupération de la liste des utilisateurs fonctionne.
    public function testGetUsers(): void
    {
        $this->userManager->addUser('user1', 'qsdij@qsdiojqds.com');
        $this->userManager->addUser('utilisateur2', 'qsdpoqdsop@qsdij.com');

        $users = $this->userManager->getUsers();
        $this->assertCount(2, $users);
    }

    // Vérifie qu’une tentative de modification d’un livre inexistant génère une erreur.
    public function testInvalidUpdateThrowsException(): void
    {
        $this->expectException(Exception::class);
        $this->userManager->updateUser(9999, 'qsodkqspodk', 'qsiodqsdod@example.com');
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
