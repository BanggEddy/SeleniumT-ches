<?php

use David\TestUnitaire\TaskManager;
use PHPUnit\Framework\TestCase;

class  StockManagerTests extends TestCase
{
    public function testAddTask()
    {
        $taskManager = new TaskManager();
        $taskManager->addTask("tache1");
        $this->assertEquals(["tache1"], $taskManager->getTasks());
    }

    public function testRemoveTask()
    {
        $taskManager = new TaskManager();
        $taskManager->addTask("tache1");
        $taskManager->addTask("tache2");

        $taskManager->removeTask(0);
        $this->assertEquals(["tache2"], $taskManager->getTasks());
    }

    public function testGetTasks()
    {
        $taskManager = new TaskManager();
        $taskManager->addTask("tache1");
        $taskManager->addTask("tache2");

        $this->assertEquals(["tache1", "tache2"], $taskManager->getTasks());
    }

    public function testGetTask()
    {
        $taskManager = new TaskManager();
        $taskManager->addTask("tache1");
        $taskManager->addTask("tache2");

        $this->assertEquals("tache2", $taskManager->getTask(1));
    }

    public function testRemoveInvalidIndexThrowsException()
    {
        $this->expectException(OutOfBoundsException::class);

        $taskManager = new TaskManager();
        $taskManager->removeTask(0);
    }

    public function testGetInvalidIndexThrowsException()
    {
        $this->expectException(OutOfBoundsException::class);

        $taskManager = new TaskManager();
        $taskManager->getTask(0);
    }

    public function testTaskOrderAfterRemoval()
    {
        $taskManager = new TaskManager();
        $taskManager->addTask("tache1");
        $taskManager->addTask("tache2");
        $taskManager->addTask("tache3");

        $taskManager->removeTask(1);

        $this->assertEquals(["tache1", "tache3"], $taskManager->getTasks());
    }
}
