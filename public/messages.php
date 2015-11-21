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

$messages = $t->getHistory($user->phone, 100);
$result = array();
foreach($messages as $message) {
    $result[] = array(
        'text' => nl2br($message->text),
        'from' => $message->from->phone,
        'to' => $message->to->phone,
        'date' => date("y.m.d H:j:s", $message->date),
    );
}

return Response::ok(array('messages' => $result));