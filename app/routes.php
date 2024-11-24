<?php
declare(strict_types=1);

use App\Controllers\TasksController;
use App\Controllers\TaskController;
use App\Controllers\AddTaskController;
use App\Controllers\DeleteTaskController;
use Slim\App;
use Slim\Views\PhpRenderer;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/', function ($request, $response, $args) use ($container) {
        $renderer = $container->get(PhpRenderer::class);
        return $renderer->render($response, "index.php", $args);
    });

    $app->get('/tasks', TasksController::class);
    $app->get('/tasks/{id}', TaskController::class);
    $app->post('/tasks', AddTaskController::class);
    $app->post('/tasks/{id}', DeleteTaskController::class);
    //put

};
