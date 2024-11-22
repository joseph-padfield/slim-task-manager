<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\TasksModel;
use App\Abstracts\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;

class TasksController extends Controller
{
    private TasksModel $model;
    private PhpRenderer $renderer;

    public function __construct(TasksModel $model, PhpRenderer $renderer)
    {
        $this->model = $model;
        $this->renderer = $renderer;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            $tasks = $this->model->getTasks();

            if (empty($tasks)) {
                return $this->respondWithJson($response, ['message' => 'No tasks found.'], 200);
            }
            return $this->renderer->render($response, 'tasks.phtml', ['tasks' => $tasks]);

        } catch (\PDOException $exception) {
            return $this->respondWithJson($response, ['message' => $exception->getMessage()], 500, 500);
        }
    }
}