<?php

use App\Controller\UserController;
use App\Controller\LeaderboardController;
use App\Main;

require_once dirname(__DIR__, 1) . '/bootstrap.php';

$controllerObj = [
    0 => new UserController(),
    1 => new LeaderboardController()
];

// Endpoints
$controllers = [
    'users' => $controllerObj[0],
    'users/create' => $controllerObj[0],
    'users/delete' => $controllerObj[0],
    'leaderboard' => $controllerObj[1],
    'leaderboard/points/plus' => $controllerObj[1],
    'leaderboard/points/minus' => $controllerObj[1],
    'leaderboard/user' => $controllerObj[1]
];

$request = new \App\Http\Request($_GET, $_POST, $_SERVER, file_get_contents('php://input'));

$main = new Main($controllers);
$main->handle($request);
