<?php
require __DIR__ . '/../vendor/autoload.php';

use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use ChallengeQA\Config\Database;
use ChallengeQA\Middleware\LoggingMiddleware;
use ChallengeQA\Middleware\CorsMiddleware;

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

// Create Slim app
$app = AppFactory::create();

// Add error middleware
$errorMiddleware = $app->addErrorMiddleware(true, true, true);

// Add custom middleware
$app->add(new CorsMiddleware());
$app->add(new LoggingMiddleware());

// Add routing middleware
$app->addRoutingMiddleware();

// Load routes
(require __DIR__ . '/../src/routes/api.php')($app);

// Health check endpoint
$app->get('/health', function ($request, $response) {
    $response->getBody()->write(json_encode([
        'status' => 'ok',
        'timestamp' => date('Y-m-d H:i:s'),
        'version' => '1.0.0'
    ]));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();