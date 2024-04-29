<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/hello', function (Request $request, Response $response) {
    $response->getBody()->write('Hello hello');
    return $response;
});

// Define the POST route
$app->post('/escape', function (Request $request, Response $response) {
    // Get the JSON data from the request body
    $data = $request->getBody()->getContents();

    // Check if data is not empty and is a valid JSON
    if (!empty($data)) {
        // Escape all double quotes in the JSON string
        $escapedString = str_replace('"', '\"', $data);
        
        // Send the escaped string as response
        $response->getBody()->write($escapedString);
        return $response->withHeader('Content-Type', 'application/json');
    } else {
        // If the data is empty, return an error response
        $response->getBody()->write(json_encode(['error' => 'Empty JSON data']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }
});

// Define the GET route for current timestamp
$app->get('/timestamp', function (Request $request, Response $response, $args) {
    // Get the current timestamp
    $timestamp = time();



    $response->getBody()->write($timestamp);
    return $response;
    
    // Send the timestamp as response
    //$response->getBody()->write(json_encode(['timestamp' => $timestamp]));
    //return $response->withHeader('Content-Type', 'application/json');
});

$app->run();

