<?php

declare(strict_types=1);

namespace App\Abstracts;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

abstract class Controller
{
    abstract public function __invoke(Request $request, Response $response, array $args);

    protected function respondWithJson(Response $response, $data, int $statusCode = 200): Response
    {
        $json = json_encode($data);
        if ($json === false) {
            throw new \Exception('Cannot encode JSON.');
        }

        $response->getBody()->write($json);
        return $response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
    }
}