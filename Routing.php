<?php

require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/CavesController.php';
require_once 'src/controllers/AdminCavesController.php';
require_once 'src/controllers/AdminUsersController.php';


class Routing {
    public static $routes = [
        'login' => [
            'controller' => 'SecurityController',
            'action' => 'login'
        ],
        'register' => [
            'controller' => 'SecurityController',
            'action' => 'register'
        ],
        'logout' => [
            'controller' => 'SecurityController',
            'action' => 'logout'
        ],
        'caves' => [
            'controller' => 'CavesController',
            'action' => 'caves'
        ],
        'cave' => [
            'controller' => 'CavesController',
            'action' => 'cave'
        ],
        'visit' => [
            'controller' => 'CavesController',
            'action' => 'visit'
        ],
        'addCave' => [
            'controller' => 'CavesController',
            'action' => 'addCave'
        ],
        'addComment' => [
            'controller' => 'CavesController',
            'action' => 'addComment'
        ],
        'rateCave' => [
            'controller' => 'CavesController',
            'action' => 'rateCave'
        ],
        'admin/caves' => [
            'controller' => 'AdminCavesController',
            'action' => 'caves'
        ],
        'admin-approve' => [
            'controller' => 'AdminCavesController',
            'action' => 'approveCave'
        ],
        'admin-reject' => [
            'controller' => 'AdminCavesController',
            'action' => 'rejectCave'
        ],
        'admin/users' => [
            'controller' => 'AdminUsersController',
            'action' => 'users'
        ],
        'admin-promote' => [
            'controller' => 'AdminUsersController',
            'action' => 'promote'
        ],
        'admin-delete-user' => [
            'controller' => 'AdminUsersController',
            'action' => 'deleteUser'
        ],
    ];

    public static function run(string $path) {
        $urlParts = explode("/", $path);

        $action = $path;
        $id = null;

        if (!array_key_exists($action, self::$routes)) {
            $action = $urlParts[0];
            $id = $urlParts[1] ?? null;

            if (!array_key_exists($action, self::$routes)) {
                include 'public/views/404.html';
                return;
            }
        }

        $controller = self::$routes[$action]['controller'];
        $method = self::$routes[$action]['action'];

        $object = new $controller;

        if ($id !== null) {
            $object->$method($id);
        } else {
            $object->$method();
        }
    }
}