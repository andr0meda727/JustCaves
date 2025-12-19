<?php

require_once 'src/controllers/SecurityController.php';


class Routing {
    public static $routes = [
        'login' => [
            'controller' => 'SecurityController',
            'action' => 'login'
        ],
    ]

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