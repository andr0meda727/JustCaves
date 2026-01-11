<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/CaveRepository.php';

class AdminController extends AppController {
    private CaveRepository $caveRepository;

    public function __construct() {
        $this->caveRepository = CaveRepository::getInstance();
        $this->checkAdminAccess();
    }

    private function checkAdminAccess() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 3) {
            header('Location: /caves');
            exit();
        }
    }

    public function caves() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;

        $limit = 5;
        
        $caves = $this->caveRepository->getAdminCaves($page, $limit);
        $totalCaves = $this->caveRepository->getTotalCavesCount();
        $totalPages = ceil($totalCaves / $limit);
        $regions = $this->caveRepository->getRegions();
        $statuses = $this->caveRepository->getStatuses();

        return $this->render('admin/caves', [
            'caves' => $caves,
            'pageNumber' => $page,
            'totalPages' => $totalPages,
            'totalCaves' => $totalCaves,
            'regions' => $regions,
            'statuses' => $statuses
        ]);
    }

    public function approveCave(int $caveId) {
        if (!$this->isPost()) {
            header('Location: /admin');
            exit();
        }

        $success = $this->caveRepository->updateStatus(
            $caveId, 
            'APPROVED', 
            $_SESSION['user_id']
        );

        if ($success) {
            $_SESSION['admin_message'] = 'Jaskinia została zatwierdzona';
        } else {
            $_SESSION['admin_error'] = 'Błąd podczas zatwierdzania jaskini';
        }

        header('Location: /admin');
        exit();
    }

    public function rejectCave(int $caveId) {
        if (!$this->isPost()) {
            header('Location: /admin');
            exit();
        }

        $success = $this->caveRepository->updateStatus(
            $caveId, 
            'REJECTED', 
            $_SESSION['user_id']
        );

        if ($success) {
            $_SESSION['admin_message'] = 'Jaskinia została odrzucona';
        } else {
            $_SESSION['admin_error'] = 'Błąd podczas odrzucania jaskini';
        }

        header('Location: /admin');
        exit();
    }
}