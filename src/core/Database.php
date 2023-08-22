<?php

namespace Dulannadeeja\Mvc;

class Database
{
    public \PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';

        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function createMigrationsTable(): void
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=INNODB;");
    }

    public function getAppliedMigrations(): array
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function applyMigrations(): void
    {
        // Create migrations table if it does not exist
        $this->createMigrationsTable();
        // Get applied migrations
        $appliedMigrations = $this->getAppliedMigrations();

        // Get all migrations
        $migrationsDirectory = Application::$ROOT_DIR . "/migrations/";
        $files = scandir($migrationsDirectory);

        // Get all migrations that have not been applied
        $toApplyMigrations = array_diff($files, $appliedMigrations);

        // Apply migrations
        foreach ($toApplyMigrations as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }

            require_once $migrationsDirectory . $migration;

            // Get File name without extension
            $className = pathinfo($migration, PATHINFO_FILENAME);

            // Build the fully qualified class name
            $fullyQualifiedClassName = '\\App\\migrations\\' . $className;

            $instance = new $fullyQualifiedClassName();

            $this->log("Applying migration $migration");

            $instance->up();

            $this->log("Applied migration $migration");

            $newMigrations[] = $migration;
        }

        // Save migrations that have been applied
        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("All migrations are applied");
        }

    }

    private function saveMigrations(array $newMigrations): void
    {
        $values = implode(',', array_map(fn($m) => "('$m')", $newMigrations));
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $values");
        $statement->execute();
    }

    private function log(string $string): void
    {
        echo '[' . date('Y-m-d H:i:s') . '] - ' . $string . PHP_EOL;
    }

}