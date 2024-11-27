<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\TasksModel;
use App\Abstracts\Controller;
use PDOException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EditTaskController extends Controller
{
    private TasksModel $model;

    public function __construct(TasksModel $model)
    {
        $this->model = $model;
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try
        {
            $task = $request->getParsedBody();
            $task['id'] = (int)$args['id'];
            $this->model->editTask($task);
            return $response->withHeader('Location', '/tasks')->withStatus(303);
        } catch (PDOException $e)
        {
            return $this->respondWithJson($response, ['message' => $e->getMessage()], 500);
        }
}
}