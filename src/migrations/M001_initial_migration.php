<?php

namespace App\migrations;

use Dulannadeeja\Mvc\Application;
use Dulannadeeja\Mvc\Database;

class M001_initial_migration
{
    private Database $db;
    private \PDO $pdo;
    public function __construct()
    {
        $this->db= Application::$app->db;
        $this->pdo = $this->db->pdo;
    }
    public function up():void {
        $statement = $this->pdo->prepare(
            "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL,
                firstName VARCHAR(255) NOT NULL,
                lastName VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                status TINYINT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=INNODB;"
        );

        $statement->execute();
    }

    public function down():void {
        $statement = $this->pdo->prepare("DROP TABLE users;");
        $statement->execute();
    }

}