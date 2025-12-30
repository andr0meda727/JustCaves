<?php

require_once __DIR__ . '/Repository.php';
require_once __DIR__ . '/../models/User.php';

class UserRepository extends Repository
{
    private static ?UserRepository $instance = null;

    protected function __construct()
    {
        parent::__construct();
    }

    public static function getInstance(): UserRepository
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function create(User $user): ?User
    {
        try {
            $query = "INSERT INTO users (username, email, password_hash, role_id, created_at) 
                      VALUES (:username, :email, :password_hash, :role_id, NOW()) 
                      RETURNING id, created_at";

            $result = $this->fetchOne($query, [
                ':username' => $user->getUsername(),
                ':email' => $user->getEmail(),
                ':password_hash' => $user->getPasswordHash(),
                ':role_id' => $user->getRoleId()
            ]);

            if ($result) {
                $user->setId($result['id']);
                return $user;
            }

            return null;
        } catch (PDOException $e) {
            error_log("User creation failed: " . $e->getMessage());
            return null;
        }
    }

    public function findByEmail(string $email): ?User
    {
        $query = "SELECT id, username, email, password_hash, role_id, created_at 
                  FROM users 
                  WHERE email = :email";

        $result = $this->fetchOne($query, [':email' => $email]);

        return $result ? $this->mapToUser($result) : null;
    }

    public function findByUsername(string $username): ?User
    {
        $query = "SELECT id, username, email, password_hash, role_id, created_at 
                  FROM users 
                  WHERE username = :username";

        $result = $this->fetchOne($query, [':username' => $username]);

        return $result ? $this->mapToUser($result) : null;
    }

    public function emailExists(string $email): bool
    {
        return $this->exists(
            "SELECT COUNT(*) FROM users WHERE email = :email",
            [':email' => $email]
        );
    }

    public function usernameExists(string $username): bool
    {
        return $this->exists(
            "SELECT COUNT(*) FROM users WHERE username = :username",
            [':username' => $username]
        );
    }

    public function update(User $user): bool
    {
        $query = "UPDATE users 
                  SET username = :username, 
                      email = :email, 
                      password_hash = :password_hash,
                      role_id = :role_id
                  WHERE id = :id";

        return $this->execute($query, [
            ':username' => $user->getUsername(),
            ':email' => $user->getEmail(),
            ':password_hash' => $user->getPasswordHash(),
            ':role_id' => $user->getRoleId(),
            ':id' => $user->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        return $this->execute(
            "DELETE FROM users WHERE id = :id",
            [':id' => $id]
        );
    }


    private function mapToUser(array $row): User
    {
        return new User(
            $row['username'],
            $row['email'],
            $row['password_hash'],
            $row['role_id'],
            $row['id'],
            $row['created_at']
        );
    }
}