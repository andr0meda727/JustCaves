<?php

require_once __DIR__ . '/Repository.php';

class CommentRepository extends Repository {
    private static ?CommentRepository $instance = null;

    protected function __construct()
    {
        parent::__construct();
    }

    public static function getInstance(): CommentRepository
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getCommentsByCaveId(int $caveId): array {
        $query = "SELECT c.*, u.username 
                  FROM public.comments c 
                  JOIN public.users u ON c.author_id = u.id 
                  WHERE c.cave_id = :caveId 
                  ORDER BY c.created_at DESC";
        
        return $this->fetchAll($query, [':caveId' => $caveId]);
    }

    public function addComment(int $userId, int $caveId, string $content): bool {
        $query = "INSERT INTO public.comments (cave_id, author_id, content, created_at) 
                  VALUES (:caveId, :authorId, :content, NOW())";
        
        return $this->execute($query, [
            ':caveId' => $caveId,
            ':authorId' => $userId,
            ':content' => $content
        ]);
    }
}