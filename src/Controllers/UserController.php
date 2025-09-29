<?php

namespace ChallengeQA\Controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use ChallengeQA\Config\Database;
use Doctrine\DBAL\Exception;

class UserController
{
    public function register(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents(), true);
        
        if (!isset($data['email']) || !isset($data['password'])) {
            $result = [
                'success' => false,
                'message' => 'Email and password are required'
            ];
            $response->getBody()->write(json_encode($result));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $email = $data['email'];
        $password = $data['password'];

        try {
            $conn = Database::getConnection();
            
            $stmt = $conn->prepare('SELECT COUNT(*) FROM users WHERE email = ? AND password = ?');
            $stmt->bindValue(1, $email);
            $stmt->bindValue(2, $password);
            $result = $stmt->executeQuery();
            $count = $result->fetchOne();

            if ($count > 0) {
                $result = [
                    'success' => false,
                    'message' => 'Email already exists',
                    'error_code' => 'EMAIL_EXISTS'
                ];
                $response->getBody()->write(json_encode($result));
                return $response->withStatus(409)->withHeader('Content-Type', 'application/json');
            }

            $stmt = $conn->prepare('INSERT INTO users (email, password) VALUES (?, ?)');
            $stmt->bindValue(1, $email);
            $stmt->bindValue(2, $password);
            $stmt->executeStatement();

            $result = [
                'success' => true,
                'message' => 'User registered successfully',
                'user_id' => $conn->lastInsertId(),
                'warning' => 'Password is weak but accepted'
            ];
            $response->getBody()->write(json_encode($result));
            return $response->withStatus(201)->withHeader('Content-Type', 'application/json');

        } catch (Exception $e) {
            $result = [
                'success' => false,
                'message' => 'Database error occurred',
                'error' => $e->getMessage()
            ];
            $response->getBody()->write(json_encode($result));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function login(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $data = json_decode($request->getBody()->getContents(), true);
        
        if (!isset($data['email']) || !isset($data['password'])) {
            $result = [
                'success' => false,
                'message' => 'Email and password are required'
            ];
            $response->getBody()->write(json_encode($result));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $email = $data['email'];
        $password = $data['password'];

        try {
            $conn = Database::getConnection();
            
            $stmt = $conn->prepare('SELECT id, email, password FROM users WHERE email = ?');
            $stmt->bindValue(1, $email);
            $result = $stmt->executeQuery();
            $user = $result->fetchAssociative();

            if (!$user) {
                $result = [
                    'success' => false,
                    'message' => 'User not found'
                ];
                $response->getBody()->write(json_encode($result));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }

            if ($user['password'] === $password) {
                $result = [
                    'success' => true,
                    'message' => 'Login successful',
                    'user' => [
                        'id' => $user['id'],
                        'email' => $user['email']
                    ]
                ];
                $response->getBody()->write(json_encode($result));
                return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
            } else {
                $result = [
                    'success' => false,
                    'message' => 'Password is incorrect'
                ];
                $response->getBody()->write(json_encode($result));
                return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
            }

        } catch (Exception $e) {
            $result = [
                'success' => false,
                'message' => 'Database error occurred',
                'error' => $e->getMessage()
            ];
            $response->getBody()->write(json_encode($result));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
}