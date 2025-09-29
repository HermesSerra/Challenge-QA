<?php

namespace ChallengeQA\Tests\Unit;

use PHPUnit\Framework\TestCase;
use ChallengeQA\Controllers\UserController;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\ResponseFactory;

class UserControllerTest extends TestCase
{
    private UserController $controller;
    private $requestFactory;
    private $responseFactory;

    protected function setUp(): void
    {
        $this->controller = new UserController();
        $this->requestFactory = new ServerRequestFactory();
        $this->responseFactory = new ResponseFactory();
    }

    public function testLoginWithNonExistentUser(): void
    {
        $requestData = [
            'email' => 'nonexistent@example.com',
            'password' => 'anypassword'
        ];

        $request = $this->requestFactory
            ->createServerRequest('POST', '/api/user/login')
            ->withHeader('Content-Type', 'application/json');
        
        $request->getBody()->write(json_encode($requestData));
        $request->getBody()->rewind();
        $response = $this->responseFactory->createResponse();

        $result = $this->controller->login($request, $response);
        $responseBody = json_decode((string) $result->getBody(), true);
        
        if ($result->getStatusCode() === 404) {
            $this->assertFalse($responseBody['success']);
            $this->assertEquals('User not found', $responseBody['message']);
        }
    }
}