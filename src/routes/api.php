<?php

use Slim\App;
use ChallengeQA\Controllers\UserController;
use ChallengeQA\Controllers\CalculatorController;

return function (App $app) {
    // User routes
    $app->group('/api/user', function ($group) {
        $group->post('/register', [UserController::class, 'register']);
        $group->post('/login', [UserController::class, 'login']);
    });

    // Calculator routes
    $app->group('/api/calculator', function ($group) {
        $group->post('/simple-interest', [CalculatorController::class, 'simpleInterest']);
        $group->post('/compound-interest', [CalculatorController::class, 'compoundInterest']);
        $group->post('/installment', [CalculatorController::class, 'installmentSimulation']);
    });

    // API documentation endpoint
    $app->get('/', function ($request, $response) {
        $docs = [
            'title' => 'Challenge QA API',
            'version' => '1.0.0',
            'description' => 'API for QA testing with intentional bugs',
            'endpoints' => [
                [
                    'method' => 'POST',
                    'path' => '/api/user/register',
                    'description' => 'Register a new user',
                    'parameters' => [
                        'email' => 'string (required)',
                        'password' => 'string (required)'
                    ]
                ],
                [
                    'method' => 'POST',
                    'path' => '/api/user/login',
                    'description' => 'Authenticate user',
                    'parameters' => [
                        'email' => 'string (required)',
                        'password' => 'string (required)'
                    ]
                ],
                [
                    'method' => 'POST',
                    'path' => '/api/calculator/simple-interest',
                    'description' => 'Calculate simple interest',
                    'parameters' => [
                        'principal' => 'number (required)',
                        'rate' => 'number (required)',
                        'time' => 'number (required)'
                    ]
                ],
                [
                    'method' => 'POST',
                    'path' => '/api/calculator/compound-interest',
                    'description' => 'Calculate compound interest',
                    'parameters' => [
                        'principal' => 'number (required)',
                        'rate' => 'number (required)',
                        'time' => 'number (required)',
                        'compounding_frequency' => 'number (optional, default: 12)'
                    ]
                ],
                [
                    'method' => 'POST',
                    'path' => '/api/calculator/installment',
                    'description' => 'Simulate installment payments',
                    'parameters' => [
                        'principal' => 'number (required)',
                        'rate' => 'number (required)',
                        'installments' => 'number (required)'
                    ]
                ]
            ]
        ];
        
        $response->getBody()->write(json_encode($docs, JSON_PRETTY_PRINT));
        return $response->withHeader('Content-Type', 'application/json');
    });
};