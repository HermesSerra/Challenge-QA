<?php

declare(strict_types=1);

namespace ChallengeQA\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250921205112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Criação das tabelas users e calculation_logs';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_email (email)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        $this->addSql("
            CREATE TABLE IF NOT EXISTS calculation_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                calculation_type ENUM('simple_interest', 'compound_interest', 'installment') NOT NULL,
                principal_amount DECIMAL(15,2) NOT NULL,
                interest_rate DECIMAL(5,4) NOT NULL,
                time_period INT NOT NULL,
                installments INT NULL,
                result DECIMAL(15,2) NOT NULL,
                request_data JSON,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DROP TABLE IF EXISTS calculation_logs");
        $this->addSql("DROP TABLE IF EXISTS users");
    }
}
