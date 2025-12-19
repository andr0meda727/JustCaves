<?php

require_once 'AppController.php';


class SecurityController extends AppController {
    public function login() {

        if($this->isGet()) {
            return $this->render("login");
        } 

        $email = $_POST["email"] ?? '';
        $password = $_POST["password"] ?? '';

        var_dump($email, $password);

         $this->render("dashboard");
    }

    public function register() {
        if ($this->isGet()) {
            return $this->render("register");
        }

        $email = $_POST["email"] ?? '';
        $password1 = $_POST["password1"] ?? '';
        $password2 = $_POST["password2"] ?? '';
        $firstname = $_POST["firstname"] ?? '';
        $lastname = $_POST["lastname"] ?? '';


        return $this->render("login", ["message" => "Zarejestrowano uytkownika ".$email]);
    }
}