<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\TasksModel;
use App\Abstracts\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddTaskController extends Controller
{
    private TasksModel $model;

    public function __construct(TasksModel $model)
    {
        $this->model = $model;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $newTask = $request->getParsedBody();
            $insertedId = $this->model->addTask($newTask);

            if ($insertedId)
            {
                return $this->respondWithJson($response, ['message' => 'Task created', 'id' => $insertedId]);
            }
            else
            {
                return $this->respondWithJson($response, ['message' => 'Failed to create task'], 500);
            }

        } catch (\Exception $exception) {
            return $this->respondWithJson($response, ['message' => $exception->getMessage()], 500);
        }
    }
}