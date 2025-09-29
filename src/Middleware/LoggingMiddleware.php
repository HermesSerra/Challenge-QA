<?php

namespace ChallengeQA\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LoggingMiddleware implements MiddlewareInterface
{
    private Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger('api');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/api.log', Logger::DEBUG));
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $startTime = microtime(true);
        
        // Log request
        $requestData = [
            'method' => $request->getMethod(),
            'uri' => (string) $request->getUri(),
            'headers' => $request->getHeaders(),
            'body' => (string) $request->getBody(),
            'timestamp' => date('Y-m-d H:i:s')
        ];

        $this->logger->info('Incoming request', $requestData);

        $response = $handler->handle($request);

        $endTime = microtime(true);
        $duration = ($endTime - $startTime) * 1000; // milliseconds

        // Log response
        $responseData = [
            'status_code' => $response->getStatusCode(),
            'headers' => $response->getHeaders(),
            'body' => (string) $response->getBody(),
            'duration_ms' => round($duration, 2),
            'timestamp' => date('Y-m-d H:i:s')
        ];

        $this->logger->info('Outgoing response', $responseData);

        return $response;
    }
}