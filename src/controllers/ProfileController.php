<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/CaveRepository.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class ProfileController extends AppController {
    private CaveRepository $caveRepository;
    private UserRepository $userRepository;

    public function __construct() {
        $this->caveRepository = CaveRepository::getInstance();
        $this->userRepository = UserRepository::getInstance();
    }

    public function profile() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userRepository->findById($userId);
        $visitedCaves = $this->caveRepository->getUserVisitedCaves($userId);

        return $this->render('profile/profile', [
            'user' => $user,
            'visitedCaves' => $visitedCaves
        ]);
    }
}