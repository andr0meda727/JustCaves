<?php

require_once __DIR__ . '/../../Database.php';

abstract class Repository
{
    protected PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }

    protected function getConnection(): PDO
    {
        return $this->connection;
    }

    protected function fetchAll(string $query, array $params = []): array
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function fetchOne(string $query, array $params = []): ?array
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    protected function execute(string $query, array $params = []): bool
    {
        try {
            $stmt = $this->connection->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Query execution failed: " . $e->getMessage());
            return false;
        }
    }

    protected function exists(string $query, array $params = []): bool
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    protected function count(string $query, array $params = []): int
    {
        $stmt = $this->connection->prepare($query);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }
}