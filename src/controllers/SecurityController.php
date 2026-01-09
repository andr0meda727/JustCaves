<?php

require_once 'AppController.php';
require_once __DIR__ . '/../repository/UserRepository.php';
require_once __DIR__ . '/../models/User.php';

class SecurityController extends AppController {
    private UserRepository $userRepository;

    public function __construct() {
        $this->userRepository = UserRepository::getInstance();
    }

    public function login() {
        if ($this->isGet()) {
            return $this->render("login");
        }

        $username = $_POST["username"] ?? '';
        $password = $_POST["password"] ?? '';

        // Validation
        if (empty($username) || empty($password)) {
            return $this->render("login", [
                "error" => "Wszystkie pola są wymagane"
            ]);
        }

        // Find user
        $user = $this->userRepository->findByUsername($username);

        if (!$user) {
            return $this->render("login", [
                "error" => "Nieprawidłowa nazwa użytkownika lub hasło"
            ]);
        }

        $isPasswordValid = password_verify($password, $user->getPasswordHash());

        if (!$isPasswordValid) {
            sleep(1);

            return $this->render("login", [
                "error" => "Nieprawidłowa nazwa użytkownika lub hasło"
            ]);
        }

        if ($user && $isPasswordValid) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user->getId();
            $_SESSION['username'] = $user->getUsername();
            $_SESSION['role_id'] = $user->getRoleId();

            header('Location: /caves');
            exit();
        }
    }

    public function register() {
        if ($this->isGet()) {
            return $this->render("register");
        }

        $username = $_POST["username"] ?? '';
        $email = $_POST["email"] ?? '';
        $password1 = $_POST["password1"] ?? '';
        $password2 = $_POST["password2"] ?? '';

        // Validation
        $errors = $this->validateRegistration($username, $email, $password1, $password2);

        if (!empty($errors)) {
            return $this->render("register", [
                "errors" => $errors,
                "username" => $username,
                "email" => $email
            ]);
        }

        // Check if user already exists
        if ($this->userRepository->usernameExists($username)) {
            return $this->render("register", [
                "errors" => ["Nieprawidłowa nazwa użytkownika lub email"],
                "username" => $username,
                "email" => $email
            ]);
        }

        if ($this->userRepository->emailExists($email)) {
            return $this->render("register", [
                "errors" => ["Nieprawidłowa nazwa użytkownika lub email"],
                "username" => $username,
                "email" => $email
            ]);
        }

        // Create user
        $passwordHash = password_hash($password1, PASSWORD_BCRYPT);
        $user = new User($username, $email, $passwordHash, 1); // role_id = 1 for regular user

        $createdUser = $this->userRepository->create($user);

        if (!$createdUser) {
            return $this->render("register", [
                "errors" => ["Wystąpił błąd podczas rejestracji. Spróbuj ponownie."],
                "username" => $username,
                "email" => $email
            ]);
        }

        // Redirect to login with success message
        return $this->render("login", [
            "success" => "Konto zostało utworzone! Możesz się teraz zalogować."
        ]);
    }

    private function validateRegistration(
        string $username,
        string $email,
        string $password1,
        string $password2
    ): array {
        $errors = [];

        // Check if fields are empty
        if (empty($username)) {
            $errors[] = "Nazwa użytkownika jest wymagana";
        }

        if (empty($email)) {
            $errors[] = "Email jest wymagany";
        }

        if (empty($password1)) {
            $errors[] = "Hasło jest wymagane";
        }

        // Validate username
        if (strlen($username) < 3) {
            $errors[] = "Nazwa użytkownika musi mieć co najmniej 3 znaki";
        }

        if (strlen($username) > 50) {
            $errors[] = "Nazwa użytkownika nie może przekraczać 50 znaków";
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors[] = "Nazwa użytkownika może zawierać tylko litery, cyfry i podkreślenia";
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Nieprawidłowy format email";
        }

        if (strlen($email) > 100) {
            $errors[] = "Nieprawidłowy email";
        }


        // Validate password
        if (strlen($password1) < 8) {
            $errors[] = "Hasło musi mieć co najmniej 8 znaków";
        }

        if ($password1 !== $password2) {
            $errors[] = "Hasła nie są identyczne";
        }

        return $errors;
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        
        header('Location: /login');
        exit();
    }
}