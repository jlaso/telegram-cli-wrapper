<?php

session_start();

include_once __DIR__ . '/../vendor/autoload.php';

use TelegramCliWrapper\Storage\LocalFilesStorage;
use TelegramCliWrapper\Response;

if (!isset($_POST['phone'])) {
    return Response::error('phone parameter missed');
}
if (!isset($_POST['code'])) {
    return Response::error('code parameter missed');
}

$code = trim($_POST['code']);
$phone = trim($_POST['phone']);
if (!preg_match("/^\d{9,15}$/", $phone)) {
    return Response::error('phone parameter does not seems a phone number');
}

$userStorage = new LocalFilesStorage('user');
$user = $userStorage->getById($phone);
if (!$user) {
    return Response::error('phone does not exist in this system');
}

if ($user->code <> $code) {
    return Response::error('phone or code are wrong');
}

$user->logged = true;
$userStorage->save($user);

$_SESSION['user'] = $phone;

return Response::ok();
