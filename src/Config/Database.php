<?php

namespace ChallengeQA\Config;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class Database
{
    private static ?Connection $connection = null;

    public static function getConnection(): Connection
    {
        if (self::$connection === null) {
            $connectionParams = [
                'dbname' => $_ENV['DB_DATABASE'] ?? 'challenge_qa',
                'user' => $_ENV['DB_USERNAME'] ?? 'qa_user',
                'password' => $_ENV['DB_PASSWORD'] ?? 'qa_password',
                'host' => $_ENV['DB_HOST'] ?? 'mysql',
                'port' => $_ENV['DB_PORT'] ?? 3306,
                'driver' => 'pdo_mysql',
                'charset' => 'utf8mb4',
            ];

            self::$connection = DriverManager::getConnection($connectionParams);
        }

        return self::$connection;
    }

    public static function closeConnection(): void
    {
        if (self::$connection !== null) {
            self::$connection->close();
            self::$connection = null;
        }
    }
}