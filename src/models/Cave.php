<?php

class Cave {
    private ?int $id;
    private string $name;
    private string $description;
    private int $regionId;
    private ?float $latitude;
    private ?float $longitude;
    private ?string $mapImagePath;
    private ?float $difficultyAvg;
    private int $authorId;
    private string $status;
    private ?string $regionName;

    public function __construct(
        string $name,
        string $description,
        int $regionId,
        int $authorId,
        ?int $id = null,
        ?float $latitude = null,
        ?float $longitude = null,
        ?string $mapImagePath = null,
        ?float $difficultyAvg = null,
        string $status = 'PENDING'
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->regionId = $regionId;
        $this->authorId = $authorId;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->mapImagePath = $mapImagePath;
        $this->difficultyAvg = $difficultyAvg;
        $this->status = $status;
    }

    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getDescription(): string { return $this->description; }
    public function getRegionId(): int { return $this->regionId; }
    public function getAuthorId(): int { return $this->authorId; }
    public function getLatitude(): ?float { return $this->latitude; }
    public function getLongitude(): ?float { return $this->longitude; }
    public function getMapImagePath(): ?string { return $this->mapImagePath; }
    public function getDifficultyAvg(): ?float { return $this->difficultyAvg; }
    public function getStatus(): string { return $this->status; }
    public function getRegionName(): ?string { return $this->regionName; }

    public function setId(int $id): void { $this->id = $id; }
    public function setName(string $name): void { $this->name = $name; }
    public function setDescription(string $description): void { $this->description = $description; }
    public function setRegionId(int $regionId): void { $this->regionId = $regionId; }
    public function setLatitude(?float $latitude): void { $this->latitude = $latitude; }
    public function setLongitude(?float $longitude): void { $this->longitude = $longitude; }
    public function setMapImagePath(?string $mapImagePath): void { $this->mapImagePath = $mapImagePath; }
    public function setStatus(string $status): void { $this->status = $status; }
    public function setRegionName(string $regionName): void { $this->regionName = $regionName; }
}