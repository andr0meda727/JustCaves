<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/Cave.php';
require_once __DIR__ . '/../repository/CaveRepository.php';

class CavesController extends AppController {
    const MAX_FILE_SIZE = 1024 * 1024 * 10; // 10MB
    const SUPPORTED_TYPES = ['image/png', 'image/jpeg'];
    const UPLOAD_DIRECTORY = '/app/public/uploads/maps/';

    private CaveRepository $caveRepository;

    public function __construct() {
        $this->caveRepository = CaveRepository::getInstance();
    }


    public function caves() {
        $caves = $this->caveRepository->findByStatus('PENDING'); 

        return $this->render("caves", ['caves' => $caves]);
    }

    public function cave(int $id) {
        session_start();
        $cave = $this->caveRepository->findById($id);

        if (!$cave) {
            include 'public/views/404.html';
            return;
        }

        $isVisited = false;

        if (isset($_SESSION['user_id'])) {
            $isVisited = $this->caveRepository->isVisited($_SESSION['user_id'], $id);
        }

        return $this->render("cave_details", [
            'cave' => $cave, 
            'isVisited' => $isVisited
        ]);
    }

    public function visit(int $caveId) {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Musisz byc zalogowany']);
            return;
        }

        $userId = $_SESSION['user_id'];
        $isVisited = $this->caveRepository->isVisited($userId, $caveId);

        if ($isVisited) {
            $success = $this->caveRepository->unmarkAsVisited($userId, $caveId);
            $status = 'unmarked';
        } else {
            $success = $this->caveRepository->markAsVisited($userId, $caveId);
            $status = 'marked';
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'status' => $status
        ]);
    }

    public function addCave() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }

        if ($this->isGet()) {
            $regions = $this->caveRepository->getRegions();
            return $this->render("addCave", ['regions' => $regions]);
        }

        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $latitude = $_POST['latitude'] ? (float)$_POST['latitude'] : null;
        $longitude = $_POST['longitude'] ? (float)$_POST['longitude'] : null;
        $authorId = $_SESSION['user_id'];
        $regionId = isset($_POST['region_id']) ? (int)$_POST['region_id'] : null;

        if (empty($name) || empty($description) || !$regionId) {
            return $this->render("addCave", ['errors' => ['Wszystkie pola, w tym region, są wymagane']]);
        }

        $imagePath = null;
        if ($this->isPost() && is_uploaded_file($_FILES['map_image']['tmp_name'])) {
            if ($this->validateFile($_FILES['map_image'])) {
                $imagePath = $this->saveFile($_FILES['map_image']);
            } else {
                return $this->render("addCave", ['errors' => ['Nieprawidłowy plik graficzny']]);
            }
        }

        $cave = new Cave($name, $description, $regionId, $authorId);
        $cave->setLatitude($latitude);
        $cave->setLongitude($longitude);
        $cave->setMapImagePath($imagePath);

        if ($this->caveRepository->create($cave)) {
            header('Location: /caves');
            exit();
        }

        return $this->render("addCave", ['errors' => ['Wystąpił błąd podczas zapisu w bazie danych']]);
    }

    private function validateFile(array $file): bool {
        if ($file['size'] > self::MAX_FILE_SIZE) {
            return false;
        }

        if (!isset($file['type']) || !in_array($file['type'], self::SUPPORTED_TYPES)) {
            return false;
        }

        return true;
    }

private function saveFile(array $file): string {
    $name = uniqid('map_') . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    $destination = self::UPLOAD_DIRECTORY . $name;

    if (!is_dir(self::UPLOAD_DIRECTORY)) {
        mkdir(self::UPLOAD_DIRECTORY, 0777, true);
    }

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $name;
    }
    
    throw new Exception("Failed to move uploaded file.");
}
}