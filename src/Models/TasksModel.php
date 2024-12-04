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
            $task = $query->fetch(PDO::FETCH_ASSOC);
            return $task ? $task : [];
        }
        catch (PDOException $e)
        {
            error_log("Error fetching task: " . $e->getMessage());
            return [];
        }
    }

    public function addTask(array $newTask)
    {
        $current_time = date("Y-m-d H:i:s");
        $sql = 'INSERT INTO tasks (title, due_date, created_at) VALUES (:title, :dueDate, :createdAt)';
        try
        {
            $query = $this->db->prepare($sql);
            $query->bindValue(':title', $newTask['title'], PDO::PARAM_STR);
            $query->bindValue(':createdAt', $current_time, PDO::PARAM_STR);

            if (isset($newTask['due-date']))
            {
                $query->bindValue(':dueDate', $newTask['due_date'], PDO::PARAM_STR);
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

    public function editTask($task)
    {
        $current_time = date("Y-m-d H:i:s");
        $sql = 'UPDATE tasks SET `title` = :title, 
                 `description` = :description,
                 updated_at = :updatedAt
             WHERE `id` = :id';
        try
        {
            $query = $this->db->prepare($sql);
            $query->bindValue(':id', $task['id'], PDO::PARAM_INT);
            $query->bindValue(':title', $task['title']);
            $query->bindValue(':description', $task['description']);
            $query->bindValue(':updatedAt', $current_time);
            return $query->execute();
        } catch (PDOException $e)
        {
            error_log("Error editing task: " . $e->getMessage());
            return false;
        }
    }
}