<?php 

class User {
    private ?int $id;
    private string $username;
    private string $email;
    private string $passwordHash;
    private int $roleId;
    private ?string $createdAt;


    public function __construct(
        string $username,
        string $email,
        string $passwordHash,
        int $roleId = 1,
        ?int $id = null,
        ?string $createdAt = null
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->roleId = $roleId;
        $this->createdAt = $createdAt;
    }

    public function getId(): ?int {
        return $this->id;
    }

     public function getUsername(): string {
        return $this->username;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPasswordHash(): string {
        return $this->passwordHash;
    }

    public function getRoleId(): int {
        return $this->roleId;
    }

    public function getCreatedAt(): ?string {
        return $this->createdAt;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setUsername(string $username): void {
        $this->username = $username;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function setPasswordHash(string $passwordHash): void {
        $this->passwordHash = $passwordHash;
    }

    public function setRoleId(int $roleId): void {
        $this->roleId = $roleId;
    }
}