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

if (!isset($_POST['text'])) {
    return Response::error("text parameter missing");
}

$th = TelegramCliHelper::getInstance();
$t = new TelegramCliWrapper($th->getSocket(), $th->isDebug());

$t->msg($user->phone, $_POST['text']);

return Response::ok();