<?php

declare(strict_types=1);

namespace App\Models;

use PDO;
use App\Factories\PDOFactory;
use PDOException;
use Psr\Container\ContainerInterface;

class TasksModel
{
    private PDO $db;

    public function __construct(PDOFactory $db, ContainerInterface $container)
    {
        $this->db = $db($container);
    }

    public function getTasks(): array
    {
        $sql = 'SELECT * FROM tasks';
        try
        {
            $query = $this->db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            error_log("Error fetching tasks: " . $e->getMessage());
            return [];
        }
    }

    public function getTaskById($id): array
    {
        $sql = 'SELECT * FROM tasks WHERE id = :id';
        try
        {
            $query = $this->db->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        }
        catch (PDOException $e)
        {
            error_log("Error fetching task: " . $e->getMessage());
            return [];
        }
    }

    public function addTask(array $newTask)
    {
        $sql = 'INSERT INTO tasks (title, description, due_date) VALUES (:title, :description, :dueDate)';
        try
        {
            $query = $this->db->prepare($sql);
            $query->bindValue(':title', $newTask['title'], PDO::PARAM_STR);
            $query->bindValue(':description', $newTask['description'], PDO::PARAM_STR);

            if (isset($newTask['due-date']))
            {
                $query->bindValue(':dueDate', $newTask['due-date'], PDO::PARAM_STR);
            }
            else
            {
                $query->bindValue(':dueDate', null, PDO::PARAM_NULL);
            }

            $query->execute();
            return $this->db->lastInsertId();

        }
        catch (\PDOException $e)
        {
            error_log("Error adding task: " . $e->getMessage());
            return false;
        }
    }

    public function deleteTask($id)
    {
        $sql = 'DELETE FROM tasks WHERE id = :id';
        try
        {
            $query = $this->db->prepare($sql);
            $query->bindValue(':id', $id, PDO::PARAM_INT);
            $query->execute();
            return $query->rowCount();
        }
        catch (\PDOException $e)
        {
            error_log("Error deleting task: " . $e->getMessage());
            return false;
        }
    }
}