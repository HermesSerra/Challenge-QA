<?php

namespace ChallengeQA\Tests\Unit;

use PHPUnit\Framework\TestCase;
use ChallengeQA\Controllers\CalculatorController;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\ResponseFactory;

class CalculatorControllerTest extends TestCase
{
    private CalculatorController $controller;
    private $requestFactory;
    private $responseFactory;

    protected function setUp(): void
    {
        $this->controller = new CalculatorController();
        $this->requestFactory = new ServerRequestFactory();
        $this->responseFactory = new ResponseFactory();
    }

    public function testSimpleInterestCalculation(): void
    {
        $requestData = [
            'principal' => 1000,
            'rate' => 5,
            'time' => 2
        ];

        $request = $this->requestFactory
            ->createServerRequest('POST', '/api/calculator/simple-interest')
            ->withHeader('Content-Type', 'application/json');
        
        $request->getBody()->write(json_encode($requestData));
        $request->getBody()->rewind();
        $response = $this->responseFactory->createResponse();

        $result = $this->controller->simpleInterest($request, $response);
        
        $this->assertEquals(200, $result->getStatusCode());
        
        $responseBody = json_decode((string) $result->getBody(), true);
        $this->assertTrue($responseBody['success']);
        $this->assertEquals('simple_interest', $responseBody['calculation_type']);
    }

}