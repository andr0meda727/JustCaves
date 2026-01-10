<?php

require_once 'AppController.php';
require_once __DIR__ . '/../models/Cave.php';
require_once __DIR__ . '/../repository/CaveRepository.php';
require_once __DIR__ . '/../repository/CommentRepository.php';


class CavesController extends AppController {
    const MAX_FILE_SIZE = 1024 * 1024 * 10; // 10MB
    const SUPPORTED_TYPES = ['image/png', 'image/jpeg'];
    const UPLOAD_DIRECTORY = '/app/public/uploads/maps/';

    private CaveRepository $caveRepository;
    private CommentRepository $commentRepository;

    public function __construct() {
        $this->caveRepository = CaveRepository::getInstance();
        $this->commentRepository = CommentRepository::getInstance();
    }

    public function caves() {
        $caves = $this->caveRepository->findByStatus('PENDING'); // approved

        return $this->render("caves", ['caves' => $caves]);
    }

    public function cave(int $caveId) {
        $cave = $this->caveRepository->findById($caveId);

        if (!$cave) {
            include 'public/views/404.html';
            return;
        }

        $isVisited = false;
        $userCaveRating = null;
        $isLoggedIn = isset($_SESSION['user_id']);

        if ($isLoggedIn) {
            $isVisited = $this->caveRepository->isVisited($_SESSION['user_id'], $caveId);
            $userCaveRating = $this->caveRepository->getUserRating($_SESSION['user_id'], $caveId);
        }

        $comments = $this->commentRepository->getCommentsByCaveId($caveId);

        return $this->render("cave_details", [
            'cave' => $cave, 
            'isVisited' => $isVisited,
            'comments' => $comments,
            'rating' => $userCaveRating
        ]);
    }

    public function addComment() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            return;
        }

        $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
        if ($contentType === "application/json") {
            $content = trim(file_get_contents("php://input"));
            $decoded = json_decode($content, true);

            $caveId = (int)$decoded['caveId'];
            $text = htmlspecialchars($decoded['content']);

            if (!empty($text)) {
                $success = $this->commentRepository->addComment($_SESSION['user_id'], $caveId, $text);
                echo json_encode(['success' => $success, 'username' => $_SESSION['username'] ?? 'Ty']);
                return;
            }
        }
        http_response_code(400);
    }

    public function rateCave() {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Musisz być zalogowany']);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);
        $caveId = (int)$data['caveId'];
        $score = (int)$data['score'];

        if ($score < 1 || $score > 10) {
            http_response_code(400);
            return;
        }

        $success = $this->caveRepository->setCaveRating($_SESSION['user_id'], $caveId, $score);
        echo json_encode(['success' => $success]);
    }

    public function visit(int $caveId) {
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