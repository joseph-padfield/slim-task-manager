<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\TasksModel;
use App\Abstracts\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TaskController extends Controller
{
    private TasksModel $model;

    public function __construct(TasksModel $model)
    {
        $this->model = $model;
    }

    public function __invoke(Request $request, Response $response, $args): Response
    {
        try {
            $task = $this->model->getTaskById($args['id']);

            if (empty($task)) {
                return $this->respondWithJson($response, ['message' => 'Task not found.'], 200);
            }
            return $this->respondWithJson($response, $task);
        } catch (\PDOException $e) {
            return $this->respondWithJson($response, ['message' => $e->getMessage()]);
        }
    }
}