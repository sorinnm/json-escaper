<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->get('/timestamp', function (Request $request, Response $response) {
        // Get the current timestamp
        $timestamp = time();

        # Send the timestamp as response
        $response->getBody()->write(json_encode(['timestamp' => $timestamp]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->post('/json-escaper', function (Request $request, Response $response) {
        // Get the JSON data from the request body
        $data = $request->getBody()->getContents();

        // Check if data is not empty and is a valid JSON
        if (!empty($data)) {
            // Escape all double quotes in the JSON string
	    $escapedString = str_replace('"', '\"', $data);
 	    trim($escapedString, '[');
            trim($escapedString, ']');

	    // Send the escaped string as response
	    $response->getBody()->write(json_encode(['data' => $escapedString]));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            // If the data is empty, return an error response
            $response->getBody()->write(json_encode(['error' => 'Empty JSON data']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
};
