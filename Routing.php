<?php

require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/CavesController.php';
require_once 'src/controllers/AdminController.php';


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
        'admin' => [
            'controller' => 'AdminController',
            'action' => 'dashboard'
        ],
        'admin-approve' => [
            'controller' => 'AdminController',
            'action' => 'approveCave'
        ],
        'admin-reject' => [
            'controller' => 'AdminController',
            'action' => 'rejectCave'
        ],
        'admin-delete' => [
            'controller' => 'AdminController',
            'action' => 'deleteCave'
        ]
    ];

    public static function run(string $path) {
        $urlParts = explode("/", $path);

        $action = $urlParts[0];

        if (!array_key_exists($action, self::$routes)) {
            include 'public/views/404.html';
            return;
        }

        $controller = self::$routes[$action]['controller'];
        $method = self::$routes[$action]['action'];

        $id = $urlParts[1] ?? null;

        $object = new $controller;

        if ($id) {
            $object->$method($id);
        } else {
            $object->$method();
        }
    }
}