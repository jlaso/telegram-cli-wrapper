<?php

session_start();

include_once __DIR__ . '/../vendor/autoload.php';

use TelegramCliWrapper\TelegramCliWrapper;
use TelegramCliWrapper\TelegramCliHelper;
use TelegramCliWrapper\Storage\LocalFilesStorage;
use TelegramCliWrapper\Response;
use TelegramCliWrapper\Models\User;

if (!isset($_SESSION['user'])) {
    return Response::error("illegal request");
}

$userStorage = new LocalFilesStorage('user');
$user = $userStorage->getById($_SESSION['user']);

if (!$user) {
    return Response::error("user does not exist");
}

$th = TelegramCliHelper::getInstance();
$t = new TelegramCliWrapper($th->getSocket(), $th->isDebug());

$user = $t->getUserInfo($user->phone);
$result = array(
    'phone' => $user->phone,
    'last_name' => $user->last_name,
    'first_name' => $user->first_name,
    'print_name' => $user->print_name,
    'id' => $user->id,
    'flags' => $user->flags,
);

return Response::ok(array('user' => $result));