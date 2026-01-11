<?php

require_once __DIR__ . '/Repository.php';
require_once __DIR__ . '/../models/Cave.php';

class CaveRepository extends Repository
{
    private static ?CaveRepository $instance = null;

    protected function __construct()
    {
        parent::__construct();
    }

    public static function getInstance(): CaveRepository
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function create(Cave $cave): ?Cave
    {
        try {
            $query = "INSERT INTO caves 
                      (name, description, region_id, latitude, longitude, 
                       map_image_path, author_id, status, created_at, updated_at) 
                      VALUES 
                      (:name, :description, :region_id, :latitude, :longitude, 
                       :map_image_path, :author_id, :status, NOW(), NOW()) 
                      RETURNING id";

            $result = $this->fetchOne($query, [
                ':name' => $cave->getName(),
                ':description' => $cave->getDescription(),
                ':region_id' => $cave->getRegionId(),
                ':latitude' => $cave->getLatitude(),
                ':longitude' => $cave->getLongitude(),
                ':map_image_path' => $cave->getMapImagePath(),
                ':author_id' => $cave->getAuthorId(),
                ':status' => $cave->getStatus()
            ]);

            if ($result) {
                $cave->setId($result['id']);
                return $cave;
            }

            return null;
        } catch (PDOException $e) {
            error_log("Cave creation failed: " . $e->getMessage());
            return null;
        }
    }

    public function findById(int $id): ?Cave
    {
        $query = "SELECT c.*, r.name as region_name 
                  FROM caves c 
                  LEFT JOIN regions r ON c.region_id = r.id 
                  WHERE c.id = :id";
        $result = $this->fetchOne($query, [':id' => $id]);
        
        if (!$result) return null;

        $cave = $this->mapToCave($result);
        if (method_exists($cave, 'setRegionName')) {
            $cave->setRegionName($result['region_name'] ?? 'Nieznany');
        }
        
        return $cave;
    }

    public function findByStatus(string $status, int $limit = 20): array
    {
        $query = "SELECT * FROM caves 
                  WHERE status = :status 
                  ORDER BY created_at DESC
                  LIMIT :limit";

        $results = $this->fetchAll($query, [
            ':status' => $status,
            ':limit' => $limit
        ]);

        return array_map([$this, 'mapToCave'], $results);
    }

    public function isVisited(int $userId, int $caveId): bool {
        $query = "SELECT 1 FROM public.cave_visits WHERE user_id = :u AND cave_id = :c";
        $result = $this->fetchOne($query, [':u' => $userId, ':c' => $caveId]);
        return $result !== null;
    }

    public function markAsVisited(int $userId, int $caveId): bool {
        $query = "INSERT INTO public.cave_visits (user_id, cave_id) 
              VALUES (:u, :c) 
              ON CONFLICT (user_id, cave_id) DO NOTHING";
        return $this->execute($query, [':u' => $userId, ':c' => $caveId]);
    }

    public function unmarkAsVisited(int $userId, int $caveId): bool {
        $query = "DELETE FROM public.cave_visits WHERE user_id = :u AND cave_id = :c";
        return $this->execute($query, [':u' => $userId, ':c' => $caveId]);
    }

    public function setCaveRating(int $userId, int $caveId, int $score): bool {
        $query = "
            INSERT INTO public.cave_ratings (user_id, cave_id, difficulty_score)
            VALUES (:user_id, :cave_id, :score)
            ON CONFLICT (user_id, cave_id) 
            DO UPDATE SET difficulty_score = EXCLUDED.difficulty_score
        ";

        return $this->execute($query, [
            ':user_id' => $userId,
            ':cave_id' => $caveId,
            ':score' => $score
        ]);
    }

    public function getUserRating(int $userId, int $caveId): ?int 
    {
        $query = "SELECT difficulty_score 
                  FROM public.cave_ratings 
                  WHERE user_id = :u AND cave_id = :c";
                  
        $result = $this->fetchOne($query, [
            ':u' => $userId, 
            ':c' => $caveId
        ]);

        return $result ? (int)$result['difficulty_score'] : null;
    }

    public function updateStatus(int $caveId, string $status, int $approvedBy): bool
    {
        $query = "UPDATE caves 
                  SET status = :status, 
                      approved_by = :approved_by,
                      updated_at = NOW()
                  WHERE id = :id";

        return $this->execute($query, [
            ':status' => $status,
            ':approved_by' => $approvedBy,
            ':id' => $caveId
        ]);
    }

    public function getRegions(): array
    {
        $query = "SELECT id, name FROM regions ORDER BY name ASC";
        return $this->fetchAll($query);
    }

    public function getStatuses(): array
    {
        $query = "
            SELECT enumlabel AS name
            FROM pg_enum
            JOIN pg_type ON pg_enum.enumtypid = pg_type.oid
            WHERE pg_type.typname = 'cave_status_type'
            ORDER BY enumsortorder
        ";

        return $this->fetchAll($query);
    }


    public function getAdminCaves(string $status = 'PENDING', int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;

        $query = "
            SELECT 
                c.*, 
                r.name as region_name, 
                u.username as author_name 
            FROM caves c
            LEFT JOIN regions r ON c.region_id = r.id
            LEFT JOIN users u ON c.author_id = u.id
            ORDER BY c.created_at DESC
            LIMIT :limit OFFSET :offset
        ";

        $results = $this->fetchAll($query, [
            ':limit' => $limit,
            ':offset' => $offset
        ]);

        return array_map(function($row) {
            $cave = $this->mapToCave($row);
            if (method_exists($cave, 'setRegionName')) {
                $cave->setRegionName($row['region_name'] ?? 'Nieznany');
            }
            if (method_exists($cave, 'setAuthorName')) {
                $cave->setAuthorName($row['author_name'] ?? 'Anonim');
            }
            return $cave;
        }, $results);
    }

    public function getTotalCavesCount(): int
    {
        $query = "SELECT COUNT(*) FROM caves";
        return $this->count($query);
    }

    public function countByStatus(string $status): int
    {
        return $this->count(
            "SELECT COUNT(*) FROM caves WHERE status = :status",
            [':status' => $status]
        );
    }

    private function mapToCave(array $row): Cave
    {
        return new Cave(
            $row['name'],
            $row['description'],
            $row['region_id'],
            $row['author_id'],
            $row['id'] ?? null,
            $row['latitude'] ?? null,
            $row['longitude'] ?? null,
            $row['map_image_path'] ?? null,
            $row['difficulty_avg'] ?? null,
            $row['status'] ?? 'PENDING'
        );
    }
}