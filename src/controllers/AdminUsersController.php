<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';

class AdminUsersController extends AppController
{
    private $userRepository;
    private const USERS_PER_PAGE = 6;

    public function __construct()
    {
        $this->userRepository = UserRepository::getInstance();
    }

    public function users()
    {
        if (!$this->isAdmin()) {
            header('Location: /login');
            exit();
        }

        $searchTerm = $_GET['search'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $users = $this->userRepository->getPaginatedUsers($searchTerm, $page, self::USERS_PER_PAGE);
        $totalUsers = $this->userRepository->getTotalUserCount($searchTerm);
        $totalPages = (int)ceil($totalUsers / self::USERS_PER_PAGE);

        return $this->render('admin/users', [
            'users' => $users,
            'searchTerm' => $searchTerm,
            'pageNumber' => $page,
            'totalPages' => $totalPages,
            'totalResults' => $totalUsers
        ]);
    }

    public function promote(int $id)
    {
        if (!$this->isAdmin()) {
            die("Brak uprawnień");
        }

        if ($this->userRepository->updateRole($id, 2)) {
            header("Location: /admin/users" . ($this->getBackParams()));
        }
    }

    public function deleteUser(int $id)
    {
        if (!$this->isAdmin()) {
            die("Brak uprawnień");
        }

        if ($this->userRepository->delete($id)) {
            header("Location: /admin/users" . ($this->getBackParams()));
        }
    }

    private function getBackParams(): string {
        $search = $_GET['search'] ?? '';
        $page = $_GET['page'] ?? 1;
        return "?search=" . urlencode($search) . "&page=" . $page;
    }

    private function isAdmin(): bool
    {
        // role_id == 3 (admin)
        return isset($_SESSION['role_id']) && $_SESSION['role_id'] == 3;
    }
}