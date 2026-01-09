<?php

require 'Routing.php';

session_start();

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);

if (empty($path)) {
    header("Location: /caves");
    exit();
}

Routing::run($path);

?>